<?php
class Control_system
{
	public $name;
	public $CSId;
	public $street;
	public $phoneNo;
	public $postcode;
	function __construct($name ,$CSId = null, $street = null,$postcode = null , $phoneNo = null) 
		{
			$this->name = $name;
			$this->CSId= $CSId;
			$this->street= $street;
			$this->postcode= $postcode;
			$this->phoneNo= $phoneNo;
		}
}
class Profile
{
	public $CSId; 
	public $name; 
	public $profileId;
	public $points;
	public $username;
	public $password;
	public $email;
	public $phoneNo;
	public $role; //(user OR manager)

	
	function __construct($CSId, $name , $username, $password, $email, $points = null, $profileId = null, $role= null, $phoneNo = null) 
	{
		$this->CSId = $CSId;
		$this->name = $name;
		$this->points = $points;
		$this->profileId = $profileId;
		$this->username = $username;
		$this->password = hashPassword($password);
		$this->email = $email;
		$this->phoneNo = $phoneNo;
		$this->role = $role;
	}
}

class Tag
{
	public $CSId; 
	public $name; 
	public $TSerieNo;
	public $active;
	public $profileId;
	 
	function __construct($CSId, $profileId, $TSerieNo = null ,   $name= null, $active = 0 ) 
	{
		$this->CSId = $CSId;
		$this->name = $name;
		$this->TSerieNo = $TSerieNo;
		$this->active = $active;
		$this->profileId = $profileId;
	}
}
class Controller
{
	public $CSId; 
	public $name; 
	public $CSerieNo;
	public $location;
	public $status;
	public $cost;
	 
	function __construct($CSId, $name, $CSerieNo , $location = null, $cost=null , $status = null ) 
	{
		$this->CSId = $CSId;
		$this->name = $name;
		$this->CSerieNo = $CSerieNo;
		$this->location = $location;
		$this->status = $status;
		$this->cost = $cost;
	}
}

class Chores 
{
	public $CSId; 
	public $name; 
	public $CId;
	public $description;
	public $defaultPoints;
	 
	function __construct($CSId, $name, $CId =null , $description = null, $defaultPoints = null ) 
	{
		$this->CSId = $CSId; 
		$this->name = $name; 
		$this->CId = $CId;
		$this->description = $description;
		$this->defaultPoints = $defaultPoints;
	}
}

class Rules
{//'RId', 'name', 'profileId', 'CSId'
	public $CSId; 
	public $name; 
	public $RId;
	public $isPermission;

	 
	function __construct($CSId, $name, $isPermission= false, $RId = null ) 
	{
		$this->CSId = $CSId; 
		$this->name = $name; 
		$this->RId = $RId;
		$this->isPermission = $isPermission;
	}
}
class Condition
{//'condId','RId','name','controllerId'
	public $RId;
	public $name; 
	public $condId;
	public $controllerId;
	public $arrayOfRestAttributes;

	function __construct($RId ,$name, $condId=null, $controllerId = null, $arrayOfRestAttributes= null ) 
	{
		$this->RId = $RId; 
		$this->name = $name; 
		$this->condId = $condId;
		$this->controllerId = $controllerId;
		$this->arrayOfRestAttributes = $arrayOfRestAttributes;
	}
}

class Action
{//'AId', 'RId','name', 'points','controllerId'),

	public $RId;
	public $name; 
	public $AId;
	public $controllerId;
	public $points;


	function __construct( $RId, $name, $AId=null, $controllerId = null, $points= null ) 
	{
		$this->RId = $RId; 
		$this->name = $name; 
		$this->AId = $AId;
		$this->controllerId = $controllerId;
		$this->points = $points;
	}
}



?>