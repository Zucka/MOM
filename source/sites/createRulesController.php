<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	if (isset($_GET['action'])) {$action = $_GET['action'];} else {$action = '';}


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
<script type="text/javascript">
	$(function() {
		$('.formStarttime').datetimepicker({
			startDate: new Date(),
	        format: "dd MM yyyy - hh:ii",
	        linkField: "datePicker",
	        linkFormat: "yyyy-mm-dd hh:ii",
        	language:  'da',
	        weekStart: 1,
	        todayBtn:  0,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			pickerPosition: 'bottom-left',
			forceParse: 0,
   		    minuteStep: 5
    	});
		$('.formEndRepeatOn').datetimepicker({
			startDate: new Date(),
	        format: "dd MM yyyy - hh:ii",
	        linkField: "endRepeatOn",
	        linkFormat: "yyyy-mm-dd hh:ii",
        	language:  'da',
	        weekStart: 1,
			autoclose: 1,
			minView: 2,
			startView: 2,
			pickerPosition: 'bottom-left',
			forceParse: 0,
   		    minuteStep: 5
    	});
    	$('.formStarttime').datetimepicker().on('hide', function(e){
		    $('.formEndRepeatOn').datetimepicker('setStartDate', e.date);
		});
    	$('.formEndRepeatOn').datetimepicker().on('hide', function(e){
		    $('.formStarttime').datetimepicker('setEndDate', e.date);
		});
  	});

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
		<h1>Create a new Rule for a controller</h1>
		</br>
		<form class="form-createRulesController form-horizontal" role="form" id="createRulesController" action="?page=createRulesController&action=create" method="post">
			<div class="form-group">
				<label for="controllerName" class="col-sm-3 control-label">Controller Name: </label>
				<div class="col-sm-8">
					<select name="controllerName" id="controllerName" class="form-control" required autofocus>
					  	<option disabled ><i>Please select a controller...</i></option>
					  	<?php foreach(controllersByCSId($_SESSION['CSid']) as $row) {print ('<option value="'.$row['CSerieNo'].'">'.$row['name'].'</option>');} ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="actionName" class="col-sm-3 control-label">Action: </label>
				<div class="col-sm-8">
					<select name="actionName" id="actionName" class="form-control" required autofocus>
					  	<option disabled style="text" >Please select an action...</option>
					  	<?php foreach($actionNames as $name) {print ('<option>'.$name.'</option>');} ?>
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
				<label for="datePicker" class="col-md-3 control-label">From date: </label>
				<div class="input-group date formStarttime col-md-8">
					<input class="form-control" type="text" required readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				</div>
				<input type="hidden" name="date" id="datePicker" required/><br/>
			</div>
			<div class="form-group">
				<label for="endRepeatOn" class="col-md-3 control-label">To date: </label>
				<div class="input-group date formEndRepeatOn col-md-8">
					<input class="form-control" type="text" value="" required readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				</div>
				<input type="hidden" name="endDate" id="endRepeatOn" value="" /><br/>
			</div>
 			<div class="form-group">
				<label for="repeat" class="col-sm-3 control-label">Repeat each: </label>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" id="repeatMon" name="repeat['Mon']">Monday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" id="repeatTue" name="repeat['Tue']">Tueday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" id="repeatWed" name="repeat['Wed']">Wednesday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" id="repeatThu" name="repeat['Thu']">Thursday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" id="repeatFri" name="repeat['Fri']">Friday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" id="repeatSat" name="repeat['Sat']">Saturday</label></div>
				<div class="col-sm-1"><label class="checkbox-inline"><input type="checkbox" id="repeatSun" name="repeat['Sun']">Sunday</label></div>
			</div>
			</br>
			<center>
				<button class="btn btn-lg btn-primary" type="submit">Add Rule</button>
			</center>
		</form>
	</div> <!-- /container -->
</body>
</html>