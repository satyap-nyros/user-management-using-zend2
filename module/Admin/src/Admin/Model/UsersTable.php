<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
//use Zend\Db\Adapter\Driver\DriverInterface;
//use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Sql;

use Zend\Db\Adapter\Platform\PlatformInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;

class UsersTable 
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    public function getUser($id)
	{
		 $id  = (int) $id;

		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
		    throw new \Exception("Could not find row $id");
		}
		
		return $row;
	}    

    /*public function fetchMails($currmail,$id)
    {
	$where = new Where();    
	//$where->like('optionalcontacts',"$currmail%");
        $where->like('receipentids',"$id%");
        $where->or;
        $where->like('optionalcontacts',"$currmail%");
	$resultset = $this->tableGateway->select($where);
	
//	print_r($resultset);exit;

	return $resultset;	

    }

    public function saveMail(Mails $mail)
    {
        $data = array(
            'content' => $mail->content,
            'subject' => $mail->subject,
            'date_sent' => $mail->date_sent,
            'receipentids' => $mail->receipentids,
            'optionalcontacts'=> $mail->optionalcontacts,
            'admin_id' => $mail->admin_id,
            'agent_id' => $mail->agent_id,
        );

        $this->tableGateway->insert($data);        
    }*/
    public function getUserData($uname,$pwd)
    {
	  if (empty($pwd)) {
	        $rowset = $this->tableGateway->select(array(
        		'user_name' => $uname,
	        ));
	    } else {
	    	$where = new Where();    
		$where->equalTo('user_name',$uname);		
		$where->equalTo('password',$pwd);
	        $rowset = $this->tableGateway->select($where);
	    }
	    $row = $rowset->current();
	    
	    $row = (array) $row;
	  
	  	return ($row) ? $row : FALSE;	
    }
    public function insertRow($data)
    {
    	if($data['profile_pic']=='')
    	{
    		$data['profile_pic']='';
    	}    	
    	$new_data =array(
    		'user_name' 	=> $data['user_name'],
    		'password'	=> $data['password'],
    		'first_name' 	=> $data['first_name'],
    		'last_name' 	=> $data['last_name'],
    		'skills' 	=> $data['skills'],
    		'qulification' 	=> $data['qulification'],
    		'mobile' 	=> $data['mobile'],
    		'company' 	=> $data['company'],
    		'profile_pic' 	=> $data['profile_pic'],
    		'role'		=> $data['role']
    	); 
	//if($resultset = $this->tableGateway->select(array('user_name',$data['user_name']));)
	$row = $this->tableGateway->select(array('user_name'=>$data['user_name']));
	$rowset = $row->current();
	if(empty($rowset))
	{
		$this->tableGateway->insert($new_data);
		$insert_id = $this->tableGateway->lastInsertValue;
		$imgext = explode('.',$data['profile_pic']);
		$up_data =array(    		
    		'profile_pic' 	=> '/users/'.$insert_id.'.'.$imgext[1]    		    		
    		); 
    		$this->tableGateway->update($up_data, array('id' => $insert_id));
		return 	$insert_id;
	}
	else{
		return 'error';
	} 
    }
    public function updateUser($id,$obj)
    {
		try
		{
		   $data = array(
		    'first_name' => $obj->first_name,
		    'last_name' => $obj->last_name,
		    'user_name' => $obj->user_name,
		    'password' => $obj->password,
		    'company' => $obj->company,
		    'skills'  => $obj->skills,
		    'qulification'  => $obj->qulification,
                    'mobile' => $obj->mobile,
                    'profile_pic' => $obj->profile_pic,
                    'role'=>$obj->role
			);		
                    $this->tableGateway->update($data, array('id' => $id));
                }
                catch(\Exception $ex)
                {
                	
                }
     }
     public function removeUser($id)
     {
     	$this->tableGateway->delete(array('id' => $id));	
     }
		
}


