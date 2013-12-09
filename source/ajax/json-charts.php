<?php

	if (isset($_GET['chart'])) {$chart = $_GET['chart'];} else {exit();}

	switch ($chart) {
		case 'pointPrDay':
			$chartData = array ( array( 'device' => 'Monday'    , 'geekbench' => 203),
								array ( 'device' => 'Tuesday' , 'geekbench' => 137),
								array ( 'device' => 'Wednesday', 'geekbench' => 275),
								array ( 'device' => 'Thursday'  , 'geekbench' => 380),
								array ( 'device' => 'Friday' , 'geekbench' => 655),
								array ( 'device' => 'Saturday' , 'geekbench' => 831),
								array ( 'device' => 'Sunday'  , 'geekbench' => 942));
			break;
		
		default:
			# code...
			break;
	}
echo json_encode($chartData);

?>