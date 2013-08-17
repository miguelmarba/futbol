<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Administrator\Controller\Index'    => 'Administrator\Controller\IndexController',
            'Administrator\Controller\Users'    => 'Administrator\Controller\UsersController',
            'Administrator\Controller\Personal' => 'Administrator\Controller\PersonalController',
            'Administrator\Controller\Roles'    => 'Administrator\Controller\RolesController',
            'Administrator\Controller\Recursos'    => 'Administrator\Controller\RecursosController',
            'Administrator\Controller\Campanas' => 'Administrator\Controller\CampanasController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'administrator' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/administrator',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Administrator\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller][/:action][/:id][/page/:page]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[0-9]+',
                                'page'       => '[0-9]+',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'paginator-slide'         => __DIR__ . '/../view/layout/slidePaginator.phtml',
        ),
        'template_path_stack' => array(
            'Administrator' => __DIR__ . '/../view',
        ),
    ),
);
