<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Form\EdituserForm;
use Admin\Form\NewuserForm;
use Zend\Session\Container;
use Admin\Model\Users;
use Admin\Model\Newuser;
use Admin\Model\UsersTable;
use Zend\Db\Sql;
use Zend\Validator\File\Size;

class AdminController extends AbstractActionController
{
    protected  $usersTable;
    public function indexAction()
    { 
    	$request = $this->getRequest(); 
    	$user_session = new Container('user');
    	if(isset($user_session->user_id))
    	{
    		$all_users= $this->getUsersTable()->fetchAll();  
		return new ViewModel(array(
		   'users' => $all_users,
		));
        }
    }
    public function newuserAction()
    {
    	$form = new NewuserForm();
        $request = $this->getRequest();
        if ($request->isPost()) 
       	{
       	     
       	    if(isset($_POST['cancel'])&&$_POST['cancel'] == 'Cancel')
	    {
			return $this->redirect()->toRoute('admin', array('action' => 'index'));
	    }	    
            $register = new Newuser();
            $form->setInputFilter($register->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) 
            {                 	
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
            		return $this->redirect()->toRoute('admin', array('action' => 'index'));
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
    public function edituserAction()
    {
    	$id =  $this->params()->fromRoute('id');   
    		
    	try 
    	{
            $this_user = $this->getUsersTable()->getUser($id);           
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('admin', array(
                'action' => 'index'
            ));
        }        
    	$request = $this->getRequest();
    	$form = new EdituserForm();
    	$form->bind($this_user);
    	$pic = $this_user->profile_pic;

    	if ($request->isPost()) 
    	{
    		    if(isset($_POST['cancel'])&&$_POST['cancel'] == 'Cancel')
	    {
			return $this->redirect()->toRoute('admin', array('action' => 'index'));
	    }
	    $users = new Users();
            $form->setInputFilter($users->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) 
            {   
            	/********file uploading***********/ 
            	
            	$File = $this->params()->fromFiles('profile_pic');
            	if($File['name']!='')
		{
			$splimgurl=explode(".",$File['name']);
			$profilepic = '/users/'.$id.'.'.$splimgurl[1];
			$splimg=explode(".",$File['name']);
			$mask = getcwd().'/public/users/'.$id.'.'.$splimg[1];
   			array_map( "unlink", glob( $mask ) );		
			$size = new Size(array('min'=>2000));						
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
		            $adapter->addFilter('Rename', getcwd().'/public/users/'.$id.'.'.$splimg[1]);
		          
		            if ($adapter->receive($File['name'])) {		            	
		               // $register->exchangeArray($form->getData());		              
		            }
		        } 
			$form->getData()->profile_pic=$profilepic;
		}
		else if($pic!='')
		{
			$form->getData()->profile_pic=$pic;
		}
		else
		{
			$form->getData()->profile_pic='';
		}           	
		
		  /*************end********************/
		$update_user = $this->getUsersTable()->updateUser($id,$this_user); 
		return $this->redirect()->toRoute('admin', array('action' => 'index'));       
            }
            else{
            	$this->flashMessenger()->addErrorMessage('Please fill valid credentials');
              
            }
    	}
   	    	
    	return new ViewModel(array(
            'form' => $form,
            //'fname'=> $this_user['first_name'],
            'scc'  => $this->flashMessenger()->getCurrentSuccessMessages(),
            'err'  => $this->flashMessenger()->getCurrentErrorMessages(),
        ));   
    }
    public function removeAction()
    {
    	$id =  $this->params()->fromRoute('id');
    	$remove_user = $this->getUsersTable()->removeUser($id);
    	return $this->redirect()->toRoute('admin', array('action' => 'index'));    	
    }
    public function logoutAction()
    {
    	 $user_session = new Container('user');
	    unset($user_session->user_id);    
	    unset($user_session->user_type);
	    return $this->redirect()->toRoute('application');
    }
	
    public function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('Admin\Model\UsersTable');
            
        }
        return $this->usersTable;
    }
    
   
}
