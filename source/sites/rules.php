<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<head>
	<title>Rules</title>
</head>
<body>
	<div class="container">
		</br>
		<form class="form-createRulesController form-horizontal" role="form" id="createRulesController" action="?page=rulesControllerAdd" method="post">
			<button class="btn btn-lg btn-primary" type="submit">Add controller rule</button>
		</form>
		<form class="form-createRulesUser form-horizontal" role="form" id="createRulesUser" action="?page=rulesUserAdd" method="post">
			<button class="btn btn-lg btn-primary" type="submit">Add user rule</button>
		</form>
	</div> <!-- /container -->
</body>
</html>