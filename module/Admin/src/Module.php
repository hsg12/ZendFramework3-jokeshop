<?php

namespace Admin;

use Zend\ModuleManager\ModuleManager;
use Zend\Authentication\AuthenticationService;
use Doctrine\ORM\EntityManager;

class Module
{
    const VERSION = '3.0.2dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'formService' => Service\FormService::class,
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\ProductController::class => function ($container) {
                    return new Controller\ProductController(
                        $container->get(EntityManager::class),
                        $container->get('formService'),
                        $container->get('validationService')
                    );
                },
                Controller\OrderController::class => function ($container) {
                    return new Controller\OrderController(
                        $container->get(EntityManager::class),
                        $container->get('formService')
                    );
                },
                Controller\SliderController::class => function ($container) {
                    return new Controller\SliderController(
                        $container->get(EntityManager::class),
                        $container->get('formService')
                    );
                },
                Controller\CategoryController::class => function ($container) {
                    return new Controller\CategoryController(
                        $container->get(EntityManager::class),
                        $container->get('formService'),
                        $container->get('validationService')
                    );
                },
                Controller\UserController::class => function ($container) {
                    return new Controller\UserController(
                        $container->get(EntityManager::class),
                        $container->get('formService')
                    );
                },
                Controller\AdminController::class => function ($container) {
                    return new Controller\AdminController(
                        $container->get(EntityManager::class),
                        $container->get('formService')
                    );
                },
            ],
        ];
    }

    public function getControllerPluginConfig()
    {
        return [
            'invokables' => [
                'getOrderData' => Controller\Plugin\GetOrderData::class,
            ],
            'factories' => [
                'getAccess' => function ($container) {
                    return new Controller\Plugin\GetAccess(
                        $container->get(AuthenticationService::class)
                    );
                },
            ],
        ];
    }

    public function getViewHelperConfig()
    {
        return [
            'factories' => [
                'getCategoryParentName' => function ($container) {
                    return new View\Helper\GetCategoryParentName(
                        $container->get(EntityManager::class)
                    );
                },
            ],
        ];
    }

    public function init(ModuleManager $moduleManager)
    {
        $moduleManager->getEventManager()->getSharedManager()->attach(
            __NAMESPACE__,
            'dispatch',
            function ($e) {
                $controller = $e->getTarget();
                $controller->getAccess();
                $controller->layout('layout/adminLayout');
            },
            100
        );
    }
}
