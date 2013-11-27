<?php
	/*get all profiles from a system id*/
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
	/* get all controller from a system id */
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
    /* get all tags from a system id */
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
	/* get the x latest users who have used the controller. This is used by controllersByCSId*/
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
	/* get the x latest controller which this tagId have activated. This is used by tagsByCSId */
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

	/* get rules and permission from a personID*/	
	/* (Permission returns)An array with the following key=>value:
PId: 1
RId: 1
validFromTime: 2013-11-27 16:06:59
CSId: 1
name: timeperiode
isPermission: 1
condId: 1
controllerId: 123
timeFrom: 2013-11-14 09:00:00
timeTo: 2013-11-14 11:00:00
weekdays: monday,tuesday,wednesday,thursday,friday
weekly: 1
ndWeekly: 0
rdWeekly: 0
firstInMonth: 0
lastInMonth: 0
weekNumber: 23
	*/
	function getRulesFromPId($personId, $isPermission = false)
	{//($selectValues, $tables, $whereClause = NULL, $ordering = NULL, $otherSQL = NULL, $distinctResults = false)
		$db= new MySQLHelper();
		$db->autocommit(true);
		global $theTables;
		global $theColumns;
		$columnCond =  $theColumns['Rcondition'];
		$columnCondTP = $theColumns['Cond_timeperiod'];
		$columnCondTS = $theColumns['Cond_timestamp'];
		$columnAction = $theColumns['Action'];
		$columnRules = $theColumns['Rules'];
		$columnPHR = $theColumns['Profile_has_rules'];
		
		$selectValues='*';
		$tables = $theTables['Profile_has_rules']. ' phr,' . $theTables['Rules'] . ' r';
		$whereClause = 'phr.'. $columnPHR[0] . ' = '. $personId . ' AND phr.' . $columnPHR[1] . '= r.'. $columnRules[0]; 
		if($isPermission)
		{
			$tables .= ',' . $theTables['Rcondition'] . ' cond,' . $theTables['Cond_timeperiod'] . ' condTP';
			$whereClause .= ' AND r.'. $columnRules[3] .'='. $isPermission . ' AND cond.' . $columnCond[1] . "= r.". $columnRules[0].  ' AND condTP.' . $columnCondTP[0] . "= cond.". $columnCond[0]; 
		
			$result = $db->query($selectValues, $tables, $whereClause );
			$returnArray = null;
			while($row = mysqli_fetch_assoc($result))
			{	
				$returnArray[] = $row; 
			}
			return $returnArray;
		}
		else
		{
		
		}
	}
?>