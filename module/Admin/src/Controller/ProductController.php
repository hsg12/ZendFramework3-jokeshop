<?php

namespace Admin\Controller;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Admin\Service\FormServiceInterface;
use Application\Entity\Product;
use Application\Entity\Category;
use Zend\Form\FormInterface;
use Authentication\Service\ValidationServiceInterface;

class ProductController extends AbstractActionController
{
    private $entityManager;
    private $formService;
    private $validationService;

    public function __construct(
        EntityManagerInterface $entityManager,
        FormServiceInterface $formService,
        ValidationServiceInterface $validationService
    ) {
        $this->entityManager     = $entityManager;
        $this->formService       = $formService;
        $this->validationService = $validationService;
    }

    private function getRepository()
    {
        return $this->entityManager->getRepository(Product::class);
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function hasChildCategories($request, $form)
    {
        $categoryId = (int)$request->getPost('category');
        $childCategories = $this->entityManager->getRepository(Category::class)->findBy(['parentId' => $categoryId]);

        if ($childCategories) {
            $form->get('category')->setMessages(['Cannot add product to category which has child category']);
        }
    }

    public function addAction()
    {
        $product = new Product();
        $form = $this->formService->getAnnotationForm($this->entityManager, $product);
        $form->setValidationGroup(FormInterface::VALIDATE_ALL);

        $request = $this->getRequest();
        if ($request->isPost()) {
            /* If name exists already */
            if ($this->validationService->isObjectExists($this->getRepository(), $request->getPost('name'), ['name'])) {
                $nameExists = 'Product with name "' . $request->getPost('name') . '" exists already';
                $form->get('name')->setMessages(['nameExists' => $nameExists]);
            }

            /* In order it was impossible to add an article to a category which has a child category */
            $this->hasChildCategories($request, $form);

            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $files = $request->getFiles()->toArray();
            if ($files) { $fileName = $files['file']['name']; }

            $form->setData($data);

            if ($form->isValid() && empty($form->getMessages())) {
                $product = $form->getData();
                if ($fileName) {
                    $product->setImage('/img/products/' . $fileName);
                }

                $this->entityManager->persist($product);
                $this->entityManager->flush();

                $this->flashMessenger()->setNamespace('success')->addMessage('Product added');

                return $this->redirect()->toRoute('admin/products');
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $product = $this->getRepository()->find($id);

        if (! $product) {
            return $this->notFoundAction();
        }

        $form = $this->formService->getAnnotationForm($this->entityManager, $product);
        $form->setValidationGroup(FormInterface::VALIDATE_ALL);

        $request = $this->getRequest();
        if ($request->isPost()) {

            /* In order it was impossible to add an article to a category which has a child category */
            $this->hasChildCategories($request, $form);

            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $files = $request->getFiles()->toArray();
            if ($files) { $fileName = $files['file']['name']; }

            $form->setData($data);

            if ($form->isValid() && empty($form->getMessages())) {
                $product = $form->getData();
                if ($fileName) {
                    $product->setImage('/img/products/' . $fileName);
                }

                $this->entityManager->persist($product);
                $this->entityManager->flush();

                $this->flashMessenger()->setNamespace('success')->addMessage('Product added');

                return $this->redirect()->toRoute('admin/products');
            }
        }
        return new ViewModel([
            'id'      => $id,
            'form'    => $form,
            'product' => $product,
        ]);
    }

    public function searchAction()
    {
        $searchResult = '';
        $response = $this->getResponse();
        $request  = $this->getRequest();
        if (! $request->isPost()) {
            return $this->notFoundAction();
        }

        if (! empty($request->getPost('findProduct'))) {
            $search = $request->getPost('findProduct');

            $filterStringTrim = new StringTrim();
            $search = $filterStringTrim->filter($search);

            $filterStripTags = new StripTags();
            $search = $filterStripTags->filter($search);

            $searchResult = $this->getRepository()->search($this->entityManager, $search);
        }

        $response->setContent(\Zend\Json\Json::encode($searchResult));
        return $response;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id', 0);
        $product = $this->getRepository()->find($id);

        if (! $request->isPost() || ! $product) {
            return $this->notFoundAction();
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        $this->flashMessenger()->addSuccessMessage('Product deleted');
        return $this->redirect()->toRoute('admin/products');
    }
}
