<?
require_once('db.php');

function db_points_remove($tId,$points)
{
	$db->autocommit(FALSE);
	$row = $db->query("SELECT points,PId FROM profile,tag WHERE tag.TSerieNo='$tId' AND tag.profileId=profile.PId")->fetch_assoc();
	$pId = $row['PId'];
	$currentPoints = $row['points']-$points;
	if ($currentPoints < 0) 
	{
		//something wrong, this should not happen!
	}
	else
	{
		$db->query("UPDATE profile SET points='$currentPoints' WHERE PId='$pId'");
	}
	$db->commit();
	$db->autocommit(TRUE);
}

function db_points_add($tId,$points)
{
	$db->autocommit(FALSE);
	$row = $db->query("SELECT points,PId FROM profile,tag WHERE tag.TSerieNo='$tId' AND tag.profileId=profile.PId")->fetch_assoc();
	$currentPoints = $row['points']+$points;
	$pId = $row['PId'];
	$db->query("UPDATE profile SET points='$currentPoints' WHERE PId='$pId'"); //need to change to take point ceiling into account
	$db->commit();
	$db->autocommit(TRUE);
}

?>