<?php

namespace Admin;

use Admin\Controller\IndexController;
use Admin\Controller\LogowanieController;
use Admin\Navigation\Service\AdminNavigationFactory;
use Admin\Service\AuthService;
use Admin\View\Helper\FlashMessenger;
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\LazyControllerAbstractFactory;
use Laminas\Router\Http\Literal;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'logowanie' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/logowanie',
                    'defaults' => [
                        'controller' => LogowanieController::class,
                        'action' => 'zaloguj'
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'wyloguj' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/wyloguj',
                            'defaults' => [
                                'action' => 'wyloguj',
                            ]
                        ]
                    ]
                ],
            ],
            'admin' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/admin',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index'
                    ]
                ],
            ]
        ]
    ],
    'service_manager' => [
        'factories' => [
            'admin_navigation' => AdminNavigationFactory::class,
            AuthenticationService::class => InvokableFactory::class,
            AuthService::class => ReflectionBasedAbstractFactory::class,
        ]
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => InvokableFactory::class,
            LogowanieController::class => LazyControllerAbstractFactory::class,
        ]
    ],
    'navigation' => [
        'admin' => [
            [
                'label' => 'Strona główna',
                'route' => 'admin',
            ],
            [
                'label' => 'Wyloguj',
                'route' => 'logowanie/wyloguj'
            ]
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'template_map' => [
            'layout/administracja' => __DIR__ . '/../view/layout/administracja.phtml',
            'layout/logowanie' => __DIR__ . '/../view/layout/logowanie.phtml',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'view_helpers' => [
        'factories' => [
            FlashMessenger::class => InvokableFactory::class,
        ],
        'aliases' => [
            'fm' => FlashMessenger::class,
        ]
    ]
];
