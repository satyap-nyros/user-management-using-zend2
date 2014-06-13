<?php

namespace Superuser\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
 
/**
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("Student")
 */
class Users implements InputFilterAwareInterface
{
    public $id;
    public $first_name;
    public $last_name;
    public $user_name;
    public $password;
    public $company;
    public $skills;
    public $qulification;
    public $mobile;
    public $profile_pic;
    public $role;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id   = (isset($data['id']))   ? $data['id']   : NULL;
    	$this->first_name   = (isset($data['first_name']))   ? $data['first_name']   : NULL;
    	$this->last_name = (isset($data['last_name'])) ? $data['last_name'] : NULL;
    	$this->user_name  = (isset($data['user_name']))  ? $data['user_name']  : NULL;
    	$this->password = (isset($data['password'])) ? $data['password'] : NULL;
    	$this->company = (isset($data['company'])) ? $data['company'] : NULL;
    	$this->skills = (isset($data['skills'])) ? $data['skills'] : NULL;
    	$this->qulification = (isset($data['qulification'])) ? $data['qulification'] : NULL;
    	$this->mobile = (isset($data['mobile'])) ? $data['mobile'] : NULL;
    	$this->profile_pic = (isset($data['profile_pic'])) ? $data['profile_pic'] : NULL;
    	$this->role = (isset($data['role'])) ? $data['role'] : NULL;    	
    }
	public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
    	throw new \Exception("Not used");
    }
	public function getInputFilter()
    	{
    	if (!$this->inputFilter) {
    		$inputFilter = new InputFilter();
    		$factory     = new InputFactory();
    		$inputFilter->add($factory->createInput(array(
				'name' => 'user_name',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 3,
							'max'      => 30,
						),
					),
				),
    		)));
    		$inputFilter->add($factory->createInput(array(
				'name' => 'password',
				'required' => TRUE,
				'filters' => array(
						array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 3,
							'max'      => 50,
						),
					),
				),
    		)));
    		$this->inputFilter = $inputFilter;
    	}
    	return $this->inputFilter;
    }
}
