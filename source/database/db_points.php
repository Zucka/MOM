<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/sqlHelper.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/DBfunctions.php');
function db_points_remove($tId,$points)
{
	$db = new MySQLHelper();
	$db->autocommit(FALSE);
	$row = $db->executeSQL("SELECT points,PId FROM profile,tag WHERE tag.TSerieNo='$tId' AND tag.profileId=profile.PId")->fetch_assoc();
	$pId = $row['PId'];
	$currentPoints = $row['points']-$points;
	if ($currentPoints < 0) 
	{
		//something wrong, this should not happen!
	}
	else
	{
		$result = $db->executeSQL("UPDATE profile SET points='$currentPoints' WHERE PId='$pId'");
	}
	$db->commit();
	$db->autocommit(TRUE);
	return $result;
}

function db_points_add($tId,$points)
{
	$db = new MySQLHelper();
	$db->autocommit(FALSE);
	$row = $db->executeSQL("SELECT points,PId FROM profile,tag WHERE tag.TSerieNo='$tId' AND tag.profileId=profile.PId")->fetch_assoc();
	//$currentPoints = $row['points']+$points;
	$pId = $row['PId'];
	$result = addPointsToProfile($pId, $points);//From lisbeth use addPointsToProfile($profileId, $points) in DBfunction it takes max point into account
	//$db->executeSQL("UPDATE profile SET points='$currentPoints' WHERE PId='$pId'"); //need to change to take point ceiling into account
	
	$db->commit();
	$db->autocommit(TRUE);
	return $result;
}

?>