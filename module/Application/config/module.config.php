<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'about-us' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => 'about-us',
                            'defaults' => [
                                'controller' => Controller\AboutController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'basket' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => 'basket',
                            'defaults' => [
                                'controller' => Controller\BasketController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'order' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => 'order',
                            'defaults' => [
                                'controller' => Controller\OrderController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'category' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'       => 'category[/:id]',
                            'constraints' => [
                                'id' => '[0-9]+'
                            ],
                            'defaults' => [
                                'controller' => Controller\CategoryController::class,
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
            Controller\AboutController::class => InvokableFactory::class,
            Controller\BasketController::class => InvokableFactory::class,
            Controller\OrderController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'navigation' => [
        'default' => [
            'home' => [
                'label' => 'Home',
                'route' => 'home',
                'pages' => [
                    'register' => [
                        'label' => 'Register',
                        'route' => 'register',
                    ],
                    'login' => [
                        'label' => 'Login',
                        'route' => 'login',
                    ],
                    'about' => [
                        'label' => 'About us',
                        'route' => 'home/about-us',
                    ],
                    'basket' => [
                        'label' => 'Basket',
                        'route' => 'home/basket',
                    ],
                    'order' => [
                        'label' => 'Orders',
                        'route' => 'home/order',
                    ],
                    'category' => [
                        'label' => 'Categories',
                        'route' => 'home/category',
                    ],
                    'contact' => [
                        'label' => 'Contact Us',
                        'route' => 'contact-us',
                    ],
                    'portfolio' => [
                        'label' => 'Portfolio',
                        'route' => 'portfolio',
                    ],
                    'admin' => [
                        'label' => 'Admin area',
                        'route' => 'admin',
                        'pages' => [
                            'category' => [
                                'label' => 'Categories',
                                'route' => 'admin/categories',
                            ],
                            'product' => [
                                'label' => 'Products',
                                'route' => 'admin/products',
                            ],
                            'slider' => [
                                'label' => 'Slider',
                                'route' => 'admin/slider',
                            ],
                            'user' => [
                                'label' => 'Users',
                                'route' => 'admin/users',
                            ],
                            'order' => [
                                'label' => 'Orders',
                                'route' => 'admin/orders',
                            ],
                        ],
                    ],

                ],
            ],

        ],
        'top_navigation' => [
            'home' => [
                'label' => 'Home',
                'route' => 'home',
            ],
            'about' => [
                'label' => 'About',
                'route' => 'home/about-us',
            ],
            'contact' => [
                'label' => 'Contact Us',
                'route' => 'contact-us',
            ],
            'portfolio' => [
                'label' => 'Portfolio',
                'route' => 'portfolio',
            ],
            'admin' => [
                'label' => 'Admin area',
                'route' => 'admin',
            ],
        ],
    ],
];
