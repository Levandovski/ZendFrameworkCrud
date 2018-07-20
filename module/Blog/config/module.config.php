<?php

namespace Blog;

use Zend\ServiceManager\Factory\InvokableFactory;

return[
    'controllers' => [
        'factories' => [
        #Controller\BlogController::class => InvokableFactory::class
        ]
    ],
    'router' => [
        'routes' => [
            'post' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/blog[/:action[/:id]]', //estou dizendo que o blog/ alguma coisa pode ser utilizado de forma opcional
                     'constraints' =>[//constraints são regras para nossa action, por exemplo, o que eu posso inserir na minha url.
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id' => '[0-9]+',
                     ],
                    'defaults' => [
                        'controller' => Controller\BlogController::class,
                        'action' => 'index'
                    ]
                ]
            ],
             'post_json' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/blog[/:action[/:id]][:.json]', //estou dizendo que o blog/ alguma coisa pode ser utilizado de forma opcional
                     'constraints' =>[//constraints são regras para nossa action, por exemplo, o que eu posso inserir na minha url.
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id' => '[0-9]+',
                     ],
                    'defaults' => [
                        'controller' => Controller\BlogController::class,
                        'action' => 'index'
                    ]
                ]
            ]
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            'blog' => __DIR__ . "/../view"
            
        
    ],
    
    'strategies' => [
        'ViewJsonStrategy'
    ]
    
    ]
    
];

