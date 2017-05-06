<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Admin\Service\FormServiceInterface;
use Application\Entity\User;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

class UserController extends AbstractActionController
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
        $usersQueryBuilder = $this->repository->getUsersQueryBuilder();

        $adapter = new DoctrinePaginator(new ORMPaginator($usersQueryBuilder));
        $paginator = new Paginator($adapter);

        $currentPageNumber = (int)$this->params()->fromRoute('page', 1);
        $paginator->setCurrentPageNumber($currentPageNumber);


        $itemCountPerPage = 1;
        $paginator->setItemCountPerPage($itemCountPerPage);

        return new ViewModel([
            'users' => $paginator,
            'productsCnt'  => ($currentPageNumber - 1) * $itemCountPerPage,
        ]);
    }

    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $user = $this->repository->find($id);

        if (! $id || ! $user) {
            return $this->notFoundAction();
        }

        $form = $this->formService->getAnnotationForm($this->entityManager, $user);
        $form->setValidationGroup(['role']);

        $request = $this->getRequest();
        if ($request->getPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $user = $form->getData();

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('User edited');

                return $this->redirect()->toRoute('admin/users');
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
        $user = $this->repository->find($id);
        $request = $this->getRequest();

        if (! $id || ! $user || ! $request->isPost()) {
            return $this->notFoundAction();
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->flashMessenger()->addSuccessMessage('User deleted');

        return $this->redirect()->toRoute('admin/users');
    }
}
