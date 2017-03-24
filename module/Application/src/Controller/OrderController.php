<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\ProductOrder;
use Application\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Admin\Service\FormServiceInterface;
use Application\Model\Cart;

class OrderController extends AbstractActionController
{
    private $entityManager;
    private $formService;

    public function __construct(
        EntityManagerInterface $entityManager,
        FormServiceInterface $formService
    ) {
        $this->entityManager = $entityManager;
        $this->formService   = $formService;
    }

    public function indexAction()
    {
        $user = $this->identity();
        if (! $user) {
            return $this->notFoundAction();
        }

        $userOrders = $this->entityManager
                           ->getRepository(ProductOrder::class)
                           ->getOrders($this->entityManager, (int)$user->getId());

        $dataArray = $this->getOrderData($this->entityManager, $userOrders);

        $this->layout('layout/alternativeLayout');
        return new ViewModel([
            'userOrders' => $dataArray,
        ]);
    }

    public function addAction()
    {
        $selectedProducts = Cart::getProducts();
        if (! $selectedProducts) {
            return $this->notFoundAction();
        }

        $selectedProductsJson = json_encode($selectedProducts);

        $order = new ProductOrder();

        $user = $this->identity();
        $order->setUserId($user->getId());
        $order->setUserName($user->getName());
        $order->setUserEmail($user->getEmail());
        $order->setProducts($selectedProductsJson);

        $form = $this->formService->getAnnotationForm($this->entityManager, $order);
        $form->setValidationGroup(['userPhone', 'userAddress']);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $order = $form->getData();

                $this->entityManager->persist($order);
                $this->entityManager->flush();

                Cart::clearProductsSession();

                return $this->redirect()->toRoute('order');
            }
        }

        $this->layout('layout/alternativeLayout');
        return new ViewModel([
            'form' => $form,
        ]);
    }
}
