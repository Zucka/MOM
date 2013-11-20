<?php
	include_once "configDB.php";
	include_once "classes.php";
	include_once "sqlHelper.php";
	
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
			$table=  $theTables['Control_system'];
			$columstemp= $theColumns['Control_system'];
			$colums="("  .  $columstemp[1] . ", " . $columstemp[2];
			$values= "( '"  . $object->username . "' , MD5('" .  $object->password ."')" ;
			if($object->email != null)
			{
				$colums .= ", " .  $columstemp[3] ;
				$values .= ", '" .   $object->email . "'";
			}
			if($object->phoneNo != null)
			{
				$colums .= ", " .  $columstemp[4] ;
				$values .= ", " .   $object->phoneNo . "";
			}
			$colums.=")";
			$values.=")";
			break;
		case 'Profile':
			$table=  $theTables['Profile'];
			$columstemp= $theColumns['Profile'];
			$colums="(" . $columstemp[1] . ", " . $columstemp[2] . " )";
			$values= "( " . $object->systemId . " , '" .  $object->name ."' )";
			break;
		case 'Tag':
			$table=  $theTables['Tag'];
			$columstemp= $theColumns['Tag'];
			$colums="(" . $columstemp[0] .", ". $columstemp[1] . ", " . $columstemp[2] ;
			$values= "( " . $object->TSerieNo . " ,". $object->systemId . " ," .  $object->profileId ;
			if($object->name != null)
			{
				$colums .= ", " .  $columstemp[3] ;
				$values .= ", '" .   $object->name . "'";
			}
			$colums.=")";
			$values.=")";
			break;
		case 'Controller':
			$table=  $theTables['Controller'];
			$columstemp= $theColumns['Controller'];
			$colums="(". $columstemp[0] .", ". $columstemp[1] .", " . $columstemp[2];
			$values= "( " . $object->CSerieNo . " , " . $object->systemId . " , '".  $object->name . "'";
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
			$values= "( ".$object->systemId . " , '". $object->name . "'";
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
		default:
		return null;
		}
		$resultValue = $db->insertInto($table, $values, $colums);
		if($resultValue == null)
		{ 
		echo $resultValue = false;
		}

		return $resultValue;
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
		{ //'Control_system' =>array('CSId', 'username', 'password', 'email', 'phoneNo'), 
		case 'Control_system': //change password, email, phoneNo'
			$table=  $theTables['Control_system'];
			$columstemp= $theColumns['Control_system'];
			$columnValue = $columstemp[2] . " = MD5('" .  $object->password . "')" ;
			if($object->email != null)
			{
				$columnValue .=  ", " . $columstemp[3] . " = '" . $object->email . "'";
			}
			if($object->phoneNo != null)
			{
				$columnValue .=  ", " . $columstemp[4] . " = " . $object->phoneNo;
			}
			
			$where = $columstemp[0] . " = " . $object->systemId;
			//$(username,) $password,( $systemId = null)
			//('CSId', 'username', )'password'
			break;
		case 'Profile':
			$table=  $theTables['Profile'];
			$columstemp= $theColumns['Profile'];
			$columnValue = $columstemp[2] . " = '" . $object->name . "' , " . $columstemp[3] . " = " . $object->points;
			$where = $columstemp[0] . " = " . $object->profileId;
			//('PId', 'CSId'), 'name', 'points',
			//($systemId,) $name, ($profileId = null) , $points
			echo $object->name. "<br>";
			break;
		case 'Tag':
			$table=  $theTables['Tag'];
			$columstemp= $theColumns['Tag'];
			$where = $columstemp[0] . " = " . $object->TSerieNo;
			$columnValue = $columstemp[2] . " = " . $object->profileId . ", " .$columstemp[4] . " = " . $object->active ;
			if($object->name != null)
			{
				$columnValue .=  ", " . $columstemp[3] . " = '" . $object->name . "'";
			}
			//('TSerieNo','CSId',) 'profileId', 'name', 'active'
			//$(systemId,) $profileId, ($TSerieNo = null ),   $name= null, $active = null 
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
			//($systemId,) $name, ($CSerieNo) , $location = null, $status = null
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
			//($systemId,) $name, ($CId =null) , $description = null, $defaultPoints = null 
			break;
		default:
		return;
		}
		$resultValue = $db->update( $table, $columnValue, $where);
		if($resultValue == null)
		{
			$resultValue =  false;
		}
		
		return $resultValue;
		
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
			$where = $columntemp[0] . " = " . $object->systemId;
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
		$resultvalue = $db->delete($table, $where);
		if($resultvalue == null)
		{
			$resultvalue= false;
		}
		return $resultvalue;
	}
	
	/* validate a username and password. Return false if it does not match a login and it returns the systemId if it does find a result.*/
	function validateLogin($username, $password)
	{
		$db= new MySQLHelper();
		
		global $theTables;
		global $theColumns;
		$columntemp = $theColumns['Control_system'];
		$table = $theTables['Control_system'];
		$whereClause = $columntemp[1] . " = '" . $username . "' AND " . $columntemp[2] . "= MD5('" . $password . "')";
		$result = $db->query('*', $table, $whereClause );
		$row = mysqli_fetch_array($result);
		if($row == null)
		{
			return false;
		}
		else
		{
			return $row['CSId'];
		}
		
		  
	}

	function profilesBySystemId($systemID)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columntemp = $theColumns['Profile'];
		$table = $theTables['Profile'];
		$whereClause = $columntemp[1] . " = " . $systemID ;
		$result = $db->query('*', $table, $whereClause );
		$returnArray = null;
		while($row = mysqli_fetch_assoc($result))
		{
			$returnArray[] = $row; 
		}
		return $returnArray;
	}
	
	function controllersBySystemId($systemID)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnController = $theColumns['Controller'];
		$table = $theTables['Controller'];
		$whereClause = $columnController[1] . " = " . $systemID;
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

	function tagsBySystemId($systemID)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnTag = $theColumns['Tag'];
		$columnProfile = $theColumns['Profile'];
		$table = $theTables['Tag'] . " tag , " . $theTables['Profile'] . " profile";
		$whereClause =  "tag." . $columnTag[1] . " = " . $systemID . " AND " . "tag." . $columnTag[2] . " = profile." . $columnProfile[0];
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
	
?>