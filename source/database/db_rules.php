<?php
/*require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/sqlHelper.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/getDataFromDBFunctions.php');*/
include_once "getDataFromDBFunctions.php";
include_once "sqlHelper.php";


/*This assumes that one rule has atmost 1 condition and 1 action*/
function db_rules_user_can_turn_device_on($cId,$tId)
{
	$db = new MySQLHelper();
	$pId = $db->executeSQL("SELECT profileId from tag where tag.TSerieNo='$tId'")->fetch_assoc()['profileId'];
	if(isTagActive($tId) == false || isProfileActive($pId) == false)
	{
		return true;
	}
	//check Timeperiod and True contrains 
	$rules = getRulesFromPId($pId,false);
	$result = checkRulesTrueAndTimeperiod($rules, $cId);
	if($result!=true) //Timeperiod and True allow the rule to use controller but need to check device on and off later
	{				//if not then check if Permissions give access in time
		$permission = getRulesFromPId($pId,true);
		$permissionGiving = false;
		if($permission!=null){
			foreach($permission as $per)
			{
				$timeNow =strtotime( $db->executeSQL("SELECT now() as time")->fetch_assoc()['time']);
				$timeNowFormatHMS = date("H:i:s",$timeNow );
				$timeNowFormatDay = strtolower(date("l", $timeNow));
				$timeTo =  date("H:i:s", strtotime( $per['timeTo'] ));
				$timeFrom =   date("H:i:s", strtotime( $per['timeFrom'] ));
				$timeNowFormatWeekNo = date("W",$timeNow );	
				if($timeNowFormatWeekNo == $per['weekNumber'] && strpos($per['weekdays'], $timeNowFormatDay) 
						&& $timeFrom <= $timeNowFormatHMS && $timeNowFormatHMS <= $timeTo  && ($per['controllerId'] == $cId))
				{
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



function db_rules_device_should_turn_off($cId)
{
	$db = new MySQLHelper();
	return false;
}

function db_rules_user_has_unlimited_points($pId)
{
	$db= new MySQLHelper();
	$rules=getRulesFromPId($profileId);
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
					if( ruleHasAControllerWithID($rule, $cId))
					{
						$AccController=$rule;
					}
				}
				elseif(ruleHasActionWithName($rule, 'Cannot access controller') )
				{	
					if(ruleHasAControllerWithID($rule, $cId))
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
	return false;
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
function ruleHasAControllerWithID($rule, $ID)
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
			$timeNowFormatWeekNo = date("W",$timeNow );					
			if($timeNowFormatWeekNo == $array['weekNumber'] && strpos($array['weekdays'], $timeNowFormatDay) 
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