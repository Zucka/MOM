<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/sqlHelper.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/getDataFromDBFunctions.php');



/*This assumes that one rule has at most 1 condition and 1 action*/
function db_rules_user_can_turn_device_on($cId,$tId)
{
	$db = new MySQLHelper();
	$pId = $db->executeSQL("SELECT profileId from tag where tag.TSerieNo='$tId'")->fetch_assoc()['profileId'];
	if(isTagActive($tId) == false || isProfileActive($pId) == false)
	{
		return false;
	}
	//check Timeperiod and True contrains 
	$rules = getRulesFromPId($pId,false);
	$result = checkRulesTrueAndTimeperiod($rules, $cId);
	if($result===false)
	{
		return false;
	}
	/*elseif($result===null) //Timeperiod and True allow the rule to use controller but need to check device on and off later
	{				//if not then check if Permissions give access in time
		$permission = getRulesFromPId($pId,true);
		$permissionGiving = false;
		if($permission!=null){
			foreach($permission as $per)
			{
				/*$timeNow =strtotime( $db->executeSQL("SELECT now() as time")->fetch_assoc()['time']);
				$timeNowFormatHMS = date("H:i:s",$timeNow );
				$timeNowFormatDay = strtolower(date("l", $timeNow));
				$timeTo =  date("H:i:s", strtotime( $per['timeTo'] ));
				$timeFrom =   date("H:i:s", strtotime( $per['timeFrom'] ));
				$week = date('W',strtotime( $per['timeFrom'] ));
				$timeNowFormatWeekNo = date("W",$timeNow );	
				$weekValid = true;
				if ($array['ndWeekly'] == true) {$weekValid = ($timeNowFormatWeekNo-$week) % 2 == 0;}					
				if ($array['rdWeekly'] == true) {$weekValid = ($timeNowFormatWeekNo-$week) % 3 == 0;}
				if($per['controllerId'] == $cId && $weekValid && strpos($per['weekdays'], strtolower($timeNowFormatDay)) 
						&& $timeFrom <= $timeNowFormatHMS && $timeNowFormatHMS <= $timeTo)
				$timeNowFormatWeekNo = date("W",$timeNow );	*/
	/*			if($per['conditions'][0]['controllerId'] == $cId) /*&& $timeNowFormatWeekNo == $per['weekNumber'] && strpos($per['weekdays'], strtolower($timeNowFormatDay)) 
						&& $timeFrom <= $timeNowFormatHMS && $timeNowFormatHMS <= $timeTo)*/
	/*			{
					$permissionGiving = true;
					break;
				}
			}
		}
		if($permissionGiving != true)
		{
			return false;
		}
	}
	*/
	//check on device on device off constrains
	if($rules !=null)
	{
		foreach($rules as $rule)
		{
			if(ruleHasConditionWithName($rule,'Controller off'))
			{
				$controllerToBeOff = $rule['conditions'][0]['controllerId'];
				$status = $db->executeSQL("SELECT status FROM controller WHERE CSerieNo = '$cId'")->fetch_assoc()['status'];
				if($status == 'GREEN') // ! => is a unknown  
				{
					return false;
				}
			}
			elseif(ruleHasConditionWithName($rule, 'Controller on'))
			{
				$controllerToBeOn = $rule['conditions'][0]['controllerId'];
				$status = $db->executeSQL("SELECT status FROM controller WHERE CSerieNo = '$cId'")->fetch_assoc()['status'];
				if($status == 'RED') // ! => is a unknown 
				{
					return false;
				}
			}
		}
	}
	return true;
	
	

}


