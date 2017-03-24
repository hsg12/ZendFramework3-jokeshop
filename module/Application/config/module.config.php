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
                'type' => Segment::class,
                'options' => [
                    'route'    => '/[page/:page][home/:action[/:id]]',
                    'constraints' => [
                        'page'   => '[0-9]+',
                        'action' => '[a-z]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'product' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'       => 'product[/:id]',
                            'constraints' => [
                                'id' => '[0-9]+'
                            ],
                            'defaults' => [
                                'controller' => Controller\ProductController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                ],
            ],
            'about-us' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/about-us',
                    'defaults' => [
                        'controller' => Controller\AboutController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'category' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/category[/:id][/page/:page]',
                    'constraints' => [
                        'page' => '[0-9]+',
                        'id'   => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\CategoryController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'basket' => [
                'type' => Segment::class,
                'options' => [
                    'route'       => '/basket[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-z]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\BasketController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'order' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/order[/:action]',
                    'constraints' => [
                        'action' => '[a-z]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\OrderController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\AboutController::class => InvokableFactory::class,
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
                    'contact' => [
                        'label' => 'Contact Us',
                        'route' => 'contact-us',
                    ],
                    'portfolio' => [
                        'label' => 'Portfolio',
                        'route' => 'portfolio',
                    ],
                    'about' => [
                        'label' => 'About us',
                        'route' => 'about-us',
                    ],
                    'category' => [
                        'label' => 'Categories',
                        'route' => 'category',
                    ],
                    'basket' => [
                        'label' => 'Basket',
                        'route' => 'basket',
                    ],
                    'order' => [
                        'label' => 'Orders',
                        'route' => 'order',
                        'pages' => [
                            'add' => [
                                'label'  => 'Add',
                                'route'  => 'order',
                                'action' => 'add',
                            ],
                        ],
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
                                'pages' => [
                                    'add' => [
                                        'label'  => 'Add',
                                        'route'  => 'admin/products',
                                        'action' => 'add',
                                    ],
                                    'edit' => [
                                        'label'  => 'Edit',
                                        'route'  => 'admin/products',
                                        'action' => 'edit',
                                    ],
                                ],
                            ],
                            'slider' => [
                                'label' => 'Slider',
                                'route' => 'admin/slider',
                                'pages' => [
                                    'add' => [
                                        'label'  => 'Add',
                                        'route'  => 'admin/slider',
                                        'action' => 'add',
                                    ],
                                    'edit' => [
                                        'label'  => 'Edit',
                                        'route'  => 'admin/slider',
                                        'action' => 'edit',
                                    ],
                                ],
                            ],
                            'user' => [
                                'label' => 'Users',
                                'route' => 'admin/users',
                            ],
                            'order' => [
                                'label' => 'Orders',
                                'route' => 'admin/orders',
                                'pages' => [
                                    'add' => [
                                        'label'  => 'Order data',
                                        'route'  => 'admin/orders',
                                        'action' => 'show',
                                    ],
                                ],
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
                'route' => 'about-us',
            ],
            'contact' => [
                'label' => 'Contact Us',
                'route' => 'contact-us',
            ],
            'portfolio' => [
                'label' => 'Portfolio',
                'route' => 'portfolio',
            ],
        ],
    ],
];
