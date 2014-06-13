<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Superuser\Controller\Superuser' => 'Superuser\Controller\SuperuserController',            
        ),
    ),
	
	'view_manager' => array(
         'template_map' => array(
           'superuser/layout'    => __DIR__ . '/../../Superuser/view/layout/layout.phtml',
	),
        'template_path_stack' => array(
            'Superuser' => __DIR__ . '/../view',
        ),
    ), 
 
);
