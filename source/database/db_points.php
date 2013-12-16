<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/sqlHelper.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/DBfunctions.php');
function db_points_remove($id,$points,$isPId = false)
{
	
	$db = new MySQLHelper();
	$db->autocommit(FALSE);
	if(!$isPId){
		$row = $db->executeSQL("SELECT points,PId FROM profile,tag WHERE tag.TSerieNo='$id' AND tag.profileId=profile.PId")->fetch_assoc();
		$pId = $row['PId'];
		$currentPoints = $row['points']-$points;
	}
	else{
		$row = $db->executeSQL("SELECT points FROM profile WHERE PId = '$id'")->fetch_assoc();
		$pId = $id;
		$currentPoints = $row['points']-$points;
	}
	if ($currentPoints < 0) 
	{
		//something wrong, this should not happen!
		$result = "ERROR_To_Many_Points";
	}
	else
	{
		$result = $db->executeSQL("UPDATE profile SET points='$currentPoints' WHERE PId='$pId'");
	}
	$db->commit();
	$db->autocommit(TRUE);
	return $result;
}

function db_points_add($id,$points,$isPid = false)
{
	$db = new MySQLHelper();
	$db->autocommit(FALSE);
	if(!$isPid){
		$row = $db->executeSQL("SELECT points,PId FROM profile,tag WHERE tag.TSerieNo='$id' AND tag.profileId=profile.PId")->fetch_assoc();
		//$currentPoints = $row['points']+$points;
		$pId = $row['PId'];
	}
	else{
		$pId = $id;
	}
	$result = addPointsToProfile($pId, $points);//From lisbeth use addPointsToProfile($profileId, $points) in DBfunction it takes max point into account
	//$db->executeSQL("UPDATE profile SET points='$currentPoints' WHERE PId='$pId'"); //need to change to take point ceiling into account
	
	$db->commit();
	$db->autocommit(TRUE);
	return $result;
}

?>