/*This assumes that one rule has at most 1 condition and 1 action*/
function db_rules_device_should_turn_off($cId, $pId)
{
	$db = new MySQLHelper();
	$timeNow =strtotime( $db->executeSQL("SELECT now() as time")->fetch_assoc()['time']);
	$rules=getRulesFromPId($pId);
	
	$lowestTime= null;
	if($rules != null)
	{echo 'in rule <br>';
	foreach($rules as $rule)
	{
		$timeTo=null;
		$timeNowFormatHMS=null;
		
		//I assume they already have access so I don't check whether they are allowed to be on 
		//check it is a Access controller or Access any controller with a valid timeperiod
		if((ruleHasActionWithName($rule, 'Access any controller') 
			|| (ruleHasAActControllerWithID($rule, $cId) && ruleHasActionWithName($rule, 'Access controller'))) 
			&& ruleHasConditionWithName($rule, 'Timeperiod') && timeperiodIsValidNowInRule($rule))
		{
			if(conditionRepeatable($rule['conditions'][0])) //is repeatable
			{
				$timeTo =  date("H:i:s", strtotime(  $rule['conditions'][0]['ekstra_attribute']['timeTo'] ));
			}
			else//is not repeat
			{
				$timeTo =  date("d M Y H:i:s", strtotime(  $rule['conditions'][0]['ekstra_attribute']['timeTo'] ));
			}
			//check which is the shortest amount of time.
			if($timeTo < $lowestTime || $lowestTime==null)
			{
				$lowestTime=$timeTo;
			}
		}//check it is a Cannot access controller or Cannot access any controller with a timeperiod
		elseif( (ruleHasActionWithName($rule, 'Cannot access any controller') 
			|| (ruleHasAActControllerWithID( $rule, $cId) && ruleHasActionWithName($rule, 'Cannot access controller'))) 
			&& ruleHasConditionWithName($rule, 'Timeperiod'))// rule has action Cannot access any controller or Cannot access controller with a timeperiod
		{	
			if(conditionRepeatable($rule['conditions'][0])) //is repeatable
			{
				$timeNowFormat = date("H:i:s",$timeNow );
				$timeFrom =  date("H:i:s", strtotime(  $rule['conditions'][0]['ekstra_attribute']['timeFrom'] ));
			}
			else//is not repeat
			{
				$timeNowFormat = date("d M Y H:i:s",$timeNow );
				$timeFrom =  date("d M Y H:i:s", strtotime(  $rule['conditions'][0]['ekstra_attribute']['timeFrom'] ));
			}
			if($timeFrom > $timeNowFormat && ( $timeFrom < $lowestTime || $lowestTime==null))
			{
				$lowestTime=$timeFrom;
			}
		}
	}}
	/*
	Dette burde ikke være relevant mere
	$permissions=getRulesFromPId($pId, true);
	if($permissions != null){
	echo 'is permission<br>';
	foreach($permissions as $per)
	{
		
		$timeNowFormatHMS = date("H:i:s",$timeNow );
		$timeNowFormatDay = strtolower(date("l", $timeNow));
		$timeTo =  date("H:i:s", strtotime( $per['timeTo'] ));
		$timeFrom =   date("H:i:s", strtotime( $per['timeFrom'] ));
		$week = date('W',strtotime( $per['timeFrom'] ));
		$timeNowFormatWeekNo = date("W",$timeNow );	
		$weekValid = true;
		if ($array['ndWeekly'] == true) {$weekValid = ($timeNowFormatWeekNo-$week) % 2 == 0;}					
		if ($array['rdWeekly'] == true) {$weekValid = ($timeNowFormatWeekNo-$week) % 3 == 0;}
		if($per['controllerId'] == $cId && $weekValid && strpos($per['weekdays'], $timeNowFormatDay) 
				&& $timeFrom <= $timeNowFormatHMS && $timeNowFormatHMS <= $timeTo )
		{
			if( $lowestTime==null || $timeTo < $lowestTime)
			{
				$lowestTime=$timeTo;
				
			}
		}
	}}*/
	
	if($lowestTime == null)
	{
		return false;
	}
	else
	{
		return $lowestTime;
	}
}

function db_rules_user_has_unlimited_points($pId)
{
	$db= new MySQLHelper();
	$rules=getRulesFromPId($pId);
	$timeNow =strtotime( $db->executeSQL("SELECT now() as time")->fetch_assoc()['time']);
		
	if($rules != null)
	{	foreach($rules as $rule)
		{
			foreach($rule['actions'] as $action)
			{
				if($action['name'] == 'Unlimited time')
				{
					foreach($rule['conditions'] as $cond)
					{
						if($cond['name'] == 'Timeperiod')
						{
							timeperiodValidNowInRule($rule);
						}
						elseif($cond['name'] == 'True')
						{
							return true;
						}
					}
				}
			}
		}
	}
}

