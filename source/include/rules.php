<?php
	function setWeekdays($postData) {
		$weekdays  = (isset($postData['repeatMon']) ? 'monday,' 	: '');
		$weekdays .= (isset($postData['repeatTue']) ? 'tuesday,' 	: '');
		$weekdays .= (isset($postData['repeatWed']) ? 'wednesday,' 	: '');
		$weekdays .= (isset($postData['repeatThu']) ? 'thursday,' 	: '');
		$weekdays .= (isset($postData['repeatFri']) ? 'friday,' 	: '');
		$weekdays .= (isset($postData['repeatSat']) ? 'saturday,' 	: '');
		$weekdays .= (isset($postData['repeatSun']) ? 'sunday,' 	: '');
		return $weekdays;
	}
	function setArrayOfRestAttributes($postData, $weekdays, $startTime, $endTime) {
		return array('timeFrom'	=> $startTime,
				'timeTo'		=> $endTime,
				'weekdays'		=> $weekdays,
				'weekly'		=> ($postData['repeatEach'] == 'eachWekk' 	? 1 : 0),
				'ndWeekly'		=> ($postData['repeatEach'] == 'biWeekly' 	? 1 : 0),
				'rdWeekly'		=> ($postData['repeatEach'] == 'triWeekly'	? 1 : 0),
				'firstInMonth'	=> ($postData['repeatEach'] == 'primo' 		? 1 : 0),
				'lastInMonth'	=> ($postData['repeatEach'] == 'ultimo' 	? 1 : 0));
	}
	function setCondition($postData) {
		switch ($postData['actionName']) {
			case 'Block user':{ 					
				$conditionType = "Timeperiod";
				switch ($postData['repeatEach']) {
					case 'eachWekk': 	
					case 'biWeekly': 	
					case 'triWeeklys': 	{
						$weekdays  = setWeekdays($postData);
						$startTime = $postData['startDate'].' '.$postData['startTime'];
						$endTime   = $postData['endDate']  .' '.$postData['endTime']  ;
					}; break;
					case 'primo': 		
					case 'ultimo': 		{
						$weekdays  = $postData['specDay'];
						$startTime = $postData['startDate'].' '.$postData['startTime'];
						$endTime   = $postData['endDate']  .' '.$postData['endTime']  ;
					}; break;
					case 'sWeek': 		{
						$weekdays  = setWeekdays($postData);
						$startTime = new DateTime('NOW');
						$endTime = new DateTime('NOW');
					}; break;
					case 'noRepeat': 	{
						$weekdays  = setWeekdays($postData);
						$startTime = $postData['timeSPCTHidden'];
						$endTime   = $postData['timeSPCTHidden'];
						$conditionType = "True";
					}; break;
				}
			}; break;
			case 'Add points':
			case 'Delete points':{ 					
				$conditionType = "Timeperiod";
				switch ($postData['repeatEach']) {
					case 'eachWekk': 	
					case 'biWeekly': 	
					case 'triWeeklys': 	{
						$weekdays  = setWeekdays($postData);
						$startTime = $postData['startDate'].' '.$postData['timeATHidden'];
						$endTime   = $postData['endDate']  .' '.$postData['timeATHidden']  ;
					}; break;
					case 'primo': 		
					case 'ultimo': 		{
						$weekdays  = $postData['specDay'];
						$startTime = $postData['startDate'].' '.$postData['timeATHidden'];
						$endTime   = $postData['endDate']  .' '.$postData['timeATHidden']  ;
					}; break;
					case 'sWeek': 		{
						$weekdays  = setWeekdays($postData);
						$startTime = new DateTime('NOW');
						$endTime = new DateTime('NOW');
					}; break;
				}
			}; break;
			case 'Set maximum of point':{
				$weekdays  = "";
				$conditionType = "Timeperiod";
				$startTime = $postData['timeSPCTHidden'];
				$endTime   = $postData['timeSPCTHidden'];
			}; break;
			case 'Unlimited time':
			case 'Access any controller':
			case 'Cannot access any controller':
			case 'Access controller':
			case 'Cannot access controller':{
				$conditionType = "Timeperiod";
				switch ($postData['repeatEach']) {
					case 'eachWekk': 	
					case 'biWeekly': 	
					case 'triWeeklys': 	{
						$weekdays  = setWeekdays($postData);
						$startTime = $postData['startDate'].' '.$postData['startTime'];
						$endTime   = $postData['endDate']  .' '.$postData['endTime']  ;
					}; break;
					case 'primo': 		
					case 'ultimo': 		{
						$weekdays  = $postData['specDay'];
						$startTime = $postData['startDate'].' '.$postData['startTime'];
						$endTime   = $postData['endDate']  .' '.$postData['endTime']  ;
					}; break;
					case 'sWeek': 		{
						$weekdays  = setWeekdays($postData);
						$startTime = new DateTime('NOW');
						$endTime = new DateTime('NOW');
					}; break;
					case 'noRepeat': 	{
						$weekdays  = setWeekdays($postData);
						$startTime = $postData['startDate'].' '.$postData['startTime'];
						$endTime   = $postData['endDate']  .' '.$postData['endTime']  ;
						if ($startTime == $endTime) {$conditionType = "True";}
					}; break;
				}
			}; break;
			case 'Access controller if':
			case 'Cannot access controller if':{
				$conditionType = $postData['controllerStatus'];
				$weekdays  = "";
				$startTime = new DateTime('NOW');
				$endTime = new DateTime('NOW');
			}; break;
			default: break;
		}
			// Set condition
			$arrayOfRestAttributes = setArrayOfRestAttributes($postData, $weekdays, $startTime, $endTime);
			if (isset($postData['controllerName'])) {$controllerName = $postData['controllerName'];} else {$controllerName = null;}
			$nCondition = new Condition(0 , $conditionType, null, $controllerName, $arrayOfRestAttributes);
			$arrayCondition = array('cond' => $nCondition);
		return  $arrayCondition;
	}

?>