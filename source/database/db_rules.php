<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/sqlHelper.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/getDataFromDBFunctions.php');

function db_rules_user_can_turn_device_on($cId,$tId)
{
	$db = new MySQLHelper();
	$pId = $db->executeSQL("SELECT profileId from tag where tag.TSerieNo='$tId'")->fetch_assoc()['profileId'];
	error_log($pId);
	$permission = getRulesFromPId($pId,true);
	print_r($permission);
	return $permission;
}

function db_rules_device_should_turn_off($cId)
{
	$db = new MySQLHelper();
}

?>