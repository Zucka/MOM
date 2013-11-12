<?
require_once('db.php')

function db_device_turn_on($dId,$uId) {
	$time = time();
	$db->query("UPDATE devices SET status='value on',time='$time',user='$uId' WHERE id='$dId'"); //time=time when device was turned on, user=the user who turned the device on
}

function db_device_turn_off($dId,$uId) {
	$time = $db->query("SELECT time FROM devices WHERE id='$dId'")->fetch_assoc()['time'];
	$timeSpent = floor((time()-$time)/60);

	$db->query("UPDATE devices SET status='value off' WHERE id='$dId'");

	return $timeSpent;
}
?>