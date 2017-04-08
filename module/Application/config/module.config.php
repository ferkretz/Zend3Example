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
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'computer' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/computer[/:action][/:id][/:sub_action][/:sub_id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'sub_action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'sub_id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ComputerController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'memory' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/memory[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\MemoryController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'processor' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/processor[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ProcessorController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
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
    'service_manager' => [
        'factories' => [
            Service\ComputerManager::class => Service\Factory\ComputerManagerFactory::class,
            Service\MemoryManager::class => Service\Factory\MemoryManagerFactory::class,
            Service\ProcessorManager::class => Service\Factory\ProcessorManagerFactory::class,
        ],
    ], 'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\ComputerController::class => Controller\Factory\ComputerControllerFactory::class,
            Controller\MemoryController::class => Controller\Factory\MemoryControllerFactory::class,
            Controller\ProcessorController::class => Controller\Factory\ProcessorControllerFactory::class,
        ],
    ],
    'translator' => [
        'locale' => 'hu_HU',
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ],
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'translate' => \Zend\I18n\View\Helper\Translate::class
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
