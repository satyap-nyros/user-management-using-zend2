<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Admin' => 'Admin\Controller\AdminController',            
        ),
    ),
	 'router' => array(
        'routes' => array(

            'admin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin[/:action][/:id]',
                    'constraints' => array(
                        //'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        //'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Admin',
                        'action'     => 'index',
                    ),
                ),
            ),
            
        ),
    ),
	'view_manager' => array(
         'template_map' => array(
           'admin/layout'    => __DIR__ . '/../../Admin/view/layout/layout.phtml',
	),
        'template_path_stack' => array(
            'Admin' => __DIR__ . '/../view',
        ),
    ), 
 
);
