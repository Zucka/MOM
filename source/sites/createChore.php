<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	// require_once "db/db_user.php";

	if (isset($_GET['action'])) {$action = $_GET['action'];} else {$action = '';}

	if ($action == 'create') {
		if (isset($_POST['name']) && 
			isset($_POST['description']) && 
			isset($_POST['defaultpoints']) &&
			is_numeric($_POST['defaultpoints']))
			 {
			 $newChore = new Chore($_SESSION['CSId'],null,$_POST['name'],$_POST['description'],$_POST['defaultpoints']);
			 if (simpleInsertIntoDB($newChore) === true) {//Stricly True, because an array with some value is also true in PHP.
				// echo (DEVELOPER ? "Accepted" : "" );
				echo '<script type="text/javascript">alert("Chore created.");</script>';
			 }
			 else{echo '<script type="text/javascript">alert("Something went wrong in the DB.");</script>';}
		}
		else {echo '<script type="text/javascript">alert("You are missing something..");</script>';}
	}
?>

<!DOCTYPE html>
<head>
	<title>Create Chore</title>
</head>
<body>
	<div class="container">
		<div class="col-sm-1"></div>
			<div class="col-sm-10">
				<h1>Create a new Chore</h1>
				</br>
				<form class="form-createUser form-horizontal" role="form" id="createUser" action="?page=createChore&action=create" method="post">
					<div class="form-group">
						<label for="name" class="col-sm-3 control-label">Name: </label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="name" id="name" placeholder="Name..." required autofocus>
						</div>
					</div>
					<div class="form-group">
						<label for="description" class="col-sm-3 control-label">Description: </label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="description" id="description" placeholder="Description..." required>
						</div>
					</div>
					<div class="form-group">
						<label for="defaultpoints" class="col-sm-3 control-label">Default Points: </label>
						<div class="col-sm-8">
							<input type="number" class="form-control" name="defaultpoints" id="defaultpoints" placeholder="10" required>
						</div>
					</div>
			        </br>
			        <center>
						<button class="btn btn-lg btn-primary" type="submit">Create Chore</button>
					</center>
				</form>
			</div>
	</div> <!-- /container -->
</body>
</html>