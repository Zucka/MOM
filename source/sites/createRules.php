<?php
	require_once 'include/rules.php';
	if (isset($_GET['action'])) {$action = $_GET['action'];} else {$action = '';}

	if ($action == "create") {
		// print_r($_POST); // DEVELOPER 
		if (isset($_POST['actionName']) &&
			isset($_POST['name']) &&
			isset($_POST['systemUserSelect']) &&
			isset($_POST['repeatEach'])) { //PROBLEM WITH "Access Controller if"
			// Set rule name & control system
			$nRule = new Rules($_SESSION['CSid'], $_POST['name']);
			// Set action 
			if (isset($_POST['amountOfPoints'])) {$amountOfPoints = $_POST['amountOfPoints'];} else {$amountOfPoints = null;}
			if (isset($_POST['controllerName'])) {$controllerName = $_POST['controllerName'];} else {$controllerName = null;}
			if ($_POST['actionName'] == 'accessIf') {
				$actionName = 'Access controller';
			} elseif ($_POST['actionName'] == 'noAccessIf' ) {
				$actionName = 'Cannot access controller';
			} else {$actionName = $_POST['actionName'];}
			$nAction = new Action( 0, $actionName, null, $controllerName, $amountOfPoints );
			$arrayAction = array('cond' => $nAction);
			// Set condition 
			$arrayCondition = setCondition($_POST);
			// print_r($arrayCondition);
			$dbResult = addNewRuleToDB($nRule, $arrayCondition, $arrayAction);
			if (is_numeric($dbResult)) {
				addRuleToProfile($_POST['systemUserSelect'] ,$dbResult);
				echo '<script type="text/javascript">alert("Rule added");</script>';
			} else {echo '<script type="text/javascript">alert("Something went wrong..");</script>';}
			// print_r($dbResult);
					
		} else {echo '<script type="text/javascript">alert("You are missing something..");</script>';}
	} 
?>
<!DOCTYPE html>
<head>
	<title>Create Rule</title>
	<!-- DateTimePicker -->
	<link href="assets/css/bootstrap-datetimepicker.min.2.css" rel="stylesheet">

