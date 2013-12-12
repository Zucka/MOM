<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	if (isset($_GET['action'])) {$action = $_GET['action'];} else {$action = '';}

	if ($action == "create") {
		if (isset($_POST['actionName']) &&
			isset($_POST['name']) &&
			isset($_POST['repeatEach'])) {
			switch ($_POST['actionName']) {
				case 'Block user': echo "Not yet implemented so sorry";
					break;
				case 'Activate user': echo "Not yet implemented so sorry";
					break;
				case 'Add points':
					print_r($_POST); // DEVELOPER 
					$startTime = new DateTime($_POST['startDate'].' '.$_POST['timeATHidden']);
					$endTime = new DateTime($_POST['endDate']);
					$nRule = new Rules($_SESSION['CSid'], $_POST['name']);
					$weekdays  = (isset($_POST['repeatMon']) ? 'monday,' 	: '');
					$weekdays .= (isset($_POST['repeatTue']) ? 'tuesday,' 	: '');
					$weekdays .= (isset($_POST['repeatWed']) ? 'wednesday,' 	: '');
					$weekdays .= (isset($_POST['repeatThu']) ? 'thursday,' 	: '');
					$weekdays .= (isset($_POST['repeatFri']) ? 'friday,' 	: '');
					$weekdays .= (isset($_POST['repeatSat']) ? 'saturday,' 	: '');
					$weekdays .= (isset($_POST['repeatSun']) ? 'sunday,' 	: '');
					$nCondition = new Condition(0 , "Timeperiod", null, null, array('timeFrom'		=> '"'.$_POST['startDate'].' '.$_POST['timeATHidden'].'"',
																					'timeTo'		=> '"'.$_POST['endDate'].'"',
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
						echo "</br>"; // DEVELOPER 
						print_r($nRule); // DEVELOPER 
						echo "</br>"; // DEVELOPER 
						print_r($arrayCondition); // DEVELOPER 
						echo "</br>"; // DEVELOPER 
						print_r($arrayAction); // DEVELOPER 
						echo "</br>"; // DEVELOPER 
						$dbResult = addNewRuleToDB($nRule, $arrayCondition, $arrayAction);
						print_r($dbResult);
					} else { echo '<script type="text/javascript">alert("You are missing points..");</script>'; }
					break;
				case 'Set maximum of point': echo "Not yet implemented so sorry";
					break;
				case 'Access any controller': echo "Not yet implemented so sorry";
					break;
				case 'Cannot access any controller': echo "Not yet implemented so sorry";
					break;
				case 'Access controller': echo "Not yet implemented so sorry";
					break;
				case 'Cannot access controller': echo "Not yet implemented so sorry";
					break;
				default: echo "WHAT ARE YOU DOING ?????"; break; 
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
var actionSelected ="";
function actionNameSelect () {
	$( "#actionName option:selected" ).each(function() {
		dateFromTo();
		timeFromTo();
		timeAT();
		specificTime();
		actionSelected = $( this ).text();
		switch (actionSelected) {
			case 'Block user':{ 					setVisibility (false, true ); }break;
			case 'Activate user':{  				setVisibility (false, true ); }break;
			case 'Add points':{ 					setVisibility (false, true ); }break;
			case 'Set maximum of point':{   		setVisibility (false, true ); }break;
			case 'Access any controller':{  		setVisibility (false, true ); }break;
			case 'Cannot access any controller':{   setVisibility (false, true ); }break;
			case 'Access controller':{  			setVisibility (true , false); }break;
			case 'Cannot access controller':{   	setVisibility (true , false); }break;
			default: break;
		}
	});
}

function setVisibility (controllerName, amountOfPoints) {
	$("#controllerName").css("display"	, (controllerName 	? "" : "none"));
	$("#amountOfPoints").css("display"	, (amountOfPoints 	? "" : "none"));
	repeatWeeklySelect ();
}
function repeatWeeklySelect () {
	$( "#repeatEach option:selected" ).each(function() {
		// Reset form
		changeStateOfID(	"#repeatBetween", 'add');
		changeStateOfID(	"#betweenTime"	, 'add');
		changeStateOfChkBox("#repeatDays"	, 'add');
		changeStateOfID(	"#selectWeekNo"	, 'remove');
		changeStateOfID(	"#ATTime"		, 'remove');
		changeStateOfID(	"#SpecificTime"	, 'remove');
		if (actionSelected == 'Add points') {
			changeStateOfID("#ATTime"		, 'add');
			changeStateOfID("#betweenTime"	, 'remove');
		}
		if ($( this ).attr("value") == "sWeek") {
			changeStateOfID("#selectWeekNo"	, 'add');
			changeStateOfID("#repeatBetween", 'remove');
		} 
		else if ($( this ).attr("value") == "noRepeat") {
			changeStateOfID("#SpecificTime"	, 'add');
			changeStateOfID("#repeatBetween", 'remove');
			changeStateOfID("#betweenTime"	, 'remove');
			changeStateOfID("#ATTime"		, 'remove');
			changeStateOfChkBox("#repeatDays",'remove');
		}
		else {
		}
	});
}
function changeStateOfID(id, changeTo) {
	if (changeTo == 'remove') {
		$(id).css("display", "none");
		$(id).attr("disabled","disabled");
	} else {
		$(id).css("display", "");
		$(id).removeAttr("disabled","disabled");
	};
}
function changeStateOfChkBox(id, changeTo) {
	if (changeTo == 'remove') {
		$(id).css("display", "none");
		$(id).children('.col-sm-1').children('.checkbox-inline').children('input').attr("disabled","disabled");
	} else {
		$(id).css("display", "");
		$(id).children('.col-sm-1').children('.checkbox-inline').children('input').removeAttr("disabled","disabled");
	};
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
					<select name="actionName" id="actionName" class="form-control actionName" required autofocus onchange="actionNameSelect();">
						<option value="def" disabled selected>Please select an action...</option>
					  	<?php foreach($actionNames as $name) {print ('<option '.($name != "Add points" ? 'disabled':'').'>'.$name.'</option>
					  	'); }?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="name" class="col-sm-3 control-label">Rule Name: </label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="name" id="name" placeholder="Name..." required >
				</div>
			</div>
			<div class="form-group" id="controllerName" style="display:none;">
				<label for="controllerName" class="col-sm-3 control-label">Controller Name: </label>
				<div class="col-sm-8">
					<select name="controllerName" id="controllerName" class="form-control" disabled>
					  	<option value="def" disabled selected >Please select a controller...</option>
					  	<?php foreach(controllersByCSId($_SESSION['CSid']) as $row) {print ('<option value="'.$row['CSerieNo'].'">'.$row['name'].'</option>
					  	');} ?>
					</select>
				</div>
			</div>
			<div class="form-group" id="amountOfPoints" style="display:none;">
				<label for="amountOfPoints" class="col-sm-3 control-label">Amount of Points: </label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="amountOfPoints" id="amountOfPoints" placeholder="Points..." required >
				</div>
			</div>
			<div class="form-group" id="repeatEach" style="display:none;">
				<label for="repeatEach" class="col-sm-3 control-label">Repeat each: </label>
				<div class="col-sm-8">
					<select name="repeatEach" id="repeatEach" class="form-control" required onchange="repeatWeeklySelect();" >
						<option value="eachWekk"	>Every Week</option>
						<option value="biWeekly"	>Every two Weeks</option>
						<option value="triWeekly"	>Every three Weeks</option>
						<option value="primo"		>First in a month</option>
						<option value="ultimo"		>Last in a month</option> 
						<option value="sWeek"		>Specific Week No.</option> 
						<option value="noRepeat"	>Do not repeat</option>
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
				<label for="timeSPCTHidden" class="col-sm-3 control-label">On the: </label>
				<div class="input-group date formSpecificTime col-sm-8">
					<input class="form-control" type="text" value="" required readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				</div>
				<input type="hidden" name="timeSPCTHidden" id="timeSPCTHidden" required/>
			</div>
			<div class="form-group" id="repeatBetween" style="display:none;">
				<label for="startRepeatOn" class="col-sm-3 control-label">From date: </label>
				<div class="input-group date formStartRepeatOn col-sm-3">
					<input class="form-control" type="text" value="" required readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
				</div>
				<input type="hidden" name="startDate" id="startRepeatOn" required/>
				<label for="endRepeatOn" class="col-sm-2 control-label">To date: </label>
				<div class="input-group date formEndRepeatOn col-sm-3">
					<input class="form-control" type="text" value="" required readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
				</div>
				<input type="hidden" name="endDate" id="endRepeatOn" value="" />
			</div>
			<div class="form-group" id="ATTime" style="display:none;">
				<label for="timeATHidden" class="col-sm-3 control-label">At: </label>
				<div class="input-group date formATTime col-sm-8">
					<input class="form-control" type="text" value="" required readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				</div>
				<input type="hidden" name="timeATHidden" id="timeATHidden" required/>
			</div>
			<div class="form-group" id="betweenTime" style="display:none;">
				<label for="startTime" class="col-sm-3 control-label">From time: </label>
				<div class="input-group date formStartTime col-sm-3">
					<input class="form-control" type="text" value="" required readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				</div>
				<input type="hidden" name="startTime" id="startTime" value="" />
				<label for="endTime" class="col-sm-2 control-label">To time: </label>
				<div class="input-group date formEndTime col-sm-3">
					<input class="form-control" type="text" value="" required readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				</div>
				<input type="hidden" name="endTime" id="endTime" value="" />
			</div>
 			<div class="form-group" id="repeatDays" style="display:none;">
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
				<button class="btn btn-lg btn-primary" id="submitBtn" type="submit">Add Rule</button>
			</center>
		</form>
	</div> <!-- /container -->
</body>
</html>