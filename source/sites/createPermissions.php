<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	if (isset($_GET['action'])) {$action = $_GET['action'];} else {$action = '';}

	if ($action == "create") {
		// print_r($_POST); // DEVELOPER 
		if (isset($_POST['systemUserSelect']) && isset($_POST['controllerName']) ) {
			// Set rule name & control system
			$systemUser = explode(",", $_POST['systemUserSelect']);
			$userFirstName = explode(" ", $systemUser[1]);
			$controllerName = explode(",", $_POST['controllerName']);
			$ruleName = $userFirstName[0].' can use the '.$controllerName[1];
			$nRule = new Rules($_SESSION['CSid'], $ruleName, 1);
			// Set action 
			$nAction = new Action( 0, "Access controller", null, $controllerName[0], null );
			$arrayAction = array('cond' => $nAction);
			// Set condition 
			$nCondition = new Condition(0 , "True", null, null, array('timeFrom'	=> '""',
				'timeTo'		=> '""',
				'weekdays'		=> '',
				'weekly'		=> 0,
				'ndWeekly'		=> 0,
				'rdWeekly'		=> 0,
				'firstInMonth'	=> 0,
				'lastInMonth'	=> 0));
			$arrayCondition = array('cond' => $nCondition);
			// echo "</br>"; // DEVELOPER 
			// echo "</br>"; // DEVELOPER 
			// print_r($systemUser); // DEVELOPER 
			// echo "</br>"; // DEVELOPER 
			// print_r($userFirstName); // DEVELOPER 
			// echo "</br>"; // DEVELOPER 
			// print_r($controllerName); // DEVELOPER 
			// echo "</br>"; // DEVELOPER 
			// echo "</br>"; // DEVELOPER 
			// print_r($nRule); // DEVELOPER 
			// echo "</br>"; // DEVELOPER 
			// print_r($arrayCondition); // DEVELOPER 
			// echo "</br>"; // DEVELOPER 
			// print_r($arrayAction); // DEVELOPER 
			// echo "</br>"; // DEVELOPER 
			// Add rule
			$dbResult = addNewRuleToDB($nRule, $arrayCondition, $arrayAction);
			if (is_numeric($dbResult)) {
				addRuleToProfile($systemUser[0] ,$dbResult);
			}
			print_r($dbResult);
					
		} else {echo '<script type="text/javascript">alert("You are missing something..");</script>';}
	} 
?>
<!DOCTYPE html>
<head>
	<title>Create Permission</title>
</head>
<body>
	<div class="container">
		<h1>Add a new Permission</h1>
		</br>
		<form class="form-createPermission form-horizontal" role="form" id="createPermission" action="?page=permissionsAdd&action=create" method="post">
			<div class="form-group" id="systemUser">
				<label for="systemUserSelect" class="col-sm-3 control-label">User: </label>
				<div class="col-sm-8">
					<select name="systemUserSelect" id="systemUserSelect" class="form-control">
					  	<option disabled selected >Please select a user...</option>
					  	<?php foreach(profilesByCSId($_SESSION['CSid']) as $row) {print ('<option value="'.$row['PId'].','.$row['name'].'">'.$row['name'].'</option>
					  	');} ?>
					</select>
				</div>
			</div>
			<div class="form-group" id="controllerName">
				<label for="controllerName" class="col-sm-3 control-label">Can access: </label>
				<div class="col-sm-8">
					<select name="controllerName" id="controllerName" class="form-control">
					  	<option value="def" disabled selected >Please select a controller...</option>
					  	<?php foreach(controllersByCSId($_SESSION['CSid']) as $row) {print ('<option value="'.$row['CSerieNo'].','.$row['name'].'">'.$row['name'].'</option>
					  	');} ?>
					</select>
				</div>
			</div>
			</br>
			<center>
				<button class="btn btn-lg btn-primary" id="submitBtn" type="submit">Add Permission</button>
			</center>
		</form>
	</div> <!-- /container -->
</body>
</html>