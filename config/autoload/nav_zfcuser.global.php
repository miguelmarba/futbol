<?php

/*
 * Mapa de sitio del sistema.
 */

return array(
    // All navigation-related configuration is collected in the 'navigation' key
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Inicio',
                'route' => 'home',
            ),
            array(
                'label' => 'Reportes',
                'route' => 'reportes',
            ),
            array(
                'label' => 'Administrator',
                'route' => 'administrator',
                'pages' => array(
                    array(
                        'label' => 'Add',
                        'route' => 'album',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit',
                        'route' => 'album',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'Delete',
                        'route' => 'album',
                        'action' => 'delete',
                    ),
                ),
            ),
        ),
    ),
);