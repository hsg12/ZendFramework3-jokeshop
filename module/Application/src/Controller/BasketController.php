<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Cart;
use Zend\Session\Container;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Product;

class BasketController extends AbstractActionController
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Product::class);
    }

    private function getData()
    {
        $dataArray = [];
        $products = false;
        $totalPrice = 0;
        $productsSession = Cart::getProducts();

        if ($productsSession) {
            $ids = array_keys($productsSession);
            $products = $this->repository->getSelectedProductsByIds($this->entityManager, $ids);

            $totalPrice = Cart::getTotalPrice($products);

            array_unshift($dataArray, $productsSession, $products, $totalPrice);
            return $dataArray;
        }
    }

    public function indexAction()
    {
        $dataArray = $this->getData();

        $this->layout('layout/alternativeLayout');
        return new ViewModel([
            'productsSession' => $dataArray[0],
            'products'        => $dataArray[1],
            'totalPrice'      => $dataArray[2],
        ]);
    }

    public function addAction()
    {
        /*$id = abs((int)$this->getEvent()->getRouteMatch()->getParam('id', 0));
        Cart::addProduct($id);

        $referrer = $_SERVER['HTTP_REFERER'];
        header("Location: $referrer");
        exit;*/

        $request  = $this->getRequest();
        $response = $this->getResponse();

        if (! $request->isPost()) {
            return $this->notFoundAction();
        }

        $id = abs((int)$this->getEvent()->getRouteMatch()->getParam('id', 0));

        if ($id) {
            $countProducts = Cart::addProduct($id);
            $countConcreteProduct = Cart::countConcreteProduct($id);
            $product = $this->repository->find($id);

            $dataArray = $this->getData();

            $response->setContent(\Zend\Json\Json::encode([
                'countProducts'        => $countProducts,
                'countConcreteProduct' => $countConcreteProduct,
                'price'                => $product->getPrice(),
                'totalPrice'           => $dataArray[2],
            ]));
        }

        return $response;
    }

    public function subtractAction()
    {
        $request  = $this->getRequest();
        $response = $this->getResponse();

        if (! $request->isPost()) {
            return $this->notFoundAction();
        }

        $id = abs((int)$this->getEvent()->getRouteMatch()->getParam('id', 0));

        if ($id) {
            $countProducts = Cart::subtractProduct($id);
            $countConcreteProduct = Cart::countConcreteProduct($id);
            $product = $this->repository->find($id);

            $dataArray = $this->getData();

            $response->setContent(\Zend\Json\Json::encode([
                'countProducts'        => $countProducts,
                'countConcreteProduct' => $countConcreteProduct,
                'price'                => $product->getPrice(),
                'totalPrice'           => $dataArray[2],
            ]));
        }

        return $response;
    }

    public function clearAction()
    {
        $container = new Container('products');
        $container->getManager()->getStorage()->clear('products');

        return $this->redirect()->toRoute('basket');
    }

    public function deleteAction()
    {

    }
}
