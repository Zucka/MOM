<?php
	include_once "configDB.php";
	include_once "classes.php";
	include_once "sqlHelper.php";
	include_once "errorMessageSQL.php";
	include_once "getDataFromDBFunctions.php";
	
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
			if($object->cost != null)
			{
				$colums .= "," . $columstemp[5];
				$values .= ", ". $object->cost;
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
			return sqlErrorMessage($resultValue[1]);
		}
		return null;
	}

	/*edit data in database in the tables Control_system,Profile, Tag, Controller and Chores*/
	/*in the classes the variables that not should be change must be null, 
	but I always need the Id, e.g. if you want to change attributes in Tag then TSerieNo must not be null */
	function simpleUpdateDB($object)
	{
	$db= new MySQLHelper();
		global $theColumns;
		global $theTables;
		$table;
		$columnValue;
		$where;

		switch (get_class($object))
		{ 		
		case 'Control_system': //'name' , 'street', 'postcode', 'phoneNo'
			$table=  $theTables['Control_system'];
			$columstemp= $theColumns['Control_system'];
			$columnValue = "" ;
			if($object->name != null)
			{
				$columnValue .= $columstemp[1] . " ='" .  $object->name . "'";
			}
			if($object->street != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .=  $columstemp[2] . " = '" . $object->street . "'";
			}
			if($object->postcode != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .=  $columstemp[3] . " = '" . $object->postcode . "'";
			}
			if($object->phoneNo != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .= $columstemp[4] . " = '" . $object->phoneNo. "'";
			}
			
			$where = $columstemp[0] . " = " . $object->CSId;
			//$(username,) $password,( $CSId = null)
			//('CSId', 'username', )'password'
			break;
		case 'Profile':
			$table=  $theTables['Profile'];
			$columstemp= $theColumns['Profile'];
			$columnValue = "" ;
			if($object->name != null)
			{
				$columnValue .= $columstemp[2] . " = '" . $object->name ;
			}
			if($object->points != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .= $columstemp[3] . " = " .$object->points;
			}
			if($object->username != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .= $columstemp[4] . " = '" . $object->username . "'";
			}
			if($object->password != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .= $columstemp[5] . " = '" . $object->password . "'";
			}
			if($object->email != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .=   $columstemp[6] . " = '" . $object->email . "'";
			}
			if($object->phoneNo != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .=   $columstemp[7] . " = '" .$object->phoneNo . "'";
			}
			if($object->role != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .=  $columstemp[8] . " = '" .$object->role . "'";
			}
			
			$where = $columstemp[0] . " = " . $object->profileId;
			break;
		case 'Tag':
			$table=  $theTables['Tag'];
			$columstemp= $theColumns['Tag'];
			$where = $columstemp[0] . " = " . $object->TSerieNo;
			$columnValue = "";
			if($object->profileId != null)
			{
			
				$columnValue = $columstemp[2] . " = " . $object->profileId ;
			}
			if($object->name != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .=   $columstemp[3] . " = '" . $object->name . "'";
			}
			if($object->active != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .= $columstemp[4] . " = " . $object->active;
			
			}
			//('TSerieNo','CSId',) 'profileId', 'name', 'active'
			//$(CSId,) $profileId, ($TSerieNo = null ),   $name= null, $active = null 
			break;
		case 'Controller':
			$table=  $theTables['Controller'];
			$columstemp= $theColumns['Controller'];
			$where = $columstemp[0] . " = " . $object->CSerieNo;
			$columnValue = "" ;
			if($object->name != null)
			{
				$columnValue .= $columstemp[2] . " = '" . $object->name . "'";
			}
			if($object->location != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .=  $columstemp[3] . " = '" . $object->location . "'";
			}
			/*I don't really intent this to be here but if anyone need to set it then its here
			,"... SET status = 'RED' "
			if($object->status != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .=  $columstemp[4] . " = '" . $object->status . "'";
			}*/
			if($object->cost != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .=  $columstemp[5] . " = " . $object->cost;
			}
			break;
			
		case 'Chores': 
			$table=  $theTables['Chores'];
			$columstemp= $theColumns['Chores'];
			$where = $columstemp[0] . " = " . $object->CId;
			$columnValue = "";
			if($object->name != null)
			{
				$columnValue .= $columstemp[2] . " = '" . $object->name . "'";
			}
			if($object->defaultPoints != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .= $columstemp[4] . " = " . $object->defaultPoints;
			}
			if($object->description != null)
			{
				if($columnValue != "")
				{
					$columnValue .=  ", ";
				}
				$columnValue .=  $columstemp[3] . " = '" . $object->description . "'";
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
			return sqlErrorMessage($resultValue[1]);
		}
		return null;
	}
	
	/*delete in database Profile, Tag, Controller and Chores*/
	function removeObjectFromDB($object)
	{
		$db= new MySQLHelper();
		global $theColumns;
		global $theTables;
		$table;
		$where=null;
		
		switch (get_class($object))
		{
		case 'Control_system':
			$table=  $theTables['Control_system'];
			$columntemp = $theColumns['Control_system'];
			$where = $columntemp[0] . " = " . $object->CSId;
			break;
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
		case 'Rules':
			$table=  $theTables['Rules'];
			$columntemp = $theColumns['Rules'];
			$where = $columntemp[0] . " = " . $object->RId;
			break;
		case 'Condition':
			$table=  $theTables['Rcondition'];
			$columntemp = $theColumns['Rcondition'];
			$where = $columntemp[0] . " = " . $object->condId;
			break;
		case 'Action':
			$table=  $theTables['Action'];
			$columntemp = $theColumns['Action'];
			$where = $columntemp[0] . " = " . $object->AId;
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
			return sqlErrorMessage($resultValue[1]);
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
	
	
	/* This will connect a rule to a Profile*/
	function addRuleToProfile($profileId ,$ruleId)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		
		$tempcol = $theColumns['Profile_has_rules'];
		$table = $theTables['Profile_has_rules'];
		$column = "( " . $tempcol[0] . ", " . $tempcol[1] . ")";
		$values = "( ". $profileId . ", ". $ruleId . ")";
		$resultValue = $db->insertInto($table, $values, $column);
		if(is_bool($resultValue))
		{
			return $resultValue;
		}
		elseif(is_array($resultValue))
		{
			return sqlErrorMessage($resultValue[1]);
		}

	}
	
	/* This will connect a Chore to a Profile*/
	function addChoreToProfile($choreId, $profileId, $points= null)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		
		$tempcol = $theColumns['Profile_did_chores'];
		$table = $theTables['Profile_did_chores'];
		$column = "( " . $tempcol[0] . ", " . $tempcol[1] . ", ". $tempcol[2] .")";
		$values = "( ". $profileId . ", ". $choreId;
		if($points == null)
		{
			$result = $db->query($theColumns['Chores'][4] , $theTables['Chores'], $tempcol[1] . "= ". $choreId);
			if($row = mysqli_fetch_assoc($result))
			{
				$values .= ", " . $row['defaultPoints'] . ")"; 
			}
			else
			{
				echo 'error in addChoreToProfile:getting the default points';
			}
		}
		else
		{
			$values .= ", " . $points . ")";
		}
		
		$resultValue = $db->insertInto($table, $values, $column);
		if(is_bool($resultValue))
		{
			return $resultValue;
		}
		elseif(is_array($resultValue))
		{
			return sqlErrorMessage($resultValue[1]);
		}

	}
	
	/* This will add a rule with its conditions and actions to a control system*/
	function addNewRuleToDB($ruleData, $arrayOfCondition, $arrayOfAction)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$ruleID;
		if(get_class($ruleData)=='Rules')
		{ //'RId','CSId', 'name',  'isPermission'),
			$tempcol = $theColumns['Rules'];
			$table = $theTables['Rules'];
			$column = "( " . $tempcol[1] . ", " . $tempcol[2] ;
			$values = "( ". $ruleData->CSId . ", '". $ruleData->name . "'";
			if($ruleData->isPermission != null)
			{
				$column .= ", " . $tempcol[3]; 
				$values .= ", " . $ruleData->isPermission ;
			}
			$column .= ")"; 
			$values .= ")";
			$resultValue = $db->insertInto($table, $values, $column);
			if($resultValue)
			{
				$qResult = $db->executeSQL("SELECT LAST_INSERT_ID() AS id FROM " . $table);
				$row = mysqli_fetch_assoc($qResult);
				$ruleID = $row['id'];
				
				foreach($arrayOfCondition as $cond)
				{
					$tempresults = editCondition($cond);
					if(is_Array($tempresults))
					{
						return sqlErrorMessage($tempresults[1]);
					}
				}
				foreach($arrayOfAction as $action)
				{
					$tempresults = editAction($action);
					if(is_Array($tempresults))
					{
						return sqlErrorMessage($tempresults[1]);
					}
				}
				return $resultValue;
			}
			elseif(is_array($resultValue))
			{
				return sqlErrorMessage($resultValue[1]);
			}
			else
			{
				return false;
			}
		}
		else
		{
		return false;
		}
	}
	/*helper function to addNewRuleToDB*/
	function addAction($ruleID, $action)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		//'AId', 'RId',	'name', 'points','controllerId'
		$tempcol = $theColumns['Action'];
		$table = $theTables['Action'];
		$column = "( " . $tempcol[1] . ", " . $tempcol[2];
		$values = "( ". $ruleID . ", '". $action->name . "'";
		if( $action->points != null)
		{
			$column .= ", " . $tempcol[3];
			$values .= ", " . $action->points;
		}
		if( $action->controllerId != null)
		{
			$column .= ", " . $tempcol[4];
			$values .= ", " . $action->controllerId;
		}
		$column .= ")";
		$values .= ")";
		$resultValue = $db->insertInto($table, $values, $column);
		if(is_bool($resultValue))
		{
			return $resultValue;
		}
		elseif(is_array($resultValue))
		{
			return sqlErrorMessage($resultValue[1]);
		}
		
	}
	
	/*helper function to addNewRuleToDB*/
	function addCondition($ruleID, $cond)
	{
		$db= new MySQLHelper();
		
		global $theTables;
		global $theColumns;
		
		if(get_class($cond)=='Condition')
		{
			$tempcol = $theColumns['Rcondition'];
			$table = $theTables['Rcondition'];
			$condID;
			
			
			$column = "( " . $tempcol[1] . ", " . $tempcol[2];
			$values = "( ". $ruleID . ", '". $cond->name . "'";
			if($cond->controllerId != null)
			{
				$column .= ", " . $tempcol[3];
				$values .= ", ". $cond->controllerId;
			}
			$column .= " )";
			$values .= " )";
			
			$resultValue = $db->insertInto($table, $values, $column);
			if($resultValue)
			{
				$qResult = $db->executeSQL("SELECT LAST_INSERT_ID() AS id FROM " . $table);
				$row = mysqli_fetch_assoc($qResult);
				$condID = $row['id'];

					/*specialist Condition handling*/
				/*$arrayOfRestAttributes contains (timestamp) or ( 'timeFrom','timeTo','weekdays','weekly','ndWeekly','rdWeekly','firstInMonth','lastInMonth','weekNumber')*/
				if($cond->arrayOfRestAttributes != null)
				{
					$extras = $cond->arrayOfRestAttributes;
					if(count($extras)<2)
					{
						$tempcol = $theColumns['Cond_timestamp'];
						$table = $theTables['Cond_timestamp'];	
						$column = "( " . $tempcol[0] ;
						$values = "( " . $condID ;		
						if($extras[$tempcol[2]]!=null)
						{
							$column .= ", ". $tempcol[1];
							$values .= ", " . $extras[$tempcol[1]];
						}
						$column .= ")" ;
						$values .= ")" ;		
					}
					else
					{ 
						$tempcol = $theColumns['Cond_timeperiod'];
						$table = $theTables['Cond_timeperiod'];		
						//               'condId'         'timeFrom',         'timeTo',             'weekdays'
						$column = "(" . $tempcol[0] . ", ".$tempcol[1] . ", ". $tempcol[2] . ", " . $tempcol[3];
						$values = "(" . $condID . ", " . $extras[$tempcol[1]] . ", " . $extras[$tempcol[2]] . ", '" . $extras[$tempcol[3]]. "'";
						if($extras['weekly'] != null)
						{
							$column .= ", " . $tempcol[4];
							$values .= ", " . $extras[$tempcol[4]];
						}
						if($extras['ndWeekly']!= null)
						{
							$column .= ", ". $tempcol[5];
							$values .= ", ". $extras[$tempcol[5]];
						}
						if($extras['rdWeekly']!= null)
						{
							$column .= ", ". $tempcol[6];
							$values .= ", ". $extras[$tempcol[6]];
						}
						if($extras['firstInMonth']!= null)
						{
							$column .= ", ". $tempcol[7];
							$values .= ", ". $extras[$tempcol[7]];
						}
						if($extras['lastInMonth']!= null)
						{
							$column .= ", ". $tempcol[8];
							$values .= ", ". $extras[$tempcol[8]];
						}
						if($extras['weekNumber']!= null)
						{
							$column .= ", ". $tempcol[9];
							$values .= ", ". $extras[$tempcol[9]];
						}
						else
						{
							$column .= ", ". $tempcol[9];
							$values .= ", WEEK()";
						}
						$column .= ")";
						$values .= ")";
					}
					
					$resultValue = $db->insertInto($table, $values, $column);
					if(is_bool($resultValue))
					{
						return $resultValue;
					}
					elseif(is_array($resultValue))
					{
						return sqlErrorMessage($resultValue[1]);
					}
				}
			}
			else
			{
				return $resultValue;
			}
		}
		elseif(is_array($resultValue))
		{
			return sqlErrorMessage($resultValue[1]);
		}	
	}
	
	/* This will edit an existing rule with its conditions and actions*/
	function editRule($ruleData, $arrayOfCondition, $arrayOfAction)
	{
	$db= new MySQLHelper();

		global $theTables;
		global $theColumns;
		$ruleID;
		if(get_class($ruleData)=='Rules')
		{ //'RId','CSId', 'name',  'isPermission'),
		
			$tempcol = $theColumns['Rules'];
			$table = $theTables['Rules'];
			$columnValue =  $tempcol[2] . " = '" . $ruleData->name . "'";
			$where = $tempcol[0] . ' = ' . $ruleData->RId;
			if($ruleData->isPermission != null)
			{
				$columnValue .= ", " . $tempcol[3] ." = ". $ruleData->isPermission.""; 
			}

			$resultValue = $db->update($table, $columnValue, $where);
			if($resultValue)
			{
				foreach($arrayOfCondition as $cond)
				{
					//if a new condition should be added then make condition here
					$tempresults = editCondition($cond);
					if(is_Array($tempresults))
					{
						return sqlErrorMessage($tempresults[1]);
					}
				}
				foreach($arrayOfAction as $action)
				{
					//if a new condition should be added then make condition here
					$tempresults = editAction($action);
					if(is_Array($tempresults))
					{
						return sqlErrorMessage($tempresults[1]);
					}
				}
				
				return true;
			}
			elseif(is_array($resultValue))
			{
				return sqlErrorMessage($resultValue[1]);
			}
			else
			{
				return false;
			}
		}
		else
		{
		return false;
		}
	}
	
	/*helper function to editRule*/
	function editAction($action)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		//'AId', 'RId',	'name', 'points','controllerId'
		$tempcol = $theColumns['Action'];
		$table = $theTables['Action'];
		$where = $tempcol[0] . "=" . $action->AId;
		$columnValue =  $tempcol[2] . "='" . $action->name. "'";

		if( $action->points != null)
		{
			$columnValue .= ", " . $tempcol[3] . "=". $action->points;
		}
		if( $action->controllerId != null)
		{
			$columnValue .= ", " . $tempcol[4] . "=" . $action->controllerId;
		}
		$resultValue = $db->update( $table, $columnValue, $where);
		if(is_bool($resultValue))
		{
			return $resultValue;
		}
		elseif(is_array($resultValue))
		{
			return sqlErrorMessage($resultValue[1]);
		}
	}
	
	/*helper function to editRule*/
	function editCondition($cond)
	{
	
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		
		if(get_class($cond)=='Condition')
		{
			$tempcol = $theColumns['Rcondition'];
			$table = $theTables['Rcondition'];
			$columnValue =  $tempcol[2] . "='" . $cond->name. "'";
			$where =  $tempcol[0] ."=". $cond->condId;
			if($cond->controllerId != null)
			{
				$columnValue .= ", " . $tempcol[3] . "=". $cond->controllerId;
			}
			$resultValue = $db->update( $table, $columnValue, $where);
			if($resultValue)
			{
				/*specialist Condition handling*/
				/*$arrayOfRestAttributes contains (timestamp) or ( 'timeFrom','timeTo','weekdays','weekly','ndWeekly','rdWeekly','firstInMonth','lastInMonth','weekNumber')*/
				if($cond->arrayOfRestAttributes != null)
				{
					$extras = $cond->arrayOfRestAttributes;
					if(count($extras)<2)
					{
						$tempcol = $theColumns['Cond_timestamp'];
						$table = $theTables['Cond_timestamp'];		
						$columnValue =  $tempcol[1] ."=". $extras[$tempcol[1]];
						$where = $tempcol[0] ."=".  $cond->condId;		
					}
					else
					{ 
						$tempcol = $theColumns['Cond_timeperiod'];
						$table = $theTables['Cond_timeperiod'];		
						$where = $tempcol[0] . "=". $cond->condId;
						//               'timeFrom'                                ,'timeTo',                                       'weekdays'
						$columnValue = $tempcol[1] ."=". $extras[$tempcol[1]]. ", ". $tempcol[2] ."=". $extras[$tempcol[2]]. ", " . $tempcol[3]."='". $extras[$tempcol[3]]."'";
						if($extras['weekly'] != null)
						{
							$columnValue .= ", " . $tempcol[4]. "=". $extras[$tempcol[4]];
						}
						if($extras['ndWeekly']!= null)
						{
							$columnValue .= ", ". $tempcol[5]. "=". $extras[$tempcol[5]];
						}
						if($extras['rdWeekly']!= null)
						{
							$columnValue .= ", ". $tempcol[6]. "=". $extras[$tempcol[6]];
						}
						if($extras['firstInMonth']!= null)
						{
							$columnValue .= ", ". $tempcol[7]. "=". $extras[$tempcol[7]];
						}
						if($extras['lastInMonth']!= null)
						{
							$columnValue .= ", ". $tempcol[8]. "=". $extras[$tempcol[8]];
						}
						if($extras['weekNumber']!= null)
						{
							$columnValue .= ", ". $tempcol[9]. "=". $extras[$tempcol[9]];
						}
					}
					
					$resultValue = $db->update( $table, $columnValue, $where);
					if(is_bool($resultValue))
					{
						return $resultValue;
					}
					elseif(is_array($resultValue))
					{
						return sqlErrorMessage($resultValue[1]);
					}
				}
			}
			else
			{
				return $resultValue;
			}
		}
		elseif(is_array($resultValue))
		{
			return sqlErrorMessage($resultValue[1]);
		}
	}
	
	/*match the sqlErrorMessage*/
	function sqlErrorMessage($errorValue)
	{
		$errorMessage = null;
		switch($errorValue)
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
		case 1451:
			$errorMessage = $GLOBALS['SQL_ERROR_DELETE_FAILED'];
			break;
		default:
			$errorMessage = $GLOBALS['SQL_ERROR_OTHER'];
		}
		return $errorMessage;
	}
	
?>