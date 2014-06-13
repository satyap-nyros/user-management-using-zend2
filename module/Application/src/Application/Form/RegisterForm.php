<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class RegisterForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('RegisterForm');
        $this->setAttribute('method', 'post');
         $this->setAttribute('class', 'form-horizontal');

        $this->add(new Element('security'));
      /*  $this->add(array(
            'name' => 'id',
            'type' => 'text',
        ));  */

	$this->add(array(
            'name' => 'first_name',
            'type' => 'text',
            'options' => array(
                'label' => 'First Name',
            ),
        ));

	$this->add(array(
            'name' => 'last_name',
            'type' => 'text',
            'options' => array(
                'label' => 'Last Name',
            ),
           
        ));


        $this->add(array(
            'name' => 'user_name',
            'type' => 'text',
            'options' => array(
                'label' => 'Username',
            ),
        ));

	$this->add(array(
            'name' => 'password',
            'type' => 'text',       
            'options' => array(
                'label' => 'Password',
            ),
        ));

        $this->add(array(
            'name' => 'company',
            'type' => 'text',       
            'options' => array(
                'label' => 'Company',
            ),
        ));

        $this->add(array(
            'name' => 'skills',            
            'attributes' => array(
				'type' => 'textarea',
				'placeholder' => 'Skills',
				
    		),
            'options' => array(
                'label' => 'Skills',              
            ),
        ));

	$this->add(array(
    		'name' => 'qulification',
    		'type' => 'text',
    		'options' => array(
				'label' => 'Qulification:',
    		),
        ));
        $this->add(array(
    		'name' => 'mobile',
    		'type' => 'text',
    		'options' => array(
				'label' => 'Mobile:',
    		),
        ));
        $this->add(array(
    		'name' => 'profile_pic',
    		'type' => 'file',
    		'options' => array(
				'label' => 'Profile Picture:',
    		),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',

            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
	        'class' => 'btn btn-success',
            ),
        ));

        $this->add(array(
            'name' => 'cancel',
            //'type' => 'Submit',
            'attributes' => array(
                'value' => 'Cancel',
                'id' => 'submitbutton',
		'class' => 'btn btn-primary',
            ),
        ));

    }
}


