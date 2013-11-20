<?
require_once('db.php')

function db_device_turn_on($cId,$tId) {
	$db->autocommit(false);
	$db->query("INSERT INTO controller_used_by_tag VALUES ('$tId','$cId',NOW(),)"); //time=time when device was turned on, user=the user who turned the device on
	$db->query("UPDATE controller SET status='GREEN' WHERE CSerieNo='$cId'");
	$db->commit();
	$db->autocommit(true);
}

function db_device_turn_off($cId,$tId) {
	$db->autocommit(false);
	$time = $db->query("SELECT UNIX_TIMESTAMP(starttime) FROM controller_used_by_tag WHERE CSerieNo='$cId' AND TSerieNo='$tId' AND endtime IS NULL")->fetch_assoc()['starttime'];
	$timeSpent = floor((time()-$time)/60);
	$db->query("UPDATE controller SET status='RED' WHERE CSerieNo='$cId'");
	$db->commit();
	$db->autocommit(true);

	return $timeSpent;
}
?>