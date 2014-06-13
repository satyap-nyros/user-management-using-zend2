<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Superuser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Superuser\Form\EdituserForm;
//use Admin\Form\NewuserForm;
use Zend\Session\Container;
use Superuser\Model\Users;
//use Admin\Model\Newuser;
use Superuser\Model\UsersTable;
use Zend\Db\Sql;
use Zend\Validator\File\Size;

class SuperuserController extends AbstractActionController
{
    protected  $usersTable;
    public function indexAction()
    {
    	$user_session = new Container('user');
    	if(isset($user_session->user_id))
    	{
    		$this_user = (array)$this->getUsersTable()->getUser($user_session->user_id);   		
        return new ViewModel(array(
           'details' => $this_user,
        ));
        } 
    	
    }
    public function usersAction()
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
    
    public function edituserAction()
    {
    	$user_session = new Container('user');
    	$id =  $user_session->user_id;  
    		
    	try 
    	{ 
            $this_user = $this->getUsersTable()->getUser($id);            
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('superuser', array(
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
			return $this->redirect()->toRoute('superuser', array('action' => 'index'));
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
		return $this->redirect()->toRoute('superuser', array('action' => 'index'));       
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
            $this->usersTable = $sm->get('Superuser\Model\UsersTable');
            
        }
        return $this->usersTable;
    }
    public function bbgetallAction()
    {
    	$users= $this->getUsersTable()->fetchAll();
    	foreach ($users as $user) :
    	$data[] = array('FirstName'=>$user->first_name,
    			'LastName'=>$user->last_name,
    			'Mobile'=>$user->mobile,
    			'Username'=>$user->user_name,
    			'Qualification'=>$user->qulification,
    			'Skills'=>$user->skills,
    			'Company'=>$user->company,
    			'Role'=>$user->role);
    	endforeach;
    	echo json_encode($data);
    	exit;
    }
    
   
}
