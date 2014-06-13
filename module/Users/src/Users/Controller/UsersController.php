<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Users\Model\Users;
use Users\Model\UsersTable;
use Users\Form\EditForm;
use Zend\Session\Container;
use Zend\Validator\File\Size;
/*use Users\Model\Users;
use Users\Model\UsersTable;
use Users\Model\Agent;
use Users\Model\AgentTable;
use Users\Model\GetAgentTable;
use Users\Model\Lead;
use Users\Model\LeadTable;

use Users\Form\AgentForm;
use Users\Form\LoginForm;
use Users\Form\LoginAgentForm;
use Users\Form\LoginSuperUsersForm;
use Users\Form\LeadForm;
use Users\Model\Login;
use Users\Form\emailForm;
use Users\Form\AgentRegisterForm;
use Users\Form\RegisterForm;
use Users\Model\Signup;
use Users\Model\SignupTable;
use Users\Model\Signin;
use Users\Model\SigninTable;
use Users\Model\Mails;
use Users\Model\MailTable;

use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Db\Sql;
 */
class UsersController extends AbstractActionController
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
    public function editAction()
    {
    	$user_session = new Container('user');
    	$id = (int) $this->params()->fromRoute('id', $user_session->user_id);
    	try 
    	{
            $this_user = $this->getUsersTable()->getUser($id);           
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('Users', array(
                'action' => 'index'
            ));
        }        
    	$request = $this->getRequest();
    	$form = new EditForm();
    	$form->bind($this_user);
    	$pic = $this_user->profile_pic;
    	if ($request->isPost()) 
    	{
    	    if(isset($_POST['cancel'])&&$_POST['cancel'] == 'Cancel')
	    {
			return $this->redirect()->toRoute('Users', array('action' => 'index'));
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
		return $this->redirect()->toRoute('users', array('action' => 'index'));       
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
   
    public function inlineeditAction()
    {
	    if(isset($_POST['rownum']))
	    {  
	    	$update_user = $this->getUsersTable()->updateLine($_POST['rownum'],$_POST['value'],$_POST['field']);
	    	
	    }	   
	    exit;
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
            $this->usersTable = $sm->get('Users\Model\UsersTable');
            
        }
        return $this->usersTable;
    }   


}
