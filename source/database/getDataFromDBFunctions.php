<?php
	/*burde virke*/
	/*get all chores from a system id*/
	function getChoresFromCSID($csid)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnCon =  $theColumns['Chores']; //'Chores' =>array('CId', 'CSId', 'name', 'description', 'defaultPoints'), 
		

		$table =  $theTables['Chores'];
		$whereClause = $columnCon[1] .' = '. $csid;
		
		$result = $db->query( '*' , $table, $whereClause);
		$returnArray = null;
		while($row = mysqli_fetch_assoc($result))
		{
			$returnArray[] = $row;
		}
		return $returnArray;
		
	}
	//output array(choreName=>value,profileName=>value,points=>value,time=>value)
	function getChoresLogFromCSID($csid, $startFromIndex=null, $numberOfActivities=null)
	{
	//'Chores' =>array('CId', 'CSId', 'name', 'description', 'defaultPoints'), 
	//'Profile_did_chores' =>array('PId', 'CId', 'actualPoints', 'timeOfCreation'),
	//'Profile' =>array('PId', 'CSId', 'name', 'points', 'username', 'password', 'email','phone', 'role'),
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnC = $theColumns['Chores'];
		$columnPDC = $theColumns['Profile_did_chores'];
		$columnUser = $theColumns['Profile'];
		$otherSQL= null;
		$table = $theTables['Chores'] .' chore, '.$theTables['Profile_did_chores'] . ' pdc,' . $theTables['Profile'] . ' pro';
		$whereClause = 'chore.'. $columnC[1] .'='. $csid .
						'AND pcd'.$columnPDC[0] .'= pro'.$columnUser[0] . 
						'AND pcd.'. $columnPDC[1] . ' = chore.'. $columnC[0];
		$ordering = 'timeOfCreation DESC';
		if($startFromIndex===null && $numberOfActivities===null)
		{
		$otherSQL = 'LIMIT '. $startFromIndex . ', '. ($startFromIndex + $numberOfActivities) ;
		}
		$selectValues= 'chore.name AS choreName, pro.name AS profileName, pcd.actualPoints AS points, pcd.timeOfCreation AS time' 
		
		$result = $db->query( $selectValues , $table, $whereClause, $ordering , $otherSQL);
		$returnArray = null;
		while($row = mysqli_fetch_assoc($result))
		{
			$returnArray[] = $row;
		}
		return $returnArray;
	}
	
	/*get all profiles from a system id*/
	/*returns an array eg:
		PId: 1
		CSId: 1
		name: Johan Sørensen
		points: 4
		username: johans
		email: johan.soerensen6@gmail.com
		phone: 26136946
		role: manager*/
	function profilesByCSId($CSId)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$tempColumn = $theColumns['Profile'];
		$selectValue =  $tempColumn[0]. ", ". $tempColumn[1]. ", ". $tempColumn[2]. ", ". $tempColumn[3]. ", ". $tempColumn[4]. ", ".$tempColumn[6]. 
			", ". $tempColumn[7]. ", ". $tempColumn[8] ;		

		$table = $theTables['Profile'];
		$whereClause = $tempColumn[1] . " = " . $CSId ;
		$result = $db->query($selectValue, $table, $whereClause );
		$returnArray = null;
		while($row = mysqli_fetch_assoc($result))
		{
			$returnArray[] = $row; 
		}
		return $returnArray;
	}
	
	//- Du skal nok hører Johan om hvad der helt præcist skal med ud her.
	function getProfileByProfileId($PId) 
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$tempColumn = $theColumns['Profile'];
		$selectValue =  "p.".$tempColumn[0]. ", p.". $tempColumn[1]. ", p.". $tempColumn[2]. ", p.". $tempColumn[3]. ", p.". $tempColumn[4]. ", p.".$tempColumn[6]. 
			", p.". $tempColumn[7]. ", p.". $tempColumn[8] .", t.TSerieNo";		

		$table = $theTables['Profile']." p LEFT JOIN ".$theTables['Tag']." t ON t.profileId = p.PId";
		$whereClause = $tempColumn[0] . " = " . $PId ;
		$result = $db->query($selectValue, $table, $whereClause );
		$returnArray = null;
		if($row = mysqli_fetch_assoc($result))
		{
			$returnArray = $row;
		}
		return $returnArray;
	}
	
	//Get $tagId, $profileName, $profileId, $tagName and a array with the latested 10 activities for a tag
	/* e.g. output
	returnArray['TSerieNo']: 234
	returnArray['CSId']: 1
	returnArray['profileId']: 1
	returnArray['tagname']: ring1
	returnArray['active']: 0
	returnArray['PId']: 1
	returnArray['profilename']: Johan Sørensen
	returnArray['activity'] =>
		Array ( [lastTimeUsedFrom] => 2013-12-28 07:00:00 [lastTimeUsedTo] => [lastUsedController] => playstation ) 
		Array ( [lastTimeUsedFrom] => 2013-12-12 00:00:00 [lastTimeUsedTo] => 2013-12-12 16:00:00 [lastUsedController] => playstation ) 
		Array ( [lastTimeUsedFrom] => 2013-12-11 00:00:00 [lastTimeUsedTo] => 2013-12-11 00:00:00 [lastUsedController] => playstation ) 
		Array ( [lastTimeUsedFrom] => 2013-12-02 12:33:36 [lastTimeUsedTo] => 2013-12-02 11:00:00 [lastUsedController] => TV1 ) 
		Array ( [lastTimeUsedFrom] => 2013-12-01 00:00:00 [lastTimeUsedTo] => 2013-12-01 13:00:00 [lastUsedController] => playstation ) 
		Array ( [lastTimeUsedFrom] => 2013-11-27 13:15:17 [lastTimeUsedTo] => 2013-11-27 16:00:00 [lastUsedController] => TV1 ) 
		Array ( [lastTimeUsedFrom] => 2013-11-27 13:09:34 [lastTimeUsedTo] => 2013-11-27 13:09:44 [lastUsedController] => TV1 ) 
		Array ( [lastTimeUsedFrom] => 2013-11-27 12:50:57 [lastTimeUsedTo] => 2013-11-27 12:52:08 [lastUsedController] => TV1 ) 
		Array ( [lastTimeUsedFrom] => 2013-11-27 12:45:21 [lastTimeUsedTo] => 2013-11-27 12:48:06 [lastUsedController] => TV1 ) 
		Array ( [lastTimeUsedFrom] => 2013-11-26 12:29:19 [lastTimeUsedTo] => 2013-11-26 12:29:19 [lastUsedController] => TV1 ) 
		
*/
	function getTagByTagId($TSerieNo) 
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnTag = $theColumns['Tag'];
		$columnProfile = $theColumns['Profile'];
		//Tag' =>array('TSerieNo','CSId', 'profileId', 'name', 'active')
		$selectValue = "tag.". $columnTag[0] . ", tag." . $columnTag[1]. ", tag." . $columnTag[2]. ", tag." . $columnTag[3]. " AS tagname, tag." . $columnTag[4].
			", profile.". $columnProfile[0]. ", profile.". $columnProfile[2]. " AS profilename";
		$table = $theTables['Tag'] . " tag , " . $theTables['Profile'] . " profile";
		$whereClause =  "tag." . $columnTag[0] . " = " . $TSerieNo . " AND " . "tag." . $columnTag[2] . " = profile." . $columnProfile[0];
		$result = $db->query($selectValue, $table, $whereClause );
		$returnArray = null;
		if($row = mysqli_fetch_assoc($result))
		{
			$returnArray = $row;
			$returnArray['activities'] = tagLastUsed($TSerieNo, 0, 10);
		}
		return $returnArray;
	}
	
	//get a tags activities. 
	/* returns array where value => Array(
		[lastTimeUsedFrom] => 2013-11-27 13:15:17
		[lastTimeUsedTo]=> 2013-11-27 16:00:00
		[lastUsedController]=> TV1)
	*/
	function getTagActivity($TSerieNo,$startFromIndex,$numberOfActivities)
	{
		 return tagLastUsed($TSerieNo, $startFromIndex, $startFromIndex+$numberOfActivities);
	}
	
	// Get $controllerId, $controllerName, $controllerLocation from Controllers id. 
	/* return null or array with=>
	returnArray[CSerieNo] => 123
	returnArray[name] => TV1
	returnArray[location] => livingroom1*/
	function getControllerByControllerId($CId) 
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnController = $theColumns['Controller'];
		$table = $theTables['Controller'] ;
		$whereClause = $columnController[0] . " = " . $CId;
		//'Controller' =>array('CSerieNo','CSId', 'name' ,'location', 'status', 'cost' ),
		$selectValues = $columnController[0] . ',' . $columnController[1] . ',' . $columnController[2].','. $columnController[3] . ',' . $columnController[5];
		$result = $db->query( $selectValues, $table, $whereClause );
		
		$returnArray = null;
		if($row = mysqli_fetch_assoc($result))
		{
			$returnArray[] = $row; 
		}
		return $returnArray;
	}
	
	/* Returns true if the id does not exist in DB otherwise it returns false*/
	function isControllerIdAvalliable($CId)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnController = $theColumns['Controller'];
			
		$table = $theTables['Controller'] ;
		$whereClause =  $columnController[0] . " = " . $CId; 
		$result = $db->query('COUNT(*) AS cnt', $table, $whereClause );
		$returnValue = true;
		if($row = mysqli_fetch_assoc($result))
		{
			if($row['cnt'] == true)
			{
				$returnValue= false;
			}
		}
		return $returnValue;
	}

	/* Returns true if the id does not exist in DB otherwise it returns false*/
	function isTagIdAvailable($TId)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnTag = $theColumns['Tag'];
					
		$table = $theTables['Tag'] ;
		$whereClause =  $columnTag[0] . " = " . $TId; 
		$result = $db->query('COUNT(*) AS cnt', $table, $whereClause );
		$returnValue = true;
		if($row = mysqli_fetch_assoc($result))
		{
			if($row['cnt'] == true)
			{
				$returnValue= false;
			}
		}
		return $returnValue;
		
	}
	
	/* Returns true or false depending on whether a profileid is connected to a systemID
	evt. query: 'SELECT COUNT(*) FROM profile WHERE CSId = $input1 AND PId = $input2' - return 1*/
	function existsProfileInCS($PId,$CSId) 
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$tempColumn = $theColumns['Profile'];
		$table = $theTables['Profile'] ;
		$whereClause =  $tempColumn[0] . " = " . $PId . " AND ". $tempColumn[1] . "=" . $CSId; 
		$result = $db->query('COUNT(*) AS cnt', $table, $whereClause );
		$returnValue = false;
		if($row = mysqli_fetch_assoc($result))
		{
			if($row['cnt'] == true)
			{
				$returnValue= true;
			}
		}
		return $returnValue;
		
	}
	
	/* get all controller from a system id */
	/*return array where value is an array with:
		CSerieNo: 123
		CSId: 1
		name: TV
		location: livingroom
		status: GREEN
		cost: 1
		lastTimeUsedFrom: 2013-11-27 13:15:17(only if exist)
		lastTimeUsedTo: (only if exist)
		lastUsedByProfile: Johan Sørensen(only if exist)
	*/
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
			if($partresult = controllersLastUsedHelpFunc($row['CSerieNo']))
			{
				foreach($partresult[0] as $key => $value)
				{
					$row[$key]= $value;
				}
			}
			$returnArray[] = $row; 
		}
		return $returnArray;
	}
	
    /* get all tags from a system id */
	/*return array where value is an array with:
	TSerieNo: 234
	CSId: 1
	profileId: 1
	tagname: ring1
	active: 0
	PId: 1
	profilename: Johan Sørensen
	lastTimeUsedFrom: 2013-11-27 13:15:17 (only if exist)
	lastTimeUsedTo:  (only if exist)
	lastUsedController: TV (only if exist)
	*/
	function tagsByCSId($CSId)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnTag = $theColumns['Tag'];
		$columnProfile = $theColumns['Profile'];
		//Tag' =>array('TSerieNo','CSId', 'profileId', 'name', 'active')
		$selectValue = "tag.". $columnTag[0] . ", tag." . $columnTag[1]. ", tag." . $columnTag[2]. ", tag." . $columnTag[3]. " AS tagname, tag." . $columnTag[4].
			", profile.". $columnProfile[0]. ", profile.". $columnProfile[2]. " AS profilename";
		$table = $theTables['Tag'] . " tag , " . $theTables['Profile'] . " profile";
		$whereClause =  "tag." . $columnTag[1] . " = " . $CSId . " AND " . "tag." . $columnTag[2] . " = profile." . $columnProfile[0];
		$result = $db->query($selectValue, $table, $whereClause );
		$returnArray = null;
		while($row = mysqli_fetch_assoc($result))
		{
			if($partresult = tagLastUsed($row['TSerieNo']))
			{
				foreach($partresult[0] as $key => $value)
				{
					$row[$key]= $value;
				}
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
		$returnArray = null;
		while($row = mysqli_fetch_assoc($result))
		{
			$returnArray[] = $row;
		}
		return $returnArray;
	}
	
	/* get the x latest controller which this tagId have activated. This is used by tagsByCSId */
	/* returns array where value => Array(
		[lastTimeUsedFrom] => 2013-11-27 13:15:17
		[lastTimeUsedTo]=> 2013-11-27 16:00:00
		[lastUsedController]=> TV1)
	*/
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
		$returnArray = null;
		while($row = mysqli_fetch_assoc($result))
		{
			$returnArray[] = $row;
		}
		return $returnArray;
	}
	
	/*returns true if the profile is currently active and false otherwise*/
	function isProfileActive($profileId)
	{
		$db= new MySQLHelper();
		$rules=getRulesFromPId($profileId);
		$timeNow =strtotime( $db->executeSQL("SELECT now() as time")->fetch_assoc()['time']);
		$isblocked= false;
		if($rules != null)
		{foreach($rules as $rule)
		{
			foreach($rule['actions'] as $action)
			{
				if($action['name'] == 'Block user')
				{
					foreach($rule['conditions'] as $cond)
					{
						if($cond['name'] == 'Timeperiod')
						{
							if(timeperiodIsValidNowInRule($rule) )
							{
								$isblocked = true;
							}
						}
					}
				}
			}
		}}
		return $isblocked;
	}
	
	/* returns true if the tag is currently active and false otherwise*/
	function isTagActive($tagID)
	{
		$db= new MySQLHelper();
		
		$query = "SELECT active FROM tag WHERE TSerieNo = " . $tagID;
		$result = $db->executeSQL($query);
		if($row = mysqli_fetch_assoc($result))
		{
			if($row['active'] == 1)
			{
				return true;
			}
		}
		return false;
	}
	

	
	
		/* get rules and permission from a personID*/	

	/* (rules/permission returns)- an array that contain rules:so array(rule1, rule2 ....)
			rule1 is an array =>  array(rulesVariable, conditions, actions)
			rulesVariable is an array with => array(PId=> value,RId=>value,validFromTime=>value,CSId=>value,name=>value,isPermission=>value) 
			conditions is an array => array(cond1, cond2 ....)
			cond1 is an array with => array(condId=>value,RId=>value,name=>value,controllerId=>value, ekstra_attribute=>ArrayValue)
			ekstra_attribute is an array with => array(condId=>value,timeFrom=>value,timeTo=>value,weekdays=>value,weekly=>value,ndWeekly=>value,rdWeekly=>value,firstInMonth=>value,lastInMonth=>value,weekNumber=>value)
										OR with =>array(condId=>value, onTimestamp=>value)
										OR it is null
			actions is an array => array(action1, action2 ....)
			action1 is an array with => array(AId=>value,RId=>value,name=>value,points=>value)
	*/
	function getRulesFromPId($personId, $isPermission = false)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnCond =  $theColumns['Rcondition'];
		$columnCondTP = $theColumns['Cond_timeperiod'];
		$columnRules = $theColumns['Rules'];
		$columnPHR = $theColumns['Profile_has_rules'];
		
		$selectValues='*';
		$tables = $theTables['Profile_has_rules']. ' phr, ' . $theTables['Rules'] . " r" ;
		$whereClause = "phr.". $columnPHR[0] . ' = '. $personId ." AND phr.". $columnPHR[1] . '= r.'. $columnRules[0] ; 
		if($isPermission)
		{
			//$tables .= ',' . $theTables['Rcondition'] . ' cond,' . $theTables['Cond_timeperiod'] . ' condTP';
			$whereClause .= ' AND r.'. $columnRules[3] . '= '. $isPermission; //.' AND cond.' . $columnCond[1] . "= r.". $columnRules[0].  ' AND condTP.' . $columnCondTP[0] . "= cond.". $columnCond[0]; 
		
		/*	$result = $db->query($selectValues, $tables, $whereClause );
			$returnArray = null;
			while($row = mysqli_fetch_assoc($result))
			{	
				$returnArray[] = $row; 
			}
			return $returnArray;*/
		}
		else
		{
			$whereClause .= ' AND r.'. $columnRules[3] . '= false';
			
		}
		$result = $db->query($selectValues, $tables, $whereClause );//find all rules
			return getRuleHelper($result);
	}
	
	
		/* get rules and permission from a CSID*/	
	/* (Permission returns an array where value is an array with the following key=>value:
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
	/* (rules returns)- an array that contain rules:so array(rule1, rule2 ....)
			rule1 is an array =>  array(rulesVariable, conditions, actions)
			rulesVariable is an array with => array(PId=> value,RId=>value,validFromTime=>value,CSId=>value,name=>value,isPermission=>value) 
			conditions is an array => array(cond1, cond2 ....)
			cond1 is an array with => array(condId=>value,RId=>value,name=>value,controllerId=>value, ekstra_attribute=>ArrayValue)
			ekstra_attribute is an array with => array(condId=>value,timeFrom=>value,timeTo=>value,weekdays=>value,weekly=>value,ndWeekly=>value,rdWeekly=>value,firstInMonth=>value,lastInMonth=>value,weekNumber=>value)
										OR with =>array(condId=>value, onTimestamp=>value)
										OR it is null
			actions is an array => array(action1, action2 ....)
			action1 is an array with => array(AId=>value,RId=>value,name=>value,points=>value)
	*/
	function getRulesFromCSID($CSID, $isPermission = false)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnCond =  $theColumns['Rcondition'];
		$columnCondTP = $theColumns['Cond_timeperiod'];
		$columnRules = $theColumns['Rules'];
		
		$selectValues='*';
		$tables = $theTables['Rules'] . " r" ;
		$whereClause = "r.".  $columnRules[1].' = '. $CSID; 
		if($isPermission)
		{
			//$tables .= ',' . $theTables['Rcondition'] . ' cond,' . $theTables['Cond_timeperiod'] . ' condTP';
			$whereClause .= ' AND r.'. $columnRules[3] . '= '. $isPermission; //.' AND cond.' . $columnCond[1] . "= r.". $columnRules[0].  ' AND condTP.' . $columnCondTP[0] . "= cond.". $columnCond[0]; 
		
		/*	$result = $db->query($selectValues, $tables, $whereClause );
			$returnArray = null;
			while($row = mysqli_fetch_assoc($result))
			{	
				$returnArray[] = $row; 
			}
			return $returnArray;*/
		}
		else
		{
			$whereClause .= ' AND r.'. $columnRules[3] . '= false';
			
		}
		$result = $db->query($selectValues, $tables, $whereClause );//find all rules
			return getRuleHelper($result);
	}
	
	function getRuleHelper($result)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnCond =  $theColumns['Rcondition'];
		$columnCondTP = $theColumns['Cond_timeperiod'];
		$columnCondTS = $theColumns['Cond_timestamp'];
		$columnAction = $theColumns['Action'];
		$selectValues= '*';
		$returnArray = null;
		if($result!=null){
		while($row = mysqli_fetch_assoc($result))
		{				
			/*get all conditions for the rule*/
			$ruleArray['rulesVariable'] = $row;
			$tables = $theTables['Rcondition'] ;
			$whereClause = $row['RId'] . " = " . $columnCond[1] ;
			
			$tempresult = $db->query($selectValues, $tables, $whereClause );//find all conditions to the rules
			$conditionsArray = null;
			if($tempresult!=null){
			while($condition = mysqli_fetch_assoc($tempresult))
			{	
				if($condition['name'] == "Timeperiod" || $condition['name'] == "True" )
				{
					$tables = $theTables['Cond_timeperiod'] ; 
					$whereClause = $columnCondTP[0] . "=" . $condition['condId'] ;

					$timeResult = $db->query($selectValues, $tables, $whereClause ); //find timeperiode to this condition
					if($timeResult)
					{
						$condition['ekstra_attribute'] = mysqli_fetch_assoc($timeResult);
					}				
				}
				elseif($condition['name'] == "Timestamp")
				{
					$tables = $theTables['Cond_timestamp'] ; 
					$whereClause = $condition['condId'] . "=" . $columnCondTS[0] ;
					$timeResult = $db->query($selectValues, $tables, $whereClause );//find timestamp to this condition
					if($timeResult)
					{
						$condition['ekstra_attribute'] = mysqli_fetch_assoc($timeResult);
					}
				}
				else
				{
					$condition['ekstra_attribute'] = null;
				}
				$conditionsArray[] = $condition;
			}}				
			$ruleArray['conditions'] = $conditionsArray;
			
			/* get all actions for the rule*/
			$tables = $theTables['Action'] ;
			$whereClause = $row['RId'] . " = " . $columnAction[1] ;
			$tempresult = $db->query($selectValues, $tables, $whereClause );
			$actionArray = null;
			if($tempresult!=null)
			{
			while($tempRow = mysqli_fetch_assoc($tempresult))
			{
				$actionArray[] = $tempRow;
			}}
			$ruleArray['actions'] = $actionArray;
			
			$returnArray[] = $ruleArray; 
		}}
		
		return $returnArray;
	}
	


	// STATISTICS !!!
	// Get usage statistics by PId 
	// function getUsageByPId($PId, $type='device') {
	// 	$db= new MySQLHelper();
	// 	global $theTables;
	// 	global $theColumns;
	// 	$profile = getProfileByProfileId($PId);
	// 	$selectValue =  "cubt.TSerieNo,
	// 					cubt.CSerieNo,
	// 					con.CSId,
	// 					con.name,
	// 					con.location,
	// 					con.status,
	// 					con.cost,
	// 					cubt.starttime,
	// 					SUM((FLOOR((UNIX_TIMESTAMP(cubt.endtime) - UNIX_TIMESTAMP(cubt.starttime)) / 60))*con.cost) AS point";
	// 	$table = "controller_used_by_tag cubt LEFT JOIN  controller con ON con.CSerieNo = cubt.CSerieNo";
	// 	$whereClause = "cubt.TSerieNo = ".$profile['TSerieNo']." AND (UNIX_TIMESTAMP(cubt.endtime) - UNIX_TIMESTAMP(cubt.starttime) >= 59)";
	// 	$ordering = "";
	// 	$otherSQL = " GROUP BY con.CSerieNo ";
	// 	$result = $db->query($selectValue, $table, $whereClause, $ordering, $otherSQL);
	// 	$returnArray = null;
	// 	while($row = mysqli_fetch_assoc($result))
	// 	{
	// 		$returnArray[] = $row; 
	// 	}
	// 	return $returnArray;
	// }
	function getUsageByPId($PId, $type='device') {
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$profile = getProfileByProfileId($PId);
		$selectValue =  "subSelect1.TSerieNo,
					    subSelect1.CSerieNo,
					    subSelect1.CSId,
					    subSelect1.name,
					    subSelect1.location,
					    subSelect1.status,
					    subSelect1.cost,
					    subSelect1.starttime,
					    subSelect1.point,
					    (subSelect1.point / (subSelect.totalPoints / 100)) AS percentage,
					    subSelect.totalPoints";
		$from = "(SELECT 
			        SUM((FLOOR((UNIX_TIMESTAMP(cubt.endtime) - UNIX_TIMESTAMP(cubt.starttime)) / 60)) * con.cost) AS totalPoints
			    FROM
			        controller_used_by_tag cubt
			    LEFT JOIN controller con ON con.CSerieNo = cubt.CSerieNo
			    WHERE
			        cubt.TSerieNo = ".$profile['TSerieNo']."
			            AND (UNIX_TIMESTAMP(cubt.endtime) - UNIX_TIMESTAMP(cubt.starttime) >= 59)
			    GROUP BY cubt.TSerieNo) AS subSelect,
			    (SELECT 
			        cubt1.TSerieNo,
		            cubt1.CSerieNo,
		            con1.CSId,
		            con1.name,
		            con1.location,
		            con1.status,
		            con1.cost,
		            cubt1.starttime,
		            SUM((FLOOR((UNIX_TIMESTAMP(cubt1.endtime) - UNIX_TIMESTAMP(cubt1.starttime)) / 60)) * con1.cost) AS point
			    FROM
			        controller_used_by_tag cubt1
			    LEFT JOIN controller con1 ON con1.CSerieNo = cubt1.CSerieNo
			    WHERE
			        cubt1.TSerieNo = ".$profile['TSerieNo']."
			            AND (UNIX_TIMESTAMP(cubt1.endtime) - UNIX_TIMESTAMP(cubt1.starttime) >= 59)
			    GROUP BY con1.CSerieNo) AS subSelect1";
		$result = $db->query($selectValue, $from);
		$returnArray = null;
		while($row = mysqli_fetch_assoc($result))
		{
			$returnArray[] = $row; 
		}
		return $returnArray;
	}

?>