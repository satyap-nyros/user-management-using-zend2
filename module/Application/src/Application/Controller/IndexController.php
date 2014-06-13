<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\LoginForm;
use Application\Form\RegisterForm;
use Zend\Session\Container;
use Application\Model\Login;
use Application\Model\Register;
use Application\Model\UsersTable;
use Zend\Db\Sql;
use Zend\Validator\File\Size;

class IndexController extends AbstractActionController
{
    protected  $usersTable;
    public function indexAction()
    {   	
    	$form = new LoginForm();
        $request = $this->getRequest();      
       	if ($request->isPost()) 
       	{
            $login = new Login();
            $form->setInputFilter($login->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) 
            {
                $login->exchangeArray($form->getData());
                $arrData = $form->getData();
                $username = $arrData['user_name'];
                $password = $arrData['password'];                
              	$existed_row = $this->getUsersTable()->getUserData($username,$password);
              	
              	try
              	{
              		if(isset($existed_row['id']))
              		{
              			$user_session = new Container('user');
				$user_session->user_id = $existed_row['id'];
				
				if($existed_row['role']==1)
				{
					$user_session->user_type = $existed_row['role'];
					return $this->redirect()->toRoute('admin');
				}
				else if($existed_row['role']==2)
				{
					$user_session->user_type = $existed_row['role'];
					return $this->redirect()->toRoute('superuser');
				}
				else
				{
					$user_session->user_type = $existed_row['role'];
					return $this->redirect()->toRoute('users');
				}
              			
              		}
              		else
              		{
              			$this->flashMessenger()->addErrorMessage('Invalid Username/Password!');
                        	return $this->redirect()->toRoute('home', array('action' => 'index'));
              		}
              	}
              	catch(Exception $e)
              	{
              	
              	}          
            }
	    else {
              // $this->flashMessenger()->addErrorMessage('Please fill valid credentials');
              // return $this->redirect()->toRoute('home', array('action' => 'index'));
            }
          }
           return new ViewModel(array(
            'form' => $form,
            'scc'  => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err'  => $this->flashMessenger()->getCurrentErrorMessages(),
        )); 
    }
    public function registerAction()
    {
    	$form = new RegisterForm();
        $request = $this->getRequest();
        if ($request->isPost()) 
       	{
       	     
       	    if(isset($_POST['cancel'])&&$_POST['cancel'] == 'Cancel')
	    {
			return $this->redirect()->toRoute('Start', array('action' => 'index'));
	    }
	    
            $register = new Register();
            $form->setInputFilter($register->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) 
            {	
            	$register = new Register();            	
            	$register->exchangeArray($form->getData());
            	$arrData = $form->getData();            	
            	if(isset($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['name'] != "") 
		{					
			$arrData['profile_pic'] ='/users/'.$_FILES['profile_pic']['name'];
		}
		$insert_row = $this->getUsersTable()->insertRow($arrData);            	
            	if($insert_row=='error')
            	{
            		$this->flashMessenger()->addErrorMessage('Username already Exists');
            		//$this->url('Application', array('action'=>'register'));
            	}
            	else
            	{            	
            		/********file uploading***********/
			$File = $this->params()->fromFiles('profile_pic');
			$size = new Size(array('min'=>2000));
			$splimg=explode(".",$File['name']);					
			$adapter = new \Zend\File\Transfer\Adapter\Http();		
		        $adapter->setValidators(array($size), $File['name']);
		        if (!$adapter->isValid()){
		            $dataError = $adapter->getMessages();
		            $error = array();
		            foreach($dataError as $key=>$row)
		            {
		                $error[] = $row;
		            }
		            $form->setMessages(array('profile_pic'=>$error ));
		        } else {
		        
		            $adapter->setDestination(getcwd().'/public/users/');
		            $adapter->addFilter('Rename', getcwd().'/public/users/'.$insert_row.'.'.$splimg[1]);
		          
		            if ($adapter->receive($File['name'])) {		            	
		                $register->exchangeArray($form->getData());		              
		            }
		        }  
		        /*************end********************/				
            		return $this->redirect()->toRoute('Start', array('action' => 'index'));
            	}
			
            	
            	        
            }
            else
            {
            	//$this->flashMessenger()->addErrorMessage('Invalid Details!');
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'scc'  => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err'  => $this->flashMessenger()->getCurrentErrorMessages(),
        ));     
    }
    

    public function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('Application\Model\UsersTable');
            
        }
        return $this->usersTable;
    }   
   
}
