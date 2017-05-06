<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\ProductOrder;
use Doctrine\ORM\EntityManagerInterface;
use Admin\Service\FormServiceInterface;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

class OrderController extends AbstractActionController
{
    private $entityManager;
    private $formService;

    public function __construct(
        EntityManagerInterface $entityManager,
        FormServiceInterface $formService
    ) {
        $this->entityManager = $entityManager;
        $this->formService = $formService;
    }

    public function indexAction()
    {
        $productOrderQueryBuilder = $this->entityManager
                                     ->getRepository(ProductOrder::class)
                                     ->getProductOrderQueryBuilder();

        $adapter = new DoctrinePaginator(new ORMPaginator($productOrderQueryBuilder));
        $paginator = new Paginator($adapter);

        $currentPageNumber = (int)$this->params()->fromRoute('page', 1);
        $paginator->setCurrentPageNumber($currentPageNumber);

        $itemCountPerPage = 10;
        $paginator->setItemCountPerPage($itemCountPerPage);

        $pageNumber = (int)$paginator->getCurrentPageNumber();

        return new ViewModel([
            'orders'     => $paginator,
            'pageNumber' => $pageNumber,
            'ordersCnt'  => ($currentPageNumber - 1) * $itemCountPerPage,
        ]);
    }

    public function showAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id', 0);
        $page = (int)$this->getEvent()->getRouteMatch()->getParam('page', 0);
        $order = $this->entityManager
                          ->getRepository(ProductOrder::class)
                          ->find($id);

        if (! $id || ! $page || ! $order) {
            return $this->notFoundAction();
        }

        $orderData = $this->getOrderData($this->entityManager, $order);

        $form = $this->formService->getAnnotationForm($this->entityManager, $order);
        $form->setValidationGroup(['status']);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $order = $form->getData();

                $this->entityManager->persist($order);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('admin/orders', ['page' => $page]);
            }
        }

        return new ViewModel([
            'orderData' => $orderData,
            'form'      => $form,
            'id'        => $id,
            'page'      => $page,
        ]);
    }

    public function deleteAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id', 0);
        $page = (int)$this->getEvent()->getRouteMatch()->getParam('page', 0);
        $order = $this->entityManager
                      ->getRepository(ProductOrder::class)
                      ->find($id);

        $request = $this->getRequest();

        if (! $id || ! $order || !$page || ! $request->isPost()) {
            return $this->notFoundAction();
        }

        $this->entityManager->remove($order);
        $this->entityManager->flush();

        $this->flashMessenger()->addSuccessMessage('Order deleted');
        return $this->redirect()->toRoute('admin/orders', ['page' => $page]);
    }
}
