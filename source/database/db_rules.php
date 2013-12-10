<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/sqlHelper.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/getDataFromDBFunctions.php');
include_once "getDataFromDBFunctions.php";

function db_rules_user_can_turn_device_on($cId,$tId)
{
	$db = new MySQLHelper();
	$pId = $db->executeSQL("SELECT profileId from tag where tag.TSerieNo='$tId'")->fetch_assoc()['profileId'];
	if(isTagActive($tId) == false || isProfileActive($pId) == false)
	{
		return true
	}
	
	$rules = getRulesFromPId($pId,true);
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
	return hasPersonIdUnlimitedPoints($pId);
}

function get_relevant_rules($rules)
{
	if($rules != null)
	{	foreach($rules as $index => $rule)
		{
			foreach($rule['actions'] as $action)
			{
				if($action['name'] != 'Access any controller' || $action['name'] != 'Cannot access any controller'
					|| $action['name'] != 'Access controller' || $action['name'] != 'Cannot access controller')
				{
					unset($arrayOfCondition[$index]);
				}
			}
		}
	}	
}

?>