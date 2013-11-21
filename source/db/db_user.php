<?php
 // File containing User related DB functions

require_once('db.php');

	$connectionNewBD = new mysqli('localhost','root','root','smartparentalcontrol');
	if (mysqli_connect_errno()) {
	    die('Could not connect: ' . mysqli_connect_error());
	}
	
	function queryNew($q) {
		global $connectionNewBD;
		$R = $connectionNewBD->query($q);
		return $R;
	}

	function validateLogin($username, $password) {
		global $connectionNewBD;
		$username = $connectionNewBD->real_escape_string($username);
		$password = $connectionNewBD->real_escape_string($password);
		$Q = "SELECT Pid, password FROM profile WHERE username = '".$username."' LIMIT 1";
		$R = queryNew($Q);
		$D = $R->fetch_assoc();
		// if (isset($_GET['action'])) {$action = $_GET['action'];} else {$action = '';}
		$_SESSION['Pid'] = $D['Pid'];
		// Hashing the password with its hash as the salt returns the same hash
		if ( crypt($password, $D['password']) == $D['password'] ) {
		  // Ok!
				$Q = "SELECT Pid, CSid, name, username, email, phone, role FROM profile WHERE Pid = ".$_SESSION['Pid']." LIMIT 1";
				$R = queryNew($Q);
				$D = $R->fetch_assoc();
				$_SESSION['CSid'] = $D['CSid'];
				$_SESSION['name'] = $D['name'];
				$_SESSION['username'] = $D['username'];
				$_SESSION['phone'] = $D['phone'];
				$_SESSION['email'] = $D['email'];
				$_SESSION['role'] = $D['role'];
				$_SESSION['session_id'] = session_id();
				session_write_close();
				header("Location:/");
		}
		else  {
			// Wrong Password OR no such user
			header('location:login.php?error=1');
		}
	}

	function hashPassword($password) {

		// Taken from: http://alias.io/2010/01/store-passwords-safely-with-php-and-mysql/
		// How to store passwords safely with PHP and MySQL

		// A higher "cost" is more secure but consumes more processing power
		$cost = 10;

		// Create a random salt
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

		// Prefix information about the hash so PHP knows how to verify it later.
		// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
		$salt = sprintf("$2a$%02d$", $cost) . $salt;

		// Hash the password with the salt
		$hash = crypt($password, $salt);

		return $hash;

	}

												// '".$_SESSION['CSid']."',)"))

	function createUser($postData) {
		global $connectionNewBD;
		$hashedPassword = hashPassword($postData['password']);
		if (mysqli_query($connectionNewBD,"INSERT INTO profile (name, username, email, phone, role, password, CSid)
										VALUES ('".$postData['name']."',
												'".$postData['userName']."',
												'".$postData['email']."',
												'".$postData['phone']."',
												'".$postData['userRole']."',
												'".$hashedPassword."',
												1)"))
		{
			return true;
		}
		else {
			echo "ERROR: " . mysqli_error($connectionNewBD);
		}
	}
?>