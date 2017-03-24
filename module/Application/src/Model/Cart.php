<?php

namespace Application\Model;

use Zend\Session\Container;

class Cart
{
    protected static function sessionContainer()
    {
        $container = new Container('products');
        $container->setExpirationSeconds(30*60);
        return $container;
    }

    public static function addProduct($id)
    {
        $id = abs((int)$id);
        $productsInCart = [];

        if (isset(self::sessionContainer()->products)) {
            $productsInCart = self::sessionContainer()->products;
        }

        if (array_key_exists($id, $productsInCart)) {
            $productsInCart[$id]++;
        } else {
            $productsInCart[$id] = 1;
        }

        self::sessionContainer()->products = $productsInCart;

        return self::countProducts();
    }

    public static function subtractProduct($id)
    {
        $id = abs((int)$id);
        $productsInCart = [];

        if (isset(self::sessionContainer()->products)) {
            $productsInCart = self::sessionContainer()->products;
        }

        if (array_key_exists($id, $productsInCart)) {
            if ($productsInCart[$id] > 1) {
                $productsInCart[$id]--;
            }
        }

        self::sessionContainer()->products = $productsInCart;

        return self::countProducts();
    }

    public static function countProducts()
    {
        if (isset(self::sessionContainer()->products)) {
            $count = 0;
            foreach (self::sessionContainer()->products as $id => $quantity) {
                $count = $count + $quantity;
            }
            return $count;
        } else {
            return 0;
        }
    }

    public static function countConcreteProduct($id)
    {
        $id = abs((int)$id);

        if (isset(self::sessionContainer()->products[$id])) {
            return self::sessionContainer()->products[$id];
        } else {
            return 0;
        }
    }

    public static function getProducts()
    {
        if (isset(self::sessionContainer()->products)) {
            return self::sessionContainer()->products;
        } else {
            return false;
        }
    }

    public static function getTotalPrice($products)
    {
        $productsInCart = self::getProducts();
        $total = 0;

        if ($productsInCart && $products) {
            foreach ($products as $product) {
                $total += $product->getPrice() * $productsInCart[$product->getId()];
            }
        }

        return $total;
    }

    public static function clearProductsSession()
    {
        $container = new Container('products');

        if ($container) {
            $container->getManager()->getStorage()->clear('products');
            return true;
        }

        return false;
    }
}
