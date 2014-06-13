<?php 
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class LoginForm extends Form
{
    public function __construct ($name = NULL)
    {
        parent::__construct('LoginForm');
        
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(new Element\Csrf('security'));

        $this->add(array(
    		'name' => 'user_name',
    		'attributes' => array(
				'type' => 'text',
				'class' => 'largeinput',
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
				'class' => 'largeinput',
				'placeholder' => 'Your password',
    		),
    		'options' => array(
				'label' => 'Password:',
    		),
        ));
      
        $this->add(array(
    		'name' => 'signin',
    		'attributes' => array(
				'type' => 'submit',
				'value' => 'Sign in',
				'class' => 'btn btn-success',
    		),
    		'options' => array(
				'label' => 'Sign in'
    		),
        ));
    }
}
