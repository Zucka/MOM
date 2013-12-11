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
		<h1>Create a new Rule for a user</h1>
		</br>
		<form class="form-createRulesController form-horizontal" role="form" id="createRulesController" action="?" method="post">
			<div class="form-group">
				<label for="controllerName" class="col-sm-3 control-label">User: </label>
				<div class="col-sm-8">
					<select name="controllerName" id="controllerName" class="form-control">
					  	<option disabled selected >Please select a user...</option>
					  	<?php foreach(profilesByCSId($_SESSION['CSid']) as $row) {print ('<option value="'.$row['PId'].'">'.$row['name'].'</option>');} ?>
					</select>
				</div>
			</div>
		</form>
	</div> <!-- /container -->
</body>
</html>