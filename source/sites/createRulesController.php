<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	if (isset($_GET['action'])) {$action = $_GET['action'];} else {$action = '';}

	if ($action == "create") {
		if (isset($_POST['actionName']) &&
			isset($_POST['name']) &&
			isset($_POST['repeatEach'])) {
			switch ($_POST['actionName']) {
				case 'Block user':
					break;
				case 'Activate user':
					break;
				case 'Add points':
					$nRule = new Rules($_SESSION['CSid'], $_POST['name']);
					$weekdays  = (isset($_POST['repeatMon']) ? 'monday,' 	: '');
					$weekdays .= (isset($_POST['repeatTue']) ? 'tuesday,' 	: '');
					$weekdays .= (isset($_POST['repeatWed']) ? 'wednesday,' 	: '');
					$weekdays .= (isset($_POST['repeatThu']) ? 'thursday,' 	: '');
					$weekdays .= (isset($_POST['repeatFri']) ? 'friday,' 	: '');
					$weekdays .= (isset($_POST['repeatSat']) ? 'saturday,' 	: '');
					$weekdays .= (isset($_POST['repeatSun']) ? 'sunday,' 	: '');
					$nCondition = new Condition(0 , "Timeperiod", null, null, array('timeFrom'		=> $_POST['startTime'],
																					'timeTo'		=> $_POST['endDate'],
																					'weekdays'		=> $weekdays,
																					'weekly'		=> ($_POST['repeatEach'] == 'eachWekk' 	? 1 : 0),
																					'ndWeekly'		=> ($_POST['repeatEach'] == 'biWeekly' 	? 1 : 0),
																					'rdWeekly'		=> ($_POST['repeatEach'] == 'triWeekly'	? 1 : 0),
																					'firstInMonth'	=> ($_POST['repeatEach'] == 'primo' 	? 1 : 0),
																					'lastInMonth'	=> ($_POST['repeatEach'] == 'ultimo' 	? 1 : 0),
																					'weekNumber'	=> ($_POST['repeatEach'] == 'sWeek' 	? 1 : 0)));
					$arrayCondition = array('cond' => $nCondition);
					if (is_numeric($_POST['amountOfPoints'])) {
						$nAction = new Action( 0, $_POST['actionName'], null, null, $_POST['amountOfPoints'] );
						$arrayAction = array('cond' => $nAction);
						print_r($nRule);
						echo "</br>";
						print_r($arrayCondition);
						echo "</br>";
						print_r($arrayAction);
						echo "</br>";
						$dbResult = addNewRuleToDB($nRule, $arrayCondition, $arrayAction);
						print_r($dbResult);
					} else { echo '<script type="text/javascript">alert("You are missing points..");</script>'; }
					break;
				case 'Set maximum of point':
					break;
				case 'Access any controller':
					break;
				case 'Cannot access any controller':
					break;
				case 'Access controller':
					break;
				case 'Cannot access controller':
					break;
				default: break;
			}		
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
<script type="text/javascript">
// function selectAction () {
// 	$( "select option:selected" ).each(function() {
// 		if ($( this ).attr("value") == "sWeek") {
// 			$("#selectWeekNo").css("display", "");
// 		}
// 	});
// }
// $( "#actionName" ).change(function() {
//   alert( "Handler for .change() called." );
// });
function repeatWeeklySelect () {
	$( "select option:selected" ).each(function() {
		if ($( this ).attr("value") == "sWeek") {
			$("#selectWeekNo").css("display", "");
		}
	});
}

	// $("#repeatEach").change(function() {
	// 	console.log("1");
	// 	$( "select option:selected" ).each(function() {
	// 	console.log("2");
	// 		if ($( this ).value() == "sWeek") {
	// 	console.log("3");
	// 			$("#selectWeekNo").css("display", "");
	// 		}
	// 	});
	// })

				// $('#teamNameEN').attr("value",data.name_en);
				// $('#description').val(data.description);
				// $('#descriptionEN').val(data.description_en);
				// $('.attendantsDiv').children('.form-control').find("option[value='"+data.maxAttendants+"']").attr("selected","selected");
				// $('.timeDiv').children('.form-control').find("option[value='"+data.defaultLength+"']").attr("selected","selected");
</script>
</head>
<body>
	<div class="container">
		<h1>Create a new Rule</h1>
		</br>
		<form class="form-createRulesController form-horizontal" role="form" id="createRulesController" action="?page=rulesControllerAdd&action=create" method="post">
			<div class="form-group">
				<label for="actionName" class="col-sm-3 control-label">Action: </label>
				<div class="col-sm-8">
					<select name="actionName" id="actionName" class="form-control" required autofocus onchange="selectAction();">
						<option selected>Please select an action...</option>
					  	<?php foreach($actionNames as $name) {print ('<option '.($name != "Add points" ? 'disabled':'').'>'.$name.'</option>'); }?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="controllerName" class="col-sm-3 control-label">Controller Name: </label>
				<div class="col-sm-8">
					<select name="controllerName" id="controllerName" class="form-control" disabled>
					  	<option disabled selected >Please select a controller...</option>
					  	<?php foreach(controllersByCSId($_SESSION['CSid']) as $row) {print ('<option value="'.$row['CSerieNo'].'">'.$row['name'].'</option>');} ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="name" class="col-sm-3 control-label">Rule Name: </label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="name" id="name" placeholder="Name..." required >
				</div>
			</div>
			<div class="form-group">
				<label for="amountOfPoints" class="col-sm-3 control-label">Amount of Points: </label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="amountOfPoints" id="amountOfPoints" placeholder="Points..." required >
				</div>
			</div>
			<div class="form-group">
				<label for="repeatEach" class="col-sm-3 control-label">Repeat each: </label>
				<div class="col-sm-8">
					<select name="repeatEach" id="repeatEach" class="form-control" required onchange="repeatWeeklySelect();" >
						<option value="eachWekk" selected>Every Week</option>
						<option value="biWeekly">Every two Weeks</option>
						<option value="triWeekly">Every three Weeks</option>
						<option value="primo">First in a month</option>
						<option value="ultimo">Last in a month</option> 
						<option value="sWeek">Specific Week No.</option> 
					</select>
				</div>
			</div>
			<div class="form-group" id="selectWeekNo" style="display:none;">
				<label for="weekNo" class="col-sm-3 control-label">Week No: </label>
				<div class="col-sm-8">
					<select name="weekNo" id="weekNo" class="form-control">
						<option selected disabled>Please select a week...</option>
						<?php for ($x=1; $x <= 52; $x++): print ('<option value="'.$x.'">'.$x.'</option>'); endfor; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="startRepeatOn" class="col-sm-3 control-label">From date: </label>
				<div class="input-group date formStartRepeatOn col-sm-3">
					<input class="form-control" type="text" required readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				</div>
				<input type="hidden" name="startDate" id="startRepeatOn" required/>
				<label for="endRepeatOn" class="col-sm-2 control-label">To date: </label>
				<div class="input-group date formEndRepeatOn col-sm-3">
					<input class="form-control" type="text" value="" required readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				</div>
				<input type="hidden" name="endDate" id="endRepeatOn" value="" />
			</div>
			<div class="form-group">
				<label for="startTime" class="col-sm-3 control-label">From time: </label>
				<div class="input-group date formStartTime col-sm-3">
					<input class="form-control" type="text" value="" required readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				</div>
				<input type="hidden" name="startTime" id="startTime" value="" />
				<label for="endTime" class="col-sm-2 control-label">To time: </label>
				<div class="input-group date formEndTime col-sm-3">
					<input class="form-control" type="text" value="" required disabled>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				</div>
				<input type="hidden" name="endTime" id="endTime" value="" />
			</div>
 			<div class="form-group">
				<label for="repeat" class="col-sm-3 control-label">Repeat each: </label>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" value="1" id="repeatMon" name="repeatMon">Monday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" value="1" id="repeatTue" name="repeatTue">Tueday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" value="1" id="repeatWed" name="repeatWed">Wednesday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" value="1" id="repeatThu" name="repeatThu">Thursday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" value="1" id="repeatFri" name="repeatFri">Friday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" value="1" id="repeatSat" name="repeatSat">Saturday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" value="1" id="repeatSun" name="repeatSun">Sunday</label></div>
			</div>
			</br>
			<center>
				<button class="btn btn-lg btn-primary" type="submit">Add Rule</button>
			</center>
		</form>
	</div> <!-- /container -->
</body>
</html>