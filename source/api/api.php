<?
require_once('db/db.php');
require 'vendor/autoload.php';

$app = new \Slim\Slim();


header('Content-Type: application/json'); //set header to json
$app->get('/', function() {
    echo '{}'; //return empty json
});

//Get status for a device
//Inputs:
//	dId = Id of the device wanting status
$app->get('/status/:dId', function($dId) {
	$dId = $db->real_escape_string($dId);
    $row = $db->query("SELECT * FROM devices WHERE id='$dId' LIMIT 1")->fetch_assoc();
    $data = array('status' => $row['status']);
    echo json_encode($data);
});

//Request to turn on a device
//Inputs:
//	dId = Id of the device wanting to turn on
//	uId = Id of the user wanting to turn on the device
$app->get('/turnOn/:dId/:uId', function($dId,$uId) {
	$dId = $db->real_escape_string($dId);
	$uId = $db->real_escape_string($uId);
	$row = $db->query("SELECT status,cost FROM devices WHERE id='$dId' LIMIT 1")->fetch_assoc();
	$cost = $row['cost']; //cost is points per minute

	$points = $db->query("SELECT points FROM users WHERE id='$uId'")->fetch_assoc()['points'];
	$timeRemaining = $points/$cost; //time remaining in minutes
	$status = '';
	$error = '';
	switch ($row['status']) {
		case 'value off': //device is currently off and is able to be turned on
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
		case 'value on': //device is currently on
			$status = 'ERROR';
			$error = 'Can not turn on a device that is already on!';
			break;
		case 'can\'t turn on': //something is preventing the device turning on(e.g. a rule is in effect that prevents the device from turning on)
			$status = 'ERROR';
			$error = 'Rule is preventing the device from turning on!';
			break;
		default:
			$status = 'ERROR';
			$error = 'Status value not recognized, something is very wrong!';
			break;
	}
	$data = array('status' => $status);
	if ($status == 'OK')
	{
		db_device_turn_on($dId,$uId) //update db to indicate device is on
		$data['timeRemaining'] = $timeRemaining;
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
$app->get('/turnOff/:dId/:uId', function($dId,$uId) {
	$dId = $db->real_escape_string($dId);
	$uId = $db->real_escape_string($uId);

	$row = $db->query("SELECT status,cost,time,user FROM devices WHERE id='$dId' LIMIT 1")->fetch_assoc(); //time is when the device was turned on, user is who turned the device on
	$status = '';
	$error = '';
	switch ($row['status']) {
		case 'value off': //device is currently off and is able to be turned on
			$status = 'ERROR';
			$error = 'Device is already off!';
			break;
		case 'value on': //device is currently on
			if ($uId == $row['user']) //same user has to turn the device off
			{
				$status = 'OK';
			}
			else
			{
				$status = 'ERROR';
				$error = 'Same user has to turn the device off again!';
			}
			break;
		case 'can\'t turn on': //something is preventing the device turning on, but the device is off!
			$status = 'ERROR';
			$error = 'Device is already off!';
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
		$timeSpent = db_device_turn_off($dId,$uId); //turn off device and get time spent
		$points = $timeSpent*$cost;
		db_points_remove($uId,$points);
		//$data['timeSpent'] = $timeSpent //return time spent?
	}
	else
	{
		$data['error'] = $error;
	}
	echo json_encode($data);
});

$app->run();
?>