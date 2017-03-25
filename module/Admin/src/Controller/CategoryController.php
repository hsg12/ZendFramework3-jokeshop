<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Admin\Service\FormServiceInterface;
use Application\Entity\Category;
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
        return new ViewModel();
    }
}
