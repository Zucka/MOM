<?
require_once('db.php');

function db_points_remove($uId,$points)
{
	$db->autocommit(FALSE);
	$currentPoints = $db->query("SELECT points FROM users WHERE id='$uId'")->fetch_assoc()['points'];
	$currentPoints = $currentPoints-$points;
	if ($currentPoints < 0) 
	{
		//something wrong, this should not happen!
	}
	else
	{
		$db->query("UPDATE users SET points='$currentPoints' WHERE id='$uId'");
	}
	$db->commit();
	$db->autocommit(TRUE);
}

function db_points_add($uId,$points)
{
	$db->autocommit(FALSE);
	$currentPoints = $db->query("SELECT points FROM users WHERE id='$uId'")->fetch_assoc()['points'];
	$currentPoints = $currentPoints+$points;
	$db->query("UPDATE users SET points='$currentPoints' WHERE id='$uId'"); //need to change to take point ceiling into account
	$db->commit();
	$db->autocommit(TRUE);
}

?>