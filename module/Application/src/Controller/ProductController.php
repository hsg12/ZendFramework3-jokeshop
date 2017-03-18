<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Product;
use Application\Model\Cart;

class ProductController extends AbstractActionController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $this->layout('layout/alternativeLayout');
        $id = (int)$this->params()->fromRoute('id', 0);
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        return new ViewModel([
            'product' => $product,
            'productCount' => Cart::countConcreteProduct((int)$product->getId()),
        ]);
    }
}
