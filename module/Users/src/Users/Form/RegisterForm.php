<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class RegisterForm extends Form
{
    public function __construct ($name = NULL)
    {

        parent::__construct('RegisterForm');

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');

        $this->add(new Element\Csrf('security'));
       
        $this->add(array(
    		'name' => 'username',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'Your username',
    		),
    		'options' => array(
				'label' => 'Username:',
    		),
        ));
        $this->add(array(
    		'name' => 'password',
    		'attributes' => array(
				'type' => 'password',
				'placeholder' => 'Your password',
    		),
    		'options' => array(
				'label' => 'Password:',
    		),
        ));

	 $this->add(array(
    		'name' => 'repassword',
    		'attributes' => array(
				'type' => 'password',
				'placeholder' => 'Repeat your password',
    		),
    		'options' => array(
				'label' => 'Repeat Password:',
    		),
        ));

	$this->add(array(
    		'name' => 'fname',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'First Name',
    		),
    		'options' => array(
				'label' => 'First Name:',
    		),
        ));

	$this->add(array(
    		'name' => 'lname',
    		'attributes' => array(
				'type' => 'text',
				'placeholder' => 'Last Name',
    		),
    		'options' => array(
				'label' => 'Last Name:',
    		),
        ));


	
	$this->add(array(
    		'name' => 'address',
    		'attributes' => array(
				'type' => 'textarea',
				'placeholder' => 'Address',
				'style'=>'margin-left:100px;',
    		),
    		'options' => array(
				'label' => 'Address:',
    		),
        ));

        
	$this->add(array(
    		'name' => 'email',
    		'attributes' => array(
				'type' => 'email',
				'placeholder' => 'Your email',
    		),
    		'options' => array(
				'label' => 'Email:',
    		),
        ));

        
	

        $this->add(array(
    		'name' => 'submit',
    		'attributes' => array(
				'type' => 'submit',
				'value' => 'Submit',
				'class' => 'btn btn-success',
    		),
    		'options' => array(
				'label' => 'Save'
    		),
        ));
        $this->add(array(
    		'name' => 'cancel',
    		'attributes' => array(
				'type' => 'submit',
				'value' => 'Cancel',
				'class' => 'btn btn-primary',
    		),
    		'options' => array(
				'label' => 'Cancel'
    		),
        ));
    }
}
