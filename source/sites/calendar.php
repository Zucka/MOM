<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

?>



<!DOCTYPE html>
<head>
	<title>Calendar</title>
	<!-- SITE SPECIFIC STYLE -->
	<link href='assets/css/fullcalendar.2.css' rel='stylesheet' />
	<link href='assets/css/fullcalendar.print.2.css' rel='stylesheet' media='print' />
	<style type="text/css">
	.fc-widget-header, .fc-widget-content {
		border: 1px solid #d1d1d1;
	}
	</style>

	<!-- SITE SPECIFIC JS -->
	<script src='assets/js/fullcalendar.min.js'></script>
	<script type="text/javascript">
		var userID = '<?php echo $_SESSION['PId'] ?>';
	</script>
	<script src="assets/js/calendar.js"></script>
	<!-- SITE SPECIFIC CSS -->

</head>
<body>
	<div class="container">
		<h1>Calendar:</h1>
		</br>
		<div id='fullCalendar'></div> <!-- /calender -->
	</div> <!-- /container -->
</body>
</html>