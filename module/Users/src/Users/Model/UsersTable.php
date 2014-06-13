<?php

namespace Users\Model;

use Zend\Db\TableGateway\TableGateway;

class UsersTable
{
    protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
	    $this->tableGateway = $tableGateway;
	}

	public function getUserData($username, $password = NULL)
	{
	    if (empty($password)) {
	        $rowset = $this->tableGateway->select(array(
        		'username' => $username,
	        ));
	    } else {
	        $rowset = $this->tableGateway->select(array(
        		'username' => $username,
	                'password' => $password,
	        ));
	    }
	    $row = $rowset->current();
	    return ($row) ? $row : FALSE;
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
			);		
                    $this->tableGateway->update($data, array('id' => $id));
                }
                catch(\Exception $ex)
                {
                	
                }
	}	
	public function updateLine($id,$val,$field)
	{
		try
		{
		   $data = array(
		    $field => $val		   
		 );		
                    $this->tableGateway->update($data, array('id' => $id));
                }
                catch(\Exception $ex)
                {
                	
                }
	}	
	
}
