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
   		    minuteStep: 15
    	});
		$('.formEndRepeatOn').datetimepicker({
			startDate: new Date(),
	        format: "dd MM yyyy",
	        linkField: "endRepeatOn",
	        linkFormat: "yyyy-mm-dd hh:ii",
        	language:  'da',
	        weekStart: 1,
			autoclose: 1,
			minView: 2,
			startView: 2,
			pickerPosition: 'bottom-left',
			forceParse: 0
    	});
    	$('.formStarttime').datetimepicker().on('hide', function(e){
		    $('.formEndRepeatOn').datetimepicker('setStartDate', e.date);
		});
    	$('.formEndRepeatOn').datetimepicker().on('hide', function(e){
		    $('.formStarttime').datetimepicker('setEndDate', e.date);
		});
  	});
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
					<select name="controllerName" id="controllerName" class="form-control" required autofocus onchange="submitFormGeneric();">
					  	<option disabled >Please select a controller...</option>
					  	<?php foreach(controllersByCSId($_SESSION['CSid']) as $row) {print ('<option value="'.$row['CSerieNo'].'">'.$row['name'].'</option>');} ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="name" class="col-sm-3 control-label">Rule Name: </label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="name" id="name" placeholder="Name..." required autofocus>
				</div>
			</div>
			<div class="form-group">
				<label for="userRole" class="col-sm-3 control-label">User Role: </label>
				<div class="col-sm-8">
					<select name="userRole" id="userRole" class="form-control" required>
						<option value="user" selected>User</option>
						<option value="manager">Manager</option>
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