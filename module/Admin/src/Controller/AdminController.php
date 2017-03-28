<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Admin\Service\FormServiceInterface;
use Application\Entity\User;

class AdminController extends AbstractActionController
{
    private $entityManager;
    private $formService;
    private $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        FormServiceInterface $formService
    ) {
        $this->entityManager = $entityManager;
        $this->formService   = $formService;
        $this->repository    = $this->entityManager->getRepository(User::class);
    }

    public function indexAction()
    {
        $admins = $this->repository->findBy(['role' => 'admin']);

        return new ViewModel([
            'admins' => $admins,
        ]);
    }

    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $admin = $this->repository->find($id);

        if (! $id || ! $admin) {
            return $this->notFoundAction();
        }

        $form = $this->formService->getAnnotationForm($this->entityManager, $admin);
        $form->setValidationGroup(['role']);

        $request = $this->getRequest();
        if ($request->getPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $admin = $form->getData();

                $this->entityManager->persist($admin);
                $this->entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('Admin edited');

                return $this->redirect()->toRoute('admin/admins');
            }
        }

        return new ViewModel([
            'id' => $id,
            'form' => $form,
        ]);
    }

    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $admin = $this->repository->find($id);
        $request = $this->getRequest();

        if (! $id || ! $admin || ! $request->isPost()) {
            return $this->notFoundAction();
        }

        $this->entityManager->remove($admin);
        $this->entityManager->flush();

        $this->flashMessenger()->addSuccessMessage('Admin deleted');

        return $this->redirect()->toRoute('admin/admins');
    }
}