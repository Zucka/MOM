<?php
/*require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/sqlHelper.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/getDataFromDBFunctions.php');*/
include_once "getDataFromDBFunctions.php";
include_once "sqlHelper.php";

function db_rules_user_can_turn_device_on($cId,$tId)
{
	$db = new MySQLHelper();
	$pId = $db->executeSQL("SELECT profileId from tag where tag.TSerieNo='$tId'")->fetch_assoc()['profileId'];
	if(isTagActive($tId) == false || isProfileActive($pId) == false)
	{
		return true;
	}
	
	$rules = getRulesFromPId($pId,true);
	if(!empty($rules))
	{
		$AccController ;
		$NotAccController ; 
		$AllAccController ; 
		$NotAllAccController ; 
		foreach($rules as $key => $rule)
		{
			
			if(ruleHasActionWithName($rule, 'Access controller'))
			{	
				if($rule['controllerId'] == $cId  )
				{
					$AccController[] = $rule;
				}
			}
			elseif(ruleHasActionWithName($rule, 'Cannot access controller'))
			{	
				if($rule['controllerId'] == $cId)
				{
					$NotAccController[] = $rule;
				}
			}
			elseif(ruleHasActionWithName($rule, 'Access controller'))
			{
				$AllAccController[]= $rule;
			}
			elseif(ruleHasActionWithName($rule, 'Cannot access controller'))
			{
				$NotAllAccController[] = $rule;
			}
		}
		
		if( !empty($AccController) && !empty($NotAccController))
		{
		}
		elseif(!empty($AccController))
		{
			if($AccController == 1)
			{
				
			}
		}
		elseif(!empty($NotAccController))
		{
		}
		elseif(!empty($AllAccController))
		{
		}
		elseif(!empty($NotAllAccController))
		{
		}
		
	}
	
/*
'Access any controller','Cannot access any controller',	'Access controller','Cannot access controller');
*/
	
	
	
	
	
	$permission = getRulesFromPId($pId,true);
	//return $permission;
	return true; //temporary
}

function db_rules_device_should_turn_off($cId)
{
	$db = new MySQLHelper();
	return false;
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

	

function timeperiodValidNowInRule($rule)
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

/*
'Access any controller','Cannot access any controller',	'Access controller','Cannot access controller');
*/
?>