<!-- Our CSS -->
<script src="assets/js/moment.js"></script>
<script src="assets/js/locales/bootstrap-datetimepicker.da.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.2.js"></script>
<script src="assets/js/addRules.js"></script>
</head>
<body>
	<div class="container">
		<h1>Create a new Rule</h1>
		</br>
		<form class="form-createRulesController form-horizontal" role="form" id="createRulesController" action="?page=rulesAdd&action=create" method="post">
			<div class="form-group">
				<label for="actionName" class="col-sm-3 control-label">Action: </label>
				<div class="col-sm-8">
					<select name="actionName" id="actionName" class="form-control actionName" required autofocus onchange="actionNameSelect();">
						<option value="def" disabled selected>Please select an action...</option>
					  	<?php foreach($actionNames as $name) {print ('<option '.($name == "Activate user" ? 'disabled':'').'>'.$name.'</option>
					  	'); }?>
						<option value="accessIf"  >Access controller if</option>
						<option value="noAccessIf">Cannot access controller if</option>
					</select>
				</div>
			</div>
			<div class="form-group"  id="conditionNameSelect" style="display:none;">
				<label for="condNameSelect" class="col-sm-3 control-label">Condition: </label>
				<div class="col-sm-8">
					<select name="condNameSelect" id="condNameSelect" class="form-control condNameSelect" autofocus onchange="conditionSelect();">
						<option value="def" disabled selected>Please select a condition...</option>
					  	<?php foreach($conditionNames as $name) {print ('<option>'.$name.'</option>
					  	'); }?>
					</select>
				</div>
			</div>
			<div class="form-group" id="ruleName" style="display:none;">
				<label for="name" class="col-sm-3 control-label">Rule Name: </label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="name" id="name" placeholder="Name..." required >
				</div>
			</div>
			<div class="form-group" id="systemUser" style="display:none;">
				<label for="systemUserSelect" class="col-sm-3 control-label">User: </label>
				<div class="col-sm-8">
					<select name="systemUserSelect" id="systemUserSelect" class="form-control">
					  	<option disabled selected >Please select a user...</option>
					  	<?php foreach(profilesByCSId($_SESSION['CSid']) as $row) {print ('<option value="'.$row['PId'].'">'.$row['name'].'</option>
					  	');} ?>
					</select>
				</div>
			</div>
			<div class="form-group" id="controllerName" style="display:none;">
				<label for="controllerName" class="col-sm-3 control-label">Controller Name: </label>
				<div class="col-sm-8">
					<select name="controllerName" id="controllerName" class="form-control">
					  	<option value="def" disabled selected >Please select a controller...</option>
					  	<?php foreach(controllersByCSId($_SESSION['CSid']) as $row) {print ('<option value="'.$row['CSerieNo'].'">'.$row['name'].'</option>
					  	');} ?>
					</select>
				</div>
			</div>
			<div class="form-group" id="controllerNameIf" style="display:none;">
				<label for="controllerNameIf" class="col-sm-3 control-label">Can be turned on if this controller: </label>
				<div class="col-sm-8">
					<select name="controllerNameIf" id="controllerNameIf" class="form-control">
					  	<option value="def" disabled selected >Please select a controller...</option>
					  	<?php foreach(controllersByCSId($_SESSION['CSid']) as $row) {print ('<option value="'.$row['CSerieNo'].'">'.$row['name'].'</option>
					  	');} ?>
					</select>
				</div>
				<label for="controllerStatus" class="col-sm-3 control-label">Is: </label>
				<div class="col-sm-8">
					<select name="controllerStatus" id="controllerStatus" class="form-control"  onchange="repeatWeeklySelect();" >
						<option value="Controller on">Turned On</option>
						<option value="Controller off">Turned Off</option>
					</select>
				</div>
			</div>
			<div class="form-group" id="amountOfPoints" style="display:none;">
				<label for="amountOfPoints" class="col-sm-3 control-label">Amount of Points: </label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="amountOfPoints" id="amountOfPoints" placeholder="Points..."  >
				</div>
			</div>
			<div class="form-group" id="repeatEach" style="display:none;">
				<label for="repeatEach" class="col-sm-3 control-label">Rule Applies: </label>
				<div class="col-sm-8">
					<select name="repeatEach" id="repeatEach" class="form-control"  onchange="repeatWeeklySelect();" >
						<option value="eachWekk"	>Every Week</option>
						<option value="biWeekly"	>Every two Weeks</option>
						<option value="triWeekly"	>Every three Weeks</option>
						<option value="primo"		>First in a month</option>
						<option value="ultimo"		>Last in a month</option> 
						<option value="sWeek"		>Specific Week No.</option> 
						<option value="noRepeat"	>Once / from</option>
					</select>
				</div>
			</div>
			<div class="form-group" id="selectWeekNo" style="display:none;" disabled>
				<label for="weekNo" class="col-sm-3 control-label">Week No: </label>
				<div class="col-sm-8">
					<select name="weekNo" id="weekNo" class="form-control">
						<option value="def" selected disabled>Please select a week...</option>
						<?php for ($x=1; $x <= 52; $x++): print ('<option value="'.$x.'">'.$x.'</option>
						'); endfor; ?>
					</select>
				</div>
			</div>
			<div class="form-group" id="SpecificTime" style="display:none;">
				<label for="timeSPCTHidden" class="col-sm-3 control-label">Rule active on/from the: </label>
				<div class="input-group date formSpecificTime col-sm-8">
					<input class="form-control" type="text" value=""  readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				</div>
				<input type="hidden" name="timeSPCTHidden" id="timeSPCTHidden" />
			</div>
			<div class="form-group" id="repeatBetween" style="display:none;">
				<label for="startRepeatOn" class="col-sm-3 control-label">Rule active from date: </label>
				<div class="input-group date formStartRepeatOn col-sm-3">
					<input class="form-control" type="text" value=""  readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
				</div>
				<input type="hidden" name="startDate" id="startRepeatOn" />
				<label for="endRepeatOn" class="col-sm-2 control-label">To date: </label>
				<div class="input-group date formEndRepeatOn col-sm-3">
					<input class="form-control" type="text" value=""  readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
				</div>
				<input type="hidden" name="endDate" id="endRepeatOn" value="" />
			</div>
			<div class="form-group" id="ATTime" style="display:none;">
				<label for="timeATHidden" class="col-sm-3 control-label">Rule applies at: </label>
				<div class="input-group date formATTime col-sm-8">
					<input class="form-control" type="text" value=""  readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				</div>
				<input type="hidden" name="timeATHidden" id="timeATHidden" />
			</div>
			<div class="form-group" id="betweenTime" style="display:none;">
				<label for="startTime" class="col-sm-3 control-label">Rule active from time: </label>
				<div class="input-group date formStartTime col-sm-3">
					<input class="form-control" type="text" value=""  readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				</div>
				<input type="hidden" name="startTime" id="startTime" value="" />
				<label for="endTime" class="col-sm-2 control-label">To time: </label>
				<div class="input-group date formEndTime col-sm-3">
					<input class="form-control" type="text" value=""  readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				</div>
				<input type="hidden" name="endTime" id="endTime" value="" />
			</div>
 			<div class="form-group" id="repeatDays" style="display:none;">
				<label for="repeat" class="col-sm-3 control-label">Active on: </label>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" value="1" id="repeatMon" name="repeatMon">Monday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" value="1" id="repeatTue" name="repeatTue">Tueday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" value="1" id="repeatWed" name="repeatWed">Wednesday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" value="1" id="repeatThu" name="repeatThu">Thursday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" value="1" id="repeatFri" name="repeatFri">Friday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" value="1" id="repeatSat" name="repeatSat">Saturday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" value="1" id="repeatSun" name="repeatSun">Sunday</label></div>
			</div>
 			<div class="form-group" id="specificDay" style="display:none;">
				<label for="repeat" class="col-sm-3 control-label">Active on: </label>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="radio" name="specDay">Monday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="radio" name="specDay">Tueday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="radio" name="specDay">Wednesday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="radio" name="specDay">Thursday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="radio" name="specDay">Friday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="radio" name="specDay">Saturday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="radio" name="specDay">Sunday</label></div>
			</div>
			</br>
			<center>
				<button class="btn btn-lg btn-primary" id="submitBtn" style="display:none;" type="submit">Add Rule</button>
			</center>
		</form>
	</div> <!-- /container -->
</body>
</html>