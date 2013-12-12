<?php

	if (isset($_GET['chart'])) {$chart = $_GET['chart'];} else {exit();}
	if (isset($_GET['PId'])) {$PId = $_GET['PId'];} else {exit();}
	//DB includes
	include_once "../database/DBfunctions.php";
	switch ($chart) {
		case 'useStatistics': {
			$chartData = getUsageByPId($PId, 'device');
			foreach ($chartData as $row) {
				$data = array('label' => $row['name'], 'value' => $row['percentage'] );
				$printData[] = $data;
			}
		}
			break;
		
		default:
			# code...
			break;
	}

echo json_encode($printData);

?>