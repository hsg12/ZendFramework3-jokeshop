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
                'getYear' => View\Helper\GetYear::class,
            ],
            'factories' => [
                'getCategories' => function ($container) {
                    return new View\Helper\GetCategories(
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
                Controller\CategoryController::class => function ($container) {
                    return new Controller\CategoryController(
                        $container->get(EntityManager::class)
                    );
                },
            ],
        ];
    }
}
