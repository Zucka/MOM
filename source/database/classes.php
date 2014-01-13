<?php
class Control_system
{
	public $name;
	public $CSId;
	public $street;
	public $phoneNo;
	public $postcode;
	function __construct($CSId = null,$name= null , $street = null,$postcode = null , $phoneNo = null) 
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

	
	function __construct($CSId, $profileId = null, $name =null , $username = null, $password= null, $email=null, $points = null,  $role= null, $phoneNo = null) 
	{
		$this->CSId = $CSId;
		$this->name = $name;
		$this->points = $points;
		$this->profileId = $profileId;
		$this->username = $username;
		if($password === null)
			$this->password = null;
		else
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
						//active must be '0' or 'false' when expressing the boolean false because false or 0 just disappears 
	function __construct($CSId, $TSerieNo ,$profileId = null,  $name= null, $active = null ) 
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
	 
	function __construct($CSId, $CSerieNo, $name= null , $location = null, $cost=null , $status = null ) 
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
	 
	function __construct($CSId, $CId =null ,$name = null, $description = null, $defaultPoints = null ) 
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

	 
	function __construct($CSId, $name=null, $isPermission= null, $RId = null ) 
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

	function __construct($RId ,$name=null, $condId=null, $controllerId = null, $arrayOfRestAttributes= null ) 
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


	function __construct( $RId, $name=null, $AId=null, $controllerId = null, $points= null ) 
	{
		$this->RId = $RId; 
		$this->name = $name; 
		$this->AId = $AId;
		$this->controllerId = $controllerId;
		$this->points = $points;
	}
}



?>