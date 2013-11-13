<?php
		$server= "localhost";
		$username = "root";
		$password = "pcontrol";
		$database = "smartparentcontrol";
		
		//tables
		$theTables = array("chores","cond_device_on_off","cond_timeperiod"
							"cond_timestamp","control_system","device","device_used_by_tag",
							"permission" ,"profile","profile_did_chores","profile_has_rules",
							"rcondition","rules","tag"
							);
		
		$theColumns = array(
						"chores" =>array("CId", "name", "description", "defaultPoints", "CSId"), 
						"cond_device_on_off" => array("condDevId","condId", "deviceId"),
						"cond_timeperiod" => array("condTimepId","condId","PerId","timeFrom","timeTo","weekdays","weekly","ndWeekly","rdWeekly","firstInMonth","lastInMonth","weekNumber"),
						"cond_timestamp" => array("condTimesId","condId","timestamp"),
						"control_system" =>array("CSId", "email", "username", "password"), 
						"device" =>array("DSerieNo", "name", "location", "status", "CSId" ),
						"device_used_by_tag" =>array("TSerieNo", "DSerieNo", "starttime", "endtime"),
						"permission" => array("PerId","name","CSId","profileId"),
						"profile" =>array("PId", "name", "points", "mobil_number", "CSId"),
						"profile_did_chores" =>array("PId", "CId", "actualPoints", "timeOfCreation"),
						"profile_has_rules" =>array("PId", "RId", "validFromTime"),
						"rcondition" => array("condId","name"),
						"rules" =>array("RId", "name", "profileId", "CSId"),
						"tag" =>array("TSerieNo", "name", "active", "profileId", "CSId")
		);
		$actionNames= array("Block user", "Activate user", "Add points",  
							"Set limit of point", "Unlimited points",
							"Access unlimited",	//permission all
							"Access retracted",	//permission non
							"Access device",	// permissionDiv
							"Turn On device", "Turn Off device");
							
		$conditionNames = array("time",	"Device on", "Device off",
								"Timeperiode", "true",	"false");
		


?>