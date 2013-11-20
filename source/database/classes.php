<?php
class Control_system
{
	public $username; 
	public $password; 
	public $systemId;
	public $email;
	public $phoneNo;
	function __construct($username, $password, $systemId = null, $email = null, $phoneNo = null) 
		{
			$this->username= $username;
			$this->password= $password;
			$this->systemId= $systemId;
			$this->email= $email;
			$this->phoneNo= $phoneNo;
		}
}
class Profile
{
	public $systemId; 
	public $name; 
	public $profileId;
	public $points;
	
	
	function __construct($systemId, $name, $profileId = null , $points = null) 
	{
		$this->systemId= $systemId;
		$this->name= $name;
		$this->points= $points;
		$this->profileId= $profileId;
	}
}

class Tag
{
	public $systemId; 
	public $name; 
	public $TSerieNo;
	public $active;
	public $profileId;
	 
	function __construct($systemId, $profileId, $TSerieNo = null ,   $name= null, $active = null ) 
	{
		$this->systemId = $systemId;
		$this->name = $name;
		$this->TSerieNo = $TSerieNo;
		$this->active = $active;
		$this->profileId = $profileId;
	}
}
class Controller
{
	public $systemId; 
	public $name; 
	public $CSerieNo;
	public $location;
	public $status;
	 
	function __construct($systemId, $name, $CSerieNo , $location = null, $status = null ) 
	{
		$this->systemId = $systemId;
		$this->name = $name;
		$this->CSerieNo = $CSerieNo;
		$this->location = $location;
		$this->status = $status;
	}
}

class Chores 
{
	public $systemId; 
	public $name; 
	public $CId;
	public $description;
	public $defaultPoints;
	 
	function __construct($systemId, $name, $CId =null , $description = null, $defaultPoints = null ) 
	{
		$this->systemId = $systemId; 
		$this->name = $name; 
		$this->CId = $CId;
		$this->description = $description;
		$this->defaultPoints = $defaultPoints;
	}
}

class Permissions
{//'PerId','name','CSId','profileId'
	public $systemId; 
	public $name; 
	public $PerId;
	public $profileId; 

	 
	function __construct($systemId, $name, $PerId =null , $profileId = null) 
	{
		$this->systemId = $systemId; 
		$this->name = $name; 
		$this->PerId = $PerId;
		$this->profileId = $profileId; 
	}
}
class Rules
{//'RId', 'name', 'profileId', 'CSId'
	public $systemId; 
	public $name; 
	public $RId;
	public $profileId;

	 
	function __construct($systemId, $name, $RId = null , $profileId = null ) 
	{
		$this->systemId = $systemId; 
		$this->name = $name; 
		$this->RId = $RId;
		$this->profileId = $profileId;
	}
}


?>