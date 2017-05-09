<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Navigation\Service\DefaultNavigationFactory;
use Doctrine\ORM\EntityManager;

class Module
{
    const VERSION = '3.0.2dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getViewHelperConfig()
    {
        return [
            'invokables' => [
                'getYear'           => View\Helper\GetYear::class,
                'getFlashMessenger' => View\Helper\GetFlashMessenger::class,
                'cutStr'            => View\Helper\CutStr::class,
                'getImage'          => View\Helper\GetImage::class,
            ],
            'factories' => [
                'getCategories' => function ($container) {
                    return new View\Helper\GetCategories(
                        $container->get(EntityManager::class)
                    );
                },
                'GetRouteParams' => function ($container) {
                    return new View\Helper\GetRouteParams(
                        $container->get('Application')
                    );
                },
                'getCarousel' => function ($container) {
                    return new View\Helper\GetCarousel(
                        $container->get(EntityManager::class)
                    );
                },
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'top_navigation' => Service\TopNavigationFactory::class,
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\IndexController::class => function ($container) {
                    return new Controller\IndexController(
                        $container->get(EntityManager::class)
                    );
                },
                Controller\CategoryController::class => function ($container) {
                    return new Controller\CategoryController(
                        $container->get(EntityManager::class)
                    );
                },
                Controller\ProductController::class => function ($container) {
                    return new Controller\ProductController(
                        $container->get(EntityManager::class)
                    );
                },
                Controller\BasketController::class => function ($container) {
                    return new Controller\BasketController(
                        $container->get(EntityManager::class)
                    );
                },
                Controller\OrderController::class => function ($container) {
                    return new Controller\OrderController(
                        $container->get(EntityManager::class),
                        $container->get('formService')
                    );
                },
            ],
        ];
    }
}
