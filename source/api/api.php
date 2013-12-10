<?php
error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/sqlHelper.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/db_device.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/db_points.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/db_rules.php');
require 'vendor/autoload.php';
$app = new \Slim\Slim();


header('Content-Type: application/json'); //set header to json
$app->get('/', function() {
    echo '{}'; //return empty json
});

//Get status for a device
//Inputs:
//	dId = Id of the device wanting status
$app->get('/status/:cId', function($cId) {
	$db = new MySQLHelper();
	$cId = $db->real_escape_string($cId);
    $row = $db->executeSQL("SELECT status,cost FROM controller WHERE CSerieNo='$cId' LIMIT 1")->fetch_assoc(); //change to get cost from controller
    $status = $row['status'];
    $cost = $row['cost'];
    $action = 'none';
    //Check rules if controller should shut off
    if (db_rules_device_should_turn_off($cId))
    {
    	$action = 'RED';
    }

    //encode json and print it
    $data = array('status' => $status, 'action' => $action); 

    //get data for later
    $row2 = $db->executeSQL("SELECT points,UNIX_TIMESTAMP(controller_used_by_tag.starttime) as starttime,UNIX_TIMESTAMP(now()) as now,profile.PId as PId FROM profile,tag,controller_used_by_tag WHERE controller_used_by_tag.CSerieNo='$cId' AND controller_used_by_tag.endtime IS NULL AND controller_used_by_tag.TSerieNo=tag.TSerieNo AND tag.profileId=profile.PId LIMIT 1")->fetch_assoc();
    
    if ($row2->numrows > 0)
    {
	    //check rules if user has unlimited points
	    if (db_rules_user_has_unlimited_points($row2['PId']))
	    {
	    	$data['timeRemaining'] = 60; //static 60 minutes from unlimited points
	    }
	    else
	    {
	    	//calculate timeRemaining and return it
		    $points = $row2['points'];
		    $timeNow = $row2['now'];
		    $startTime = $row2['starttime'];
		    $timeElapsed = floor(($timeNow-$startTime)/60);
		    $pointsRemaining = $points-($timeElapsed*$cost);
		    $data['timeRemaining'] = strval($pointsRemaining/$cost);
	    }
	}
    echo json_encode($data);
});

//Request to turn on a device
//Inputs:
//	dId = Id of the device wanting to turn on
//	uId = Id of the user wanting to turn on the device
$app->get('/turnOn/:cId/:tId', function($cId,$tId) {
	$db = new MySQLHelper();
	$dId = $db->real_escape_string($cId);
	$uId = $db->real_escape_string($tId);
	if (!db_device_verify_cId($cId,$tId) || !db_rules_user_can_turn_device_on($cId,$tId))
	{
		return;
	}
	$row = $db->executeSQL("SELECT status,cost FROM controller WHERE controller.CSerieNo='$cId' LIMIT 1")->fetch_assoc();
	$cost = $row['cost']; //cost is points per minute

	$row2 = $db->executeSQL("SELECT points,active FROM profile,tag WHERE tag.TSerieNo='$tId' AND tag.profileId=profile.PId LIMIT 1")->fetch_assoc();
	if ($row2['active'] == 0)
	{
		return;
	}
	$points = $row2['points'];
	$timeRemaining = $cost > 0 ? $points/$cost : 0; //time remaining in minutes, check for cost > 0 so that we don't devide by zero
	$status = '';
	$error = '';
	switch ($row['status']) {
		case 'RED': //device is currently off and is able to be turned on
			if (floor($timeRemaining) > 0)
			{
				$status = 'OK';
			}
			else
			{
				$status = 'ERROR';
				$error = 'Not enough points on user account to turn on';
			}
			break;
		case 'GREEN': //device is currently on
			$status = 'ERROR';
			$error = 'Can not turn on a device that is already on!';
			break;
		case '!': //something is preventing the device turning on(e.g. a rule is in effect that prevents the device from turning on)
			$status = 'ERROR';
			$error = 'Something is wrong with the controller?';
			break;
		default:
			$status = 'ERROR';
			$error = 'Status value not recognized, something is very wrong!';
			break;
	}
	$data = array('status' => $status);
	if ($status == 'OK')
	{
		db_device_turn_on($dId,$uId); //update db to indicate device is on
		$data['timeRemaining'] = strval($timeRemaining);
	}
	else
	{
		$data['error'] = $error;
	}
	echo json_encode($data);
});

//Request to turn off a device
//Inputs:
//	dId = Id of the device to turn off
//	uId = Id of the user that want's to turn off device (might not be needed? have that info already?)
$app->get('/turnOff/:cId/:tId', function($cId,$tId) {
	$db = new MySQLHelper();
	$dId = $db->real_escape_string($cId);
	$uId = $db->real_escape_string($tId);
	if (!db_device_verify_cId($cId,$tId))
	{
		return;
	}

	$row = $db->executeSQL("SELECT controller.status,controller_used_by_tag.TSerieNo,controller.cost FROM controller,controller_used_by_tag WHERE controller_used_by_tag.CSerieNo=controller.CSerieNo AND controller.CSerieNo='$cId' AND controller_used_by_tag.endtime IS NULL LIMIT 1")->fetch_assoc(); //time is when the device was turned on, user is who turned the device on
	$status = '';
	$error = '';
	switch ($row['status']) {
		case 'RED': //device is currently off and is able to be turned on
			$status = 'ERROR';
			$error = 'Device is already off!';
			break;
		case 'GREEN': //device is currently on
			if ($tId == $row['TSerieNo']) //same user has to turn the device off
			{
				$status = 'OK';
			}
			else
			{
				$status = 'ERROR';
				$error = 'Same user has to turn device off, or another user should call turnOn!';
			}
			break;
		case '!': //something is preventing the device turning on, but the device is off!
			$status = 'ERROR';
			$error = 'Something is wrong with the controller';
			break;
		default:
			$status = 'ERROR';
			$error = 'Status value not recognized, something is very wrong!';
			break;
	}
	$cost = $row['cost'];
	$data = array('status' => $status);
	if ($status == 'OK')
	{
		$timeSpent = db_device_turn_off($cId,$tId); //turn off device and get time spent
		$points = $timeSpent*$cost;
		db_points_remove($tId,$points);
		$data['timeSpent'] = strval($timeSpent); //return time spent?
		$data['pointsRemoved'] = strval($points);
	}
	else
	{
		$data['error'] = $error;
	}
	echo json_encode($data);
});

$app->get('/test/:cId/:tId', function($cId,$tId) {
	$data = db_rules_user_can_turn_device_on($cId,$tId);
	echo json_encode($data);
});

$app->run();
?>