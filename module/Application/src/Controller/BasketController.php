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
            $totalCount = Cart::countProducts();

            array_unshift($dataArray, $productsSession, $products, $totalPrice, $totalCount);
            return $dataArray;
        }
    }

    public function indexAction()
    {
        if (! $this->identity()) {
            return $this->notFoundAction();
        }

        $dataArray = $this->getData();

        $this->layout('layout/alternativeLayout');
        return new ViewModel([
            'productsSession' => $dataArray[0],
            'products'        => $dataArray[1],
            'totalPrice'      => $dataArray[2],
            'totalCount'      => (int)$dataArray[3],
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
        Cart::clearProductsSession();
        return $this->redirect()->toRoute('basket');
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        if (! $request->isPost()) {
            return $this->notFoundAction();
        }

        $id = (int)$this->params()->fromRoute('id', 0);
        $selectedProducts = Cart::getProducts();
        $container = new Container('products');

        if ($id && $selectedProducts) {
            unset($selectedProducts[$id]);
            $container->products = $selectedProducts;
        }

        return $this->redirect()->toRoute('basket');
    }
}
