<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include "include/headInclude.php";
	
	session_start();
	//require_once('db/db.php');
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

			//$result = db_getSession($username,$password);
			if ($result == false) {
					header('location:login.php?error=1');
					exit();
			}
			else {
					$_SESSION['session_id'] = session_id();
					$_SESSION['username'] = $username;

					session_write_close();
					header('location:/');
			}
	}
?>