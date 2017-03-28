<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'categories' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/categories[/:action[/:id]]',
                            'constraints'    => [
                                'action' => '[a-z-]*',
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\CategoryController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'products' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/products[/page/:page][/:action[/:id]]',
                            'constraints'    => [
                                'action' => '[a-z-]*',
                                'id'     => '[0-9]+',
                                'page'   => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\ProductController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'slider' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/slider[/:action[/:id]]',
                            'constraints'    => [
                                'action' => '[a-z-]*',
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\SliderController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'users' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/users[/page/:page][/:action[/:id]]',
                            'constraints'    => [
                                'action' => '[a-z-]*',
                                'id'     => '[0-9]+',
                                'page'   => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'admins' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/admins[/:action[/:id]]',
                            'constraints'    => [
                                'action' => '[a-z-]*',
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\AdminController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'orders' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/orders[/page/:page][/:action[/:id]]',
                            'constraints'    => [
                                'action' => '[a-z-]*',
                                'id'     => '[0-9]+',
                                'page'   => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\OrderController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'admin/index/index' => __DIR__ . '/../view/admin/index/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
