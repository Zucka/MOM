<?php
echo json_encode(""); die();
require_once '../database/DBfunctions.php';
	if (isset($_GET['start'])) {$start = $_GET['start'];} else {exit();}
	if (isset($_GET['end'])) {$end = $_GET['end'];} else {exit();}
	if (isset($_GET['uid'])) {$uid = $_GET['uid'];} else {exit();}

	$startTime = new DateTime();
	$endTime = new DateTime();
	$startTime->setTimestamp($start); 	// Hate not able to direct input unix timestamp to datetime!
	$endTime->setTimestamp($end);		// Hate not able to direct input unix timestamp to datetime!
	$events = getTeams($uid, $startTime, $endTime, $city);

	if ($events == null) {echo json_encode(""); die();}
foreach ($events as $row) {
	$timeNow   = new DateTime('now');
	$startTime = new DateTime($row['start']);
	$endTime   = new DateTime($row['end']);
	$description  = ($_SESSION['language'] == 'en') ? 'Trainer: ' : 'Træner: ';
	$description .= $row['trainerName'];
	if ($timeNow  > $startTime) {$backgroundColor = "#CACACA";} // Old event
	elseif ($row['cancelled']) {
		$backgroundColor = "#AAAAAA";
		$description .= "<br />".($_SESSION['language'] == 'en' ? 'Cancelled' : 'Aflyst');
	}
	else {
		$description .= "<br />".($_SESSION['language'] == 'en' ? 'Availeble spots: ' : 'Ledige pladser: ').$room;
	}
	$eventRow = array(
					'id' 			=> $row['id'],
					'title' 		=> ($_SESSION['language'] == 'en' ? $row['titleEN'] : $row['title']),
					'start' 		=> $startTime->format('Y-m-d H:i:s'),
					'startTime' 	=> $startTime->format('H:i'),
					'end' 			=> $endTime->format('Y-m-d H:i:s'),
					'endTime' 		=> $endTime->format('H:i'),
					'url' 			=> "?p=team&teamID=".$row['id'],
					'color' 		=> $backgroundColor,
					'description' 	=> $description
				);
	$rows[] = $eventRow;
}

echo json_encode($rows);
?>
