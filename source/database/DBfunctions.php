<?php
	include_once "configDB.php";
	include_once "classes.php";
	include_once "sqlHelper.php";
	include_once "errorMessageSQL.php";
	
	/*adding into database in the tables Control_system,Profile, Tag, Controller and Chores*/
	function simpleInsertIntoDB($object)
	{
		$db= new MySQLHelper();
		global $theColumns;
		global $theTables;
		$table;
		$colums;
		$values;
		
		switch (get_class($object))
		{
		case 'Control_system':  
		
		//db => 'CSId', 'name' , 'street', 'postcode', 'phoneNo'
		//class => $CSId = null, $street = null,$postcode = null , $phoneNo = null
			$table=  $theTables['Control_system'];
			$columstemp= $theColumns['Control_system'];
			$colums="("  .  $columstemp[1];
			$values= "( '"  . $object->name . "' " ;
			if($object->street != null)
			{
				$colums .= ", " .  $columstemp[2] ;
				$values .= ", '" .   $object->street . "'";
			}
			if($object->postcode != null)
			{
				$colums .= ", " .  $columstemp[3] ;
				$values .= ", '" .   $object->postcode . "'";
			}
			if($object->phoneNo != null)
			{
				$colums .= ", " .  $columstemp[4] ;
				$values .= ", '" .   $object->phoneNo . "'";
			}
			$colums.=")";
			$values.=")";
			break;
		case 'Profile':
		/*
		  `PId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,


  `role` enum('user','manager') NOT NULL DEFAULT 'user',*/
		
		//db =>'PId', 'CSId', 'name', 'points', 'username', 'password', 'email','phone', 'role'
		//class=> $CSId, $name , $username, $password, $email, $points = null, $profileId = null, $role= null, $phoneNo = null
			$table=  $theTables['Profile'];
			$columstemp= $theColumns['Profile'];
			$colums="(" . $columstemp[1] . ", " . $columstemp[2] ;
			$values= "( " . $object->CSId . " , '" .  $object->name ."'";
			
			if($object->points != null)
			{
				$colums .= ", " .  $columstemp[3] ;
				$values .= ", " .   $object->points;
			}
			
			$colums.= ", " . $columstemp[4];
			$values.= ", '" . $object->username . "'";
			$colums.=", " . $columstemp[5];
			$values.= ", '" .  $object->password . "'";
			$colums.= ", " . $columstemp[6];
			$values.=", '" .  $object->email . "'";
	
			if($object->phoneNo != null)
			{
				$colums .= ", " .  $columstemp[7] ;
				$values .= ", '" .   $object->phoneNo . "'";
			}
			if($object->role != null)
			{
				$colums .= ", " .  $columstemp[8] ;
				$values .= ", '" .   $object->role . "'";
			}
			$colums.=")";
			$values.=")";
			
			break;
		case 'Tag':
			$table=  $theTables['Tag'];
			$columstemp= $theColumns['Tag'];
			$colums="(" . $columstemp[0] .", ". $columstemp[1] . ", " . $columstemp[2] ;
			$values= "( " . $object->TSerieNo . " ,". $object->CSId . " ," .  $object->profileId ;
			if($object->name != null)
			{
				$colums .= ", " .  $columstemp[3] ;
				$values .= ", '" .   $object->name . "'";
			}
			if($object->active != null)
			{
				$colums .= ", " .  $columstemp[4] ;
				$values .= ", " .   $object->active . "";
			}
			$colums.=")";
			$values.=")";
			break;
		case 'Controller':
			$table=  $theTables['Controller'];
			$columstemp= $theColumns['Controller'];
			$colums="(". $columstemp[0] .", ". $columstemp[1] .", " . $columstemp[2];
			$values= "( " . $object->CSerieNo . " , " . $object->CSId . " , '".  $object->name . "'";
			if($object->location != null)
			{
				$colums .= "," . $columstemp[3];
				$values .= ", '". $object->location . "'";
			}
			$colums .= ")";
			$values .= ")";
			
			break;
			
		case 'Chores': 
			$table=  $theTables['Chores'];
			$columstemp= $theColumns['Chores'];
			$colums="(". $columstemp[1] . ", ". $columstemp[2] ;
			$values= "( ".$object->CSId . " , '". $object->name . "'";
			if($object->description != null)
			{
				$colums .= ", ". $columstemp[3] ; 
				$values .= ", '" . $object->description . "'" ;
			}
			if($object->defaultPoints != null)
			{
				$colums .= ", ". $columstemp[4] ; 
				$values .= ", ". $object->defaultPoints ;
			}
			$colums .= ")" ; 
			$values .= ")";
		break;
		default:return null;
		}
		$resultValue = $db->insertInto($table, $values, $colums);
		if(is_bool($resultValue))
		{ 
			return $resultValue;
		}
		elseif(is_array($resultValue))
		{
			$errorMessage = null;
			switch($resultValue[1])
			{
			case 1062:
				$errorMessage = $GLOBALS['SQL_ERROR_VALUE_ERROR'];
				break;
			default:
				$errorMessage = $GLOBALS['SQL_ERROR_OTHER'];
			}
			return $errorMessage;
		
			
		}
		return null;
	}

	/*edit data in database in the tables Control_system,Profile, Tag, Controller and Chores*/
	function simpleUpdateDB($object)
	{
	$db= new MySQLHelper();
		global $theColumns;
		global $theTables;
		$table;
		$columnValue;
		$where;

		switch (get_class($object))
		{ 		//db => 'CSId', 'name' , 'street', 'postcode', 'phoneNo'
				//class => $name,$CSId = null, $street = null,$postcode = null , $phoneNo = null
		case 'Control_system': //'name' , 'street', 'postcode', 'phoneNo'
			$table=  $theTables['Control_system'];
			$columstemp= $theColumns['Control_system'];
			$columnValue = $columstemp[1] . " ='" .  $object->name . "'" ;
			if($object->street != null)
			{
				$columnValue .=  ", " . $columstemp[2] . " = '" . $object->street . "'";
			}
			if($object->postcode != null)
			{
				$columnValue .=  ", " . $columstemp[3] . " = '" . $object->postcode . "'";
			}
			if($object->phoneNo != null)
			{
				$columnValue .=  ", " . $columstemp[4] . " = '" . $object->phoneNo. "'";
			}
			
			$where = $columstemp[0] . " = " . $object->CSId;
			//$(username,) $password,( $CSId = null)
			//('CSId', 'username', )'password'
			break;
		case 'Profile':
			$table=  $theTables['Profile'];
			$columstemp= $theColumns['Profile'];
			$columnValue = $columstemp[2] . " = '" . $object->name . "' , " . $columstemp[3] . " = " . $object->points;
			
			if($object->points != null)
			{
				$columnValue .=  ", " . $columstemp[3] . " = " .$object->points;
			}
			$columnValue .=  ", " . $columstemp[4] . " = '" . $object->username . "', " . $columstemp[5] . " = '" . $object->password . 
							"', " . $columstemp[6] . " = '" . $object->email . "'";
	
			if($object->phoneNo != null)
			{
				$columnValue .=  ", " . $columstemp[7] . " = '" .$object->phoneNo . "'";
			}
			if($object->role != null)
			{
				$columnValue .=  ", " . $columstemp[8] . " = '" .$object->role . "'";
			}
			
			$where = $columstemp[0] . " = " . $object->profileId;
			break;
		case 'Tag':
			$table=  $theTables['Tag'];
			$columstemp= $theColumns['Tag'];
			$where = $columstemp[0] . " = " . $object->TSerieNo;
			$columnValue = $columstemp[2] . " = " . $object->profileId ;
			if($object->name != null)
			{
				$columnValue .=  ", " . $columstemp[3] . " = '" . $object->name . "'";
			}
			$columnValue .= ", " .$columstemp[4] . " = " . $object->active;
			//('TSerieNo','CSId',) 'profileId', 'name', 'active'
			//$(CSId,) $profileId, ($TSerieNo = null ),   $name= null, $active = null 
			break;
		case 'Controller':
			$table=  $theTables['Controller'];
			$columstemp= $theColumns['Controller'];
			$where = $columstemp[0] . " = " . $object->CSerieNo;
			$columnValue = $columstemp[2] . " = '" . $object->name . "', " .$columstemp[4] . " = '" . $object->status . "'";
			if($object->location != null)
			{
			$columnValue .=  ", " .$columstemp[3] . " = '" . $object->location . "'";
			}
			//('CSerieNo','CSId',) 'name' ,'location', 'status' 
			//($CSId,) $name, ($CSerieNo) , $location = null, $status = null
			break;
			
		case 'Chores': 
			$table=  $theTables['Chores'];
			$columstemp= $theColumns['Chores'];
			$where = $columstemp[0] . " = " . $object->CId;
			$columnValue = $columstemp[2] . " = '" . $object->name . "', " .$columstemp[4] . " = " . $object->defaultPoints;
			if($object->description != null)
			{
			$columnValue .=  ", " .$columstemp[3] . " = '" . $object->description . "'";
			}
			//('CId', 'CSId'), 'name', 'description', 'defaultPoints'
			//($CSId,) $name, ($CId =null) , $description = null, $defaultPoints = null 
			break;
		default:
			return;
		}
		$resultValue = $db->update( $table, $columnValue, $where);
		if(is_bool($resultValue))
		{ 
			return $resultValue;
		}
		elseif(is_array($resultValue))
		{
			$errorMessage = null;
			switch($resultValue[1])
			{
			case 1054:
				$errorMessage = $GLOBALS['SQL_ERROR_BAD_INPUT'];
				break;
			case 1062:
				$errorMessage = $GLOBALS['SQL_ERROR_VALUE_ERROR'];
				break;
			case 1064:
				$errorMessage = $GLOBALS['SQL_ERROR_WEIRD_FALSE'];
				break;
			default:
				$errorMessage = $GLOBALS['SQL_ERROR_OTHER'];
			}
			return $errorMessage;
		}
		return null;
	}
	
	/*delete in database Profile, Tag, Controller and Chores*/
	function removeSimpleObjectFromDB($object)
	{
		$db= new MySQLHelper();
		global $theColumns;
		global $theTables;
		$table;
		$where=null;
		
		switch (get_class($object))
		{
	/*don't use this one yet
		case 'Control_system':
			$table=  $theTables['Control_system'];
			$columntemp = $theColumns['Control_system'];
			$where = $columntemp[0] . " = " . $object->CSId;
			break;*/
		case 'Profile':
			$table=  $theTables['Profile'];
			$columntemp = $theColumns['Profile'];
			$where = $columntemp[0] . " = " . $object->profileId;
			break;
		case 'Tag':
			$table=  $theTables['Tag'];
			$columntemp = $theColumns['Tag'];
			$where= $columntemp[0] . " = " . $object->TSerieNo;
			break;
		case 'Controller':
			$table=  $theTables['Controller'];
			$columntemp = $theColumns['Controller'];
			$where = $columntemp[0] . " = " . $object->CSerieNo;
			break;	
		case 'Chores': 
			$table=  $theTables['Chores'];
			$columntemp = $theColumns['Chores'];
			$where = $columntemp[0] . " = " . $object->CId;
			break;
		default:
		return;
		}
		$resultValue = $db->delete($table, $where);
		if(is_bool($resultValue))
		{ 
			return $resultValue;
		}
		elseif(is_array($resultValue))
		{
			$errorMessage = null;
			switch($resultValue[1])
			{
			case 1451:
				$errorMessage = $GLOBALS['SQL_ERROR_DELETE_FAILED'];
				break;
			default:
				$errorMessage = $GLOBALS['SQL_ERROR_OTHER'];
			}
			return $errorMessage;
		}
		return null;
	}
	
	/* validate a username and password. Return false if it does not match a login and it returns the CSId if it does find a result.*/
	function validateLogin($username, $password)
	{
		$db= new MySQLHelper();
		//'Profile' =>array('PId', 'CSId', 'name', 'points', 'username', 'password', 'email','phone', 'role'),
		global $theTables;
		global $theColumns;
		$columntemp = $theColumns['Profile'];
		$table = $theTables['Profile'];
		$username = $db->real_escape_string($username);
		$password = $db->real_escape_string($password);
		$selectClause = $columntemp[0] .",". $columntemp[5];
		$whereClause =  $columntemp[4] . " = '" . $username ."'";
		// Get prfile id and password from DB
		$result = $db->query($selectClause, $table, $whereClause );
		if($row = $result->fetch_assoc())
		{
			// Hashing the password with its hash as the salt returns the same hash
			if ( crypt($password, $row['password']) == $row['password'] ) {
				// Ok!
				$selectClause = "Pid, CSid, name, username, email, phone, role";
				$whereClause =  $columntemp[0] . " = " . $row['PId'] ."";
				// Get prfile id and password from DB
				$result = $db->query($selectClause, $table, $whereClause );
				if($row = $result->fetch_assoc()) {
					return $row;
				}
			}
			else  {
				// Wrong Password
				return false;
			}
		}
		// No such user
		return false;	  
	}

	function profilesByCSId($CSId)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columntemp = $theColumns['Profile'];
		$table = $theTables['Profile'];
		$whereClause = $columntemp[1] . " = " . $CSId ;
		$result = $db->query('*', $table, $whereClause );
		$returnArray = null;
		while($row = mysqli_fetch_assoc($result))
		{
			$returnArray[] = $row; 
		}
		return $returnArray;
	}
	
	function controllersByCSId($CSId)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnController = $theColumns['Controller'];
		$table = $theTables['Controller'];
		$whereClause = $columnController[1] . " = " . $CSId;
		$result = $db->query( ' * ', $table, $whereClause );
		
		$returnArray = null;
		while($row = mysqli_fetch_assoc($result))
		{
			$partresult = controllersLastUsedHelpFunc($row['CSerieNo']);
			foreach($partresult as $key => $value)
			{
				$row[$key]= $value;
			}
			$returnArray[] = $row; 
		}
		return $returnArray;
	}

	function tagsByCSId($CSId)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnTag = $theColumns['Tag'];
		$columnProfile = $theColumns['Profile'];
		$table = $theTables['Tag'] . " tag , " . $theTables['Profile'] . " profile";
		$whereClause =  "tag." . $columnTag[1] . " = " . $CSId . " AND " . "tag." . $columnTag[2] . " = profile." . $columnProfile[0];
		$result = $db->query('*', $table, $whereClause );
		$returnArray = null;
		while($row = mysqli_fetch_assoc($result))
		{
			$partresult = tagLastUsed($row['TSerieNo']);
			foreach($partresult as $key => $value)
			{
				$row[$key]= $value;
			}
			$returnArray[] = $row; 
		}
		return $returnArray;
	}

	function controllersLastUsedHelpFunc($controllerID, $limitFrom=0, $limitTo=1)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnCUBT = $theColumns['Controller_used_by_tag'];
		$columnTag = $theColumns['Tag'];
		$columnUser = $theColumns['Profile'];

		$table = $theTables['Controller_used_by_tag'] . " cubt, " . $theTables['Tag'] . " tag," . $theTables['Profile']. " pro";
		$tagDeviceClause = " tag." . $columnTag[0] . " = cubt." . $columnCUBT[0];
		$tagUserClause = "tag." . $columnTag[2] . " = pro." . $columnUser[0];  
		$controller = "cubt." . $columnCUBT[1] . " = " . $controllerID;
		$whereClause = $controller . " AND " . $tagDeviceClause ." AND " . $tagUserClause;
		$ordering = 'lastTimeUsedFrom DESC';
		$otherSQL = 'LIMIT '. $limitFrom . ', '. $limitTo ;
		$selectValues= $columnCUBT[2] . " AS lastTimeUsedFrom , ". $columnCUBT[3] . " AS lastTimeUsedTo, pro.". $columnUser[2] . " AS lastUsedByProfile";
		
		$result = $db->query( $selectValues , $table, $whereClause, $ordering , $otherSQL);
		return  mysqli_fetch_assoc($result);
	}
	
	function tagLastUsed($tagID, $limitFrom = 0, $limitTo = 1)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnCon =  $theColumns['Controller'];
		$columnCUBT = $theColumns['Controller_used_by_tag'];

		$table = $theTables['Controller_used_by_tag'] . " cubt, "  . $theTables['Controller']. " con";
		$tagControllerClause =   "cubt." . $columnCUBT[1] . " = con." .  $columnCon[0]; 
		$tag = "cubt." . $columnCUBT[0] . " = " . $tagID;
		$whereClause = $tag . " AND " . $tagControllerClause ;
		$ordering = 'lastTimeUsedFrom DESC';
		$otherSQL = 'LIMIT '. $limitFrom . ', '. $limitTo ;
		$selectValues= $columnCUBT[2] . " AS lastTimeUsedFrom , ". $columnCUBT[3] . " AS lastTimeUsedTo, con.". $columnCon[2] . " AS lastUsedController";
		$result = $db->query( $selectValues , $table, $whereClause, $ordering , $otherSQL);
	
		return mysqli_fetch_assoc($result);
	}
	
	/* This will add a rule to a control system*/
	function addNewRuleToDB($ruleData, $arrayOfCondition, $arrayOfCondition)
	{
		if(get_class($ruleData)=='Rules')
		{
		}
		elseif(is_array($ruleData))
		{
		}
		else
		{
		return false;
		}
	}
	
	/* This will connect a rule to a Profile*/
	function addRuleToProfile($ruleId, $profileId)
	{
	}
	
	/* This will connect a Chore to a Profile*/
	function addChoreToProfile($choreId, $profileId)
	{
	}
	
	function hashPassword($password) {

		// Taken from: http://alias.io/2010/01/store-passwords-safely-with-php-and-mysql/
		// How to store passwords safely with PHP and MySQL

		// A higher "cost" is more secure but consumes more processing power
		$cost = 10;

		// Create a random salt
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

		// Prefix information about the hash so PHP knows how to verify it later.
		// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
		$salt = sprintf("$2a$%02d$", $cost) . $salt;

		// Hash the password with the salt
		$hash = crypt($password, $salt);

		return $hash;

	}
	
?>