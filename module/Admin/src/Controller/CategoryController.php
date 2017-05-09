<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Admin\Service\FormServiceInterface;
use Application\Entity\Category;
use Application\Entity\Product;
use Authentication\Service\ValidationServiceInterface;

class CategoryController extends AbstractActionController
{
    private $entityManager;
    private $formService;
    private $validationService;
    private $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        FormServiceInterface $formService,
        ValidationServiceInterface $validationService
    ) {
        $this->entityManager     = $entityManager;
        $this->formService       = $formService;
        $this->validationService = $validationService;
        $this->repository        = $this->entityManager->getRepository(Category::class);
    }

    public function indexAction()
    {
        $categories = $this->repository->findAll();
        return new ViewModel([
            'categories' => $categories,
        ]);
    }

    public function addAction()
    {
        $category = new category();
        $form = $this->formService->getAnnotationForm($this->entityManager, $category);

        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($this->validationService->isObjectExists($this->repository, $request->getPost('name'), ['name'])) {
                $nameExists = 'Category with name "' . $request->getPost('name') . '" exists already';
                $form->get('name')->setMessages(['nameExists' => $nameExists]);
            }

            $form->setData($request->getPost());

            if ($form->isValid() && empty($form->getMessages())) {
                $category = $form->getData();

                /* In order do not select parent category (create new category)*/
                if ($category->getParentId() == 0) {
                    $category->setParentId(null);
                }

                $this->entityManager->persist($category);
                $this->entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('Category added');

                return $this->redirect()->toRoute('admin/categories');
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $category = $this->repository->find($id);

        if (! $id || ! $category) {
            return $this->notFoundAction();
        }
        $form = $this->formService->getAnnotationForm($this->entityManager, $category);
        $form->setValidationGroup(['name', 'parentId']);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            $categoryNameOld = $category->getName();
            $categoryNameNew = trim(strip_tags($form->get('name')->getValue()));

            if ($this->repository->findBy(['name' => $categoryNameNew]) && $categoryNameNew != $categoryNameOld) {
                $nameExists = 'Category with name "' . $categoryNameNew . '" exists already';
                $form->get('name')->setMessages(['nameExists' => $nameExists]);
            }

            if ($form->isValid() && empty($form->getMessages())) {
                $category = $form->getData();

                $this->entityManager->persist($category);
                $this->entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('Category edited');

                return $this->redirect()->toRoute('admin/categories');
            }
        }

        return new ViewModel([
            'id'   => $id,
            'form' => $form,
        ]);
    }

    private function getNestedCategoriesChain($categoryId)
    {
        $result = [];
        $categories = $this->repository->findBy(['parentId' => $categoryId]);
        if (! empty($categories)) {
            foreach ($categories as $category) {
                if (!empty($category)) {
                    $result[] = $category;
                    $result[] = $this->getNestedCategoriesChain($category->getId());
                }
            }
        }

        return $result;
    }

    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $category = $this->repository->find($id);
        $request = $this->getRequest();

        if (! $id || ! $category || ! $request->isPost()) {
            return $this->notFoundAction();
        }

        /* Block for deletion nested products images (on server) (If category has nested categories) */
        $nestedCategoriesChain = $this->getNestedCategoriesChain($id);

        array_walk_recursive($nestedCategoriesChain, function($value) {
            $products = $this->entityManager->getRepository(Product::class)->findBy(['category' => $value->getId()]);

            if (isset($products)) {
                array_walk_recursive($products, function($product){
                    if (is_file(getcwd() . '/public_html' . $product->getImage())) {
                        unlink(getcwd() . '/public_html' . $product->getImage());
                    }
                });
            }
        });
        /* End block */

        /* Block for deletion products images in category (on server) (If category has not nested categories) */
        $products = $this->entityManager->getRepository(Product::class)->findBy(['category' => $category]);

        if ($products) {
            foreach ($products as $product) {
                if (is_file(getcwd() . '/public_html' . $product->getImage())) {
                    unlink(getcwd() . '/public_html' . $product->getImage());
                }
            }
        }
        /* End block */

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        $this->flashMessenger()->addSuccessMessage('Category deleted');

        return $this->redirect()->toRoute('admin/categories');
    }
}
