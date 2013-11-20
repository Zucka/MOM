<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	//DB includes
	include_once "database/DBfunctions.php";
	
	session_start();
	if (isset($_GET['action'])) {$action = $_GET['action'];} else {$action = '';}

	if ($action == '')
	{
			if (isset($_GET['error'])) {$error = $_GET['error'];} else {$error = '';}
			$errorStartUsername = ''; $errorEndUsername = ''; $errorStartPassword = ''; $errorEndPassword = '';
			if ($error == '1')
			{
					$errorStartUsername = '<div class="control-group error">
												<div class="controls">';
												
					$errorEndUsername        = '	</div> <!-- controls -->
												</div> <!-- control-group error -->';
												
					$errorStartPassword = '<div class="control-group error">
												<div class="controls">';
												
					$errorEndPassword        = '		<span class="help-inline">Wrong Password</span>
													</div> <!-- controls -->
												</div> <!-- control-group error -->';
			}
			/*Print Form*/?>
				<html lang="en">
						<head>
							<meta charset="utf-8">
							<title>Login</title>
							<meta name="viewport" content="width=device-width, initial-scale=1.0">
							 <meta http-equiv="X-UA-Compatible" content="IE=Edge"> <!-- Force document mode to IE9 standards -->
							 <!-- JQuery from Google -->
							<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
							<!-- JQueryUI from Google -->
							<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script> 
							<!-- Bootstrap -->
							<link href="assets/css/bootstrap.min.css" rel="stylesheet">

							<script src="assets/js/bootstrap.min.js"></script>

							<!-- Tablesorter -->
							<script type="text/javascript" src="assets/tablesorter/jquery-latest.js"></script> 
							<script type="text/javascript" src="assets/tablesorter/jquery.tablesorter.js"></script> 
							<link href="assets/tablesorter/themes/blue/style.css" rel="stylesheet">

							<!-- Our CSS -->
							<link href="assets/css/style.css" rel="stylesheet">
						</head>
						<body>
						<div class="container">
								<form class="form-signin" action="?action=login" method="post">
										<div class="logo" >
												<img height="90%" src="assets/image/loginImage.jpg">
										</div>
										<?php echo $errorStartUsername; ?>
										<input type="text" name="username" class="input-block-level" placeholder="Username">
										<?php echo $errorEndUsername; ?>
										<?php echo $errorStartPassword; ?>
										<input type="password" name="password" class="input-block-level" placeholder="Password">
										<?php echo $errorEndPassword; ?>
										<button class="btn btn-large btn-primary" type="submit">Log In</button>
								</form>
						</div> <!-- /container -->
						</body>
				</html>
			<?php /*End of Print*/
	}
	elseif ($action == 'login')
	{
			if (isset($_POST['username'])) {$username = $_POST['username'];} else {header('location:login.php');}
			if (isset($_POST['password'])) {$password = $_POST['password'];} else {header('location:login.php');}

			$result = validateLogin($username,$password);
			if ($result == false) {
					header('location:login.php?error=1');
					exit();
			}
			else {
					$_SESSION['session_id'] = session_id();
					$_SESSION['username'] = $username;
					$_SESSION['CSId'] = $result;

					session_write_close();
					header('location:/');
			}
	}
?>