<?php
		$server= 'localhost';
		$username = 'root';
		$password = 'pcontrol';
		$database = 'smartparentalcontrol';
		
		//tables
		$theTables = array( 'Action' => 'Action',
							'Chores'=>'Chores',
							'Cond_timeperiod' =>'Cond_timeperiod',
							'Cond_timestamp' => 'Cond_timestamp',
							'Control_system' => 'Control_system',
							'Controller' => 'Controller',
							'Controller_used_by_tag' => 'Controller_used_by_tag',
							'Profile' => 'Profile',
							'Profile_did_chores' => 'Profile_did_chores',
							'Profile_has_rules' => 'Profile_has_rules',
							'Rcondition' => 'Rcondition',
							'Rules' => 'Rules',
							'Tag' =>'Tag'
							);
		
		$theColumns = array(
						'Action' => array('AId', 'RId',	'name', 'points','controllerId'),
						'Chores' =>array('CId', 'CSId', 'name', 'description', 'defaultPoints'), 
						'Cond_timeperiod' => array('condTimepId','condId','PerId','timeFrom','timeTo','weekdays','weekly','ndWeekly','rdWeekly','firstInMonth','lastInMonth','weekNumber'),
						'Cond_timestamp' => array('condTimesId','condId','timestamp'),
						'Control_system' =>array('CSId', 'username', 'password', 'email', 'phoneNo'), 
						'Controller' =>array('CSerieNo','CSId', 'name' ,'location', 'status' ),
						'Controller_used_by_tag' =>array('TSerieNo', 'CSerieNo', 'starttime', 'endtime'),
						'Profile' =>array('PId', 'CSId', 'name', 'points'),
						'Profile_did_chores' =>array('PId', 'CId', 'actualPoints', 'timeOfCreation'),
						'Profile_has_rules' =>array('PId', 'RId', 'validFromTime'),
						'Rcondition' => array('condId','RId','name','controllerId'),
						'Rules' =>array('RId','CSId', 'name', 'profileId',  'isPermission'),
						'Tag' =>array('TSerieNo','CSId', 'profileId', 'name', 'active')
		);
		$actionNames= array('Block user', 'Activate user', 'Add points',  
							'Set limit of point', 'Unlimited points',
							'Access unlimited',	//permission all
							'Access retracted',	//permission non
							'Access controller',	// permissionDiv
							'Turn On controller', 'Turn Off controller');
							
		$conditionNames = array('time',	'controller on', 'controller off',
								'Timeperiode', 'true',	'false');
								
		
		


?>