/*  ----------------------- HELPER FUNCTION  ------------------------- */
/*This assumes that one rule has atmost 1 condition and 1 action*/
function checkRulesTrueAndTimeperiod($rules, $cId)
{
	if($rules !=null)
	{
		$AccController =null;
		$NotAccController =null; 
		$AllAccController =null; 
		$NotAllAccController =null; 
		foreach($rules as $key => $rule)
		{
			$isTimeP=ruleHasConditionWithName($rule, 'Timeperiod');
			$isTrue=ruleHasConditionWithName($rule, 'True');
			if($isTrue || ($isTimeP && timeperiodIsValidNowInRule($rule)))//check time rules
			{
				if(ruleHasActionWithName($rule, 'Access controller')  )
				{	
					if( ruleHasAActControllerWithID($rule, $cId))
					{
						$AccController=$rule;
					}
				}
				elseif(ruleHasActionWithName($rule, 'Cannot access controller') )
				{	
					if(ruleHasAActControllerWithID($rule, $cId))
					{
						$NotAccController=$rule;
					}
				}
				elseif(ruleHasActionWithName($rule, 'Access any controller') == true )
				{
					$AllAccController=$rule;
				}
				elseif(ruleHasActionWithName($rule, 'Cannot access any controller') )
				{
					$NotAllAccController=$rule;
				}
			}
			else
			{
				return false;
			}
		}
		
		if( $AccController != null && $NotAccController != null)
		{
		
			$AccCName= $AccController['conditions'][0]['name'];
			$NotAccCName = $NotAccController['conditions'][0]['name'];
			if($AccCName =='Timeperiod' || $NotAccCName == 'Timeperiod')
			{
			if($AccCName =='Timeperiod' && $NotAccCName == 'Timeperiod')
				{
				
					$result1=conditionRepeatable($AccController['conditions'][0]);
					$result2=conditionRepeatable($NotAccController['conditions'][0]);
					if((!$result1 && !$result2) || ($result1 && $result2))
					{
					
						//then $AccCName
						return true;
					}
					elseif($result1 == false) //not repeatable
					{
						return true;
					}
					elseif($result2 == false)//not repeatable
					{
						return false;
					}
				}
				elseif($AccCName =='Timeperiod')
				{
					return true;
				}
				elseif($NotAccCName == 'Timeperiod')
				{
					return false;
				}
			}
			else
			{
				//then both names is True then $AccCName
				return true;
			}
			
		}
		elseif($AccController != null)
		{
			return true;
		}
		elseif($NotAccController != null)
		{
			return false;
		}
		elseif($AllAccController != null)
		{
			return true;
		}
		elseif($NotAllAccController != null)
		{
			return false;
		}
		
	}
	return null;
}
	
function ruleHasActionWithName($rule, $name)
{
	$actions = $rule['actions'];
	foreach($actions as $action)
	{
		if($action['name'] == $name)
		{
			return true;
		}
	}
	return false;
}
//this is only look on action-side for Id 
function ruleHasAActControllerWithID($rule, $ID)
{
	$actions = $rule['actions'];
	foreach($actions as $action)
	{
		if($action['controllerId'] == $ID)
		{
			return true;
		}
	}
	return false;
}

function ruleHasACondControllerWithID($rule, $ID)
{
	$conditions = $rule['conditions'];
	foreach($conditions as $cond)
	{
		if($cond['controllerId'] == $ID)
		{
			return true;
		}
	}
	return false;
}
function ruleHasConditionWithName($rule, $name)
{
	$conditions= $rule['conditions'];
	foreach($conditions as $cond)
	{
		if($cond['name'] == $name)
		{
			return true;
		}
	}
	return false;
}

	

function timeperiodIsValidNowInRule($rule)
{
	$db = new MySQLHelper();
	$timeNow =strtotime( $db->executeSQL("SELECT now() as time")->fetch_assoc()['time']);

	foreach($rule['conditions'] as $condition)
	{
		$array = $condition['ekstra_attribute'];
		$repeateble = conditionRepeatable($condition);
		if($condition['name'] == 'Timeperiod' && $repeateble)
		{
			$timeNowFormatHMS = date("H:i:s",$timeNow );
			$timeNowFormatDay = strtolower(date("l", $timeNow));
			$timeTo =  date("H:i:s", strtotime( $array['timeTo'] ));
			$timeFrom =   date("H:i:s", strtotime( $array['timeFrom'] ));
			$week = date('W', strtotime( $array['timeFrom'] ));
			$weekDelta = floor(($timeNow-$timeFrom) / 60 / 60 / 24 / 7); //seconds to weeks
			$weekValid = true;
			if ($array['ndWeekly'] == true) {$weekValid = $weekDelta % 2 == 0;}					
			if ($array['rdWeekly'] == true) {$weekValid = $weekDelta % 3 == 0;}					
			if($weekValid && strpos($array['weekdays'], $timeNowFormatDay) 
				&& $timeFrom <= $timeNowFormatHMS && $timeNowFormatHMS <= $timeTo )
			{
				return true;
			}
		}
		elseif($condition['name'] == 'Timeperiod' && !$repeateble)
		{
			$fromTime = date("d M Y H:i:s", strtotime($array['timeFrom']));
			$toTime = date("d M Y H:i:s", strtotime($array['timeTo']));
			$timeNow = date("d M Y H:i:s",$timeNow );								
			if( $fromTime <= $timeNow && $timeNow <= $toTime )
			{								
				return true;
			}
		}
	}
	return false;
}
function conditionRepeatable($condition)
{
	
	$array = $condition['ekstra_attribute'];
	//if repeatable
	if((!empty($array['weekdays'])) || $array['weekly'] == true || $array['ndWeekly'] == true || $array['rdWeekly'] == true
		|| $array['firstInMonth'] == true || $array['lastInMonth'] == true)
	{ 
			return true;
	}
	//if non repeatable
	else
	{
		return false;
	}
} 


?>