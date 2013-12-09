<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	//DB includes
	include_once "database/DBfunctions.php";
	// require_once "db/db_user.php";
	
	// session_start();
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
			elseif($error == '2'){
				echo "Somthing not set";
			}
			/*Print Form*/?>
			<html lang="en">
			<head>
				<meta charset="utf-8">
				<title>Login</title>
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<meta http-equiv="X-UA-Compatible" content="IE=Edge"> <!-- Force document mode to IE9 standards -->
				<!-- Bootstrap -->
				<link href="assets/css/bootstrap.min.css" rel="stylesheet">
				<script src="assets/js/bootstrap.min.js"></script>
				<!-- Our CSS -->
				<link href="assets/css/signin.css" rel="stylesheet">
			</head>
			<body>
				<div class="container">
					<form class="form-signin" action="?action=login" method="post">
						<div class="logo" >
							<img width="100%" src="assets/image/loginImage.jpg">
						</div>
						<?php echo $errorStartUsername; ?>
						<input type="text" name="usernameLogin" class="form-control" placeholder="Username">
						<?php echo $errorEndUsername; ?>
						<?php echo $errorStartPassword; ?>
						<input type="password" name="passwordLogin" class="form-control" placeholder="Password">
						<?php echo $errorEndPassword; ?>
						<button class="btn btn-lg btn-primary btn-block" type="submit">Log In</button>
					</form>
				</div> <!-- /container -->
			</body>
			</html>
			<?php /*End of Print*/
	}
	elseif ($action == 'login')
	{
		if (isset($_POST['usernameLogin'])) {$username = $_POST['usernameLogin'];} else {header('location:login.php?error=2');}
		if (isset($_POST['passwordLogin'])) {$password = $_POST['passwordLogin'];} else {header('location:login.php?error=2');}

		$result = validateLogin($username,$password);
		if ($result == false) {
				header('location:login.php?error=1');
				die();
		}
		else {
				// Store user info in Session varieble
				$_SESSION['Pid'] = $result['Pid'];
				$_SESSION['CSid'] = $result['CSid'];
				$_SESSION['name'] = $result['name'];
				$_SESSION['username'] = $result['username'];
				$_SESSION['phone'] = $result['phone'];
				$_SESSION['email'] = $result['email'];
				$_SESSION['role'] = $result['role'];
				$_SESSION['session_id'] = session_id();
				session_write_close();
				header("Location:?login=ok");
		}
	}
?>