<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/sqlHelper.php');

function db_device_turn_on($cId,$tId) {
	$db = new MySQLHelper();
	$db->autocommit(false);
	$db->executeSQL("INSERT INTO controller_used_by_tag VALUES ('$tId','$cId',NULL,NULL)"); //time=time when device was turned on, user=the user who turned the device on
	$db->executeSQL("UPDATE controller SET status='GREEN' WHERE CSerieNo='$cId'");
	$db->commit();
	$db->autocommit(true);
}

function db_device_turn_off($cId,$tId) {
	$db = new MySQLHelper();
	$db->autocommit(false);
	$time = $db->executeSQL("SELECT UNIX_TIMESTAMP(starttime) FROM controller_used_by_tag WHERE CSerieNo='$cId' AND TSerieNo='$tId' AND endtime IS NULL")->fetch_assoc()['starttime'];
	$timeSpent = floor((time()-$time)/60);
	$db->executeSQL("UPDATE controller SET status='RED' WHERE CSerieNo='$cId'");
	$db->commit();
	$db->autocommit(true);

	return $timeSpent;
}

//Verify that the given controller id belongs to the tag/user that uses it, also verify that the controller is added to the system
//Returns true/false
function db_device_verify_cId($cId,$tId)
{
	$db = new MySQLHelper();
	$result = $db->executeSQL("SELECT TRUE FROM control_system,tag,controller WHERE controller.CSerieNo='$cId' AND tag.TSerieNo='$tId' AND tag.CSId=control_system.CSId AND controller.CSId=control_system.CSId");
	if ($result->num_rows > 0)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}
?>