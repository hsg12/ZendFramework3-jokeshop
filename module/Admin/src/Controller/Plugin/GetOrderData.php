<?php

namespace Admin\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Product;

class GetOrderData extends AbstractPlugin
{
    public function __invoke(EntityManagerInterface $entityManager, $userOrders)
    {
        $dataArray = [];

        if (is_object($userOrders)) {
            // For finding concrete order
            $products = json_decode($userOrders->getProducts(), true);

            $productsArray = [];
            foreach ($products as $id => $quantity) {
                $product = $entityManager->getRepository(Product::class)->find($id);
                $productsArray[] = [$product, $quantity];
            }

            $dataArray[] = ['userData' => $userOrders, 'productData' => $productsArray];
        } else {
            // For finding all orders related to user
            foreach ($userOrders as $order) {
                $products = json_decode($order->getProducts(), true);

                $productsArray = [];
                foreach ($products as $id => $quantity) {
                    $product = $entityManager->getRepository(Product::class)->find($id);
                    $productsArray[] = [$product, $quantity];
                }

                $dataArray[] = ['userData' => $order, 'productData' => $productsArray];
            }
        }

        return $dataArray ? $dataArray : false;
    }
}
