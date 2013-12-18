<?php

	if (isset($_GET['chart'])) {$chart = $_GET['chart'];} else {exit();}
	if (isset($_GET['PId'])) {$PId = $_GET['PId'];} else {exit();}
	//DB includes
	include_once "../database/DBfunctions.php";
	switch ($chart) {
		case 'useStatistics': {
			$chartData = getUsageByPId($PId, 'useStatistics');
			foreach ($chartData as $row) {
				$data = array('label' => $row['name'], 'value' => $row['percentage'] );
				$printData[] = $data;
			}
		} break;
		case 'systemUsage': {
			$chartData = getUsageByPId($PId, 'systemUsage');
			foreach ($chartData as $row) {
				$data = array('label' => $row['name'], 'value' => $row['percentage'] );
				$printData[] = $data;
			}
		} break;
		case 'totalPoints': {
			$chartData = getUsageByPId($PId, 'totalPoints');
			foreach ($chartData as $row) {
				$data = array('d' => $row['date'], 'point' => $row['point'] );
				$printData[] = $data;
			}
		} break;
		case 'userOverview': {
			$chartData = getUsageByPId($PId, 'userOverview');
			foreach ($chartData as $row) {
				$data = array('label' => $row['name'], 'point' => $row['point'] );
				$printData[] = $data;
			}
		} break;
		case 'pointPrDay': {
			$chartData = getUsageByPId($PId, 'pointPrDay');
			foreach ($chartData as $row) {
				$data = array('day' => $row['day'], 'point' => $row['point'] );
				$printData[] = $data;
			}
		} break;
		
		default:
			# code...
			break;
	}

echo json_encode($printData);

?>