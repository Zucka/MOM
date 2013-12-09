<?php
		/* $db_server= 'localhost';
		 $db_username = 'root';
		 $db_password = 'pcontrol';
		 $db_database = 'smartparentalcontrol';*/
		
		$db_server= 'blade3.s-et.aau.dk';
		$db_username = 'spc';
		$db_password = 'tts37ent';
		$db_database = 'smartparentalcontrol';	
		//tables
		$theTables = array( 'Action' => 'action',
							'Chores'=>'chore',
							'Cond_timeperiod' =>'cond_timeperiod',
							'Cond_timestamp' => 'cond_timestamp',
							'Control_system' => 'control_system',
							'Controller' => 'controller',
							'Controller_used_by_tag' => 'controller_used_by_tag',
							'Profile' => 'profile',
							'Profile_did_chores' => 'profile_did_chore',
							'Profile_has_rules' => 'profile_has_rule',
							'Rcondition' => 'rcondition',
							'Rules' => 'rule',
							'Tag' =>'tag'
							);
							

		
		$theColumns = array(
						'Action' => array('AId', 'RId',	'name', 'points','controllerId'),
						'Chores' =>array('CId', 'CSId', 'name', 'description', 'defaultPoints'), 
						'Cond_timeperiod' => array('condId','timeFrom','timeTo','weekdays','weekly','ndWeekly','rdWeekly','firstInMonth','lastInMonth','weekNumber'),
						'Cond_timestamp' => array('condId','onTimestamp'),
						'Control_system' =>array('CSId', 'name' , 'street', 'postcode', 'phoneNo'), 
						'Controller' =>array('CSerieNo','CSId', 'name' ,'location', 'status', 'cost' ),
						'Controller_used_by_tag' =>array('TSerieNo', 'CSerieNo', 'starttime', 'endtime'),
						'Profile' =>array('PId', 'CSId', 'name', 'points', 'username', 'password', 'email','phone', 'role'),
						'Profile_did_chores' =>array('PId', 'CId', 'actualPoints', 'timeOfCreation'),
						'Profile_has_rules' =>array('PId', 'RId', 'validFromTime'),
						'Rcondition' => array('condId','RId','name','controllerId'),
						'Rules' =>array('RId','CSId', 'name',  'isPermission'),
						'Tag' =>array('TSerieNo','CSId', 'profileId', 'name', 'active')
		);

		$actionNames= array('Block user', 'Activate user', 'Add points',  
							'Set maximum of point', 'Unlimited time',
							'Access any controller',	//permission all
							'Access retracted',	//permission non
							'Access controller',	// permissionDiv
							'Turn off');
							
		$conditionNames = array('Timestamp',	'Controller on', 'Controller off',
								'Timeperiode', 'True'
							);
								
		
		


?>