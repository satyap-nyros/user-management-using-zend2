<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Users\Controller\Users' => 'Users\Controller\UsersController',            
        ),
    ),

	'view_manager' => array(
         'template_map' => array(
           'users/layout'    => __DIR__ . '/../../Users/view/layout/layout.phtml',
	),
        'template_path_stack' => array(
            'Users' => __DIR__ . '/../view',
        ),
    ), 
 
);
