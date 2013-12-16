<?php
	// require_once "db/db_user.php";
	if (isset($_GET['Pid'])) {$Pid = $_GET['Pid'];} else {$Pid = $_SESSION['PId'];}
	if(!existsProfileInCS($Pid,$_SESSION['CSid'])){
		echo "Sorry, this user is not in your system.";
	}
	else{
		if(isset($_POST['cancel'])){
			header('location:?page=users');
		}
		elseif(isset($_POST['save'])){
			if(isset($_POST['username']) && $_POST['username'] != "" )
				$username = $_POST['username'];
			else
				$username = null;
			
			if(isset($_POST['name']))
				$name = $_POST['name'];
			else
				$name = "";
			
			if(isset($_POST['email']))
				$email = $_POST['email'];
			else
				$email = "";
				
			if(isset($_POST['phone']))
				$phoneNo = $_POST['phone'];
			else
				$phoneNo = "";
				
			if(isset($_POST['role']))
				$role = $_POST['role'];
			else
				$role = null;
			
			//$CSId, $profileId = null, $name =null , $username = null, $password= null, $email=null, $points = null,  $role= null, $phoneNo = null) 
			$updateProfile = new Profile($_SESSION['CSid'],$Pid, $name, $username, $password=null, $email, $points = null, $role, $phoneNo);
			$result = simpleUpdateDB($updateProfile);
			
			if($result === true)
				echo "Success, your profile have now been updated.";
			else
				echo "ERROR: An error has occurred, please try again later.";
			
			printUserForm($Pid);
		}
		elseif(isset($_POST['delete'])){
			$deletionProfile = new Profile($_SESSION['CSid'],$Pid);
			$result = removeObjectFromDB($deletionProfile);
			
			if($result === true){
				echo "Profile have been deleted.";
			}
			else{
				echo "An error has occurred, please try again later.";
			}
		}
		else{
			printUserForm($Pid);
		}
	}
	
	
	function printUserForm($Pid){
		$userDetails = getProfileByProfileId($Pid);
		$tagDetails = null;
		
		if(!existsProfileInCS($Pid,$_SESSION['CSid'])){
			echo "Sorry, this user is not in your system.";
		}
		else{
			if ($userDetails['TSerieNo'] != '') {
				$tagDetails  = getTagActivity($userDetails['TSerieNo'],0,10);
			}
		
?>

			<!DOCTYPE html>
			<head>
				<title>Create User</title>
				<!-- SITE SPECIFIC STYLE -->
				<link rel="stylesheet" href="//cdn.oesmith.co.uk/morris-0.4.3.min.css">
				<!-- SITE SPECIFIC JS -->
				<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
				<script src="//cdn.oesmith.co.uk/morris-0.4.3.min.js"></script>
				<script type="text/javascript">
					$(document).ready(function() { 
						$.ajax({
							url: 'ajax/json-charts1.php?chart=useStatistics&PId=1',
							success: function(data) {
								var $graph = data;
								var obj = $.parseJSON($graph);
								Morris.Donut({
								  element: 'morris-chart-donut',
								  data: obj,
								  formatter: function (y) { return y + "%" ;}
								});
							}
						});
					})
				</script>
			</head>
			<body>
				<div class="container">
					<h1>Profile Details</h1>
				</br>
				<div class="col-sm-12">
					<div class="col-sm-6">
						<h3>User Info: </h3>
						<form class="form-createUser form-horizontal" role="form" id="createUser" action="?page=profile&Pid=<?php echo $Pid;?>" method="post">
							<div class="form-group">
								<label class="col-sm-3 control-label">Name: </label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="<?php echo $userDetails['name'] ?>" name="name">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Username: </label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="<?php echo $userDetails['username'] ?>" name="username">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Phone: </label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="<?php echo $userDetails['phone'] ?>" name="phone">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email: </label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="<?php echo $userDetails['email'] ?>" name="email">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Points left: </label>
								<div class="col-sm-8">
									<p class="form-control-static"><?php echo $userDetails['points'] ?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">User Role: </label>
								<div class="col-sm-8">
									<select class="form-control" name="role">
										<?php 
											if($userDetails['role'] == "user")
												echo '<option value="user" selected>user</option><option value="manager">manager</option>';
											else
												echo '<option value="user">user</option><option value="manager" selected>manager</option>';
										?>
									</select>
								</div>
							</div>
							<button class="btn" name="cancel" value="cancel"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</button> <button class="btn" name="save" value="saveTag"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button> <button class="btn" name="delete" value="deleteTag"><span class="glyphicon glyphicon-trash"></span> Delete</button>
						</form>
					</div>
					<div class="col-sm-6">
						<h3>Last ten activities: </h3>
						<table id="logTable" class="tablesorter">
							<thead>
								<tr>
									<th>Device Name</th><th>From</th><th>To</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(!empty($tagDetails)){
									foreach($tagDetails as $row){
										echo '<tr><td>'.$row['lastUsedController'].'</td><td>'.$row['lastTimeUsedFrom'].'</td><td>'.$row['lastTimeUsedTo'].'</td></tr>';
									}
								}
								else {
									echo '<tr><td>This tag have not been used yet</td></tr>';
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row" style="display:flex;">
					<h3 style="display:inline-block;">User Charts: </h3>
				</div>
				<div class="row" style="display:flex;">
					<div class="col-sm-4">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Most active device</h3>
							</div>
							<div class="panel-body">
								<div id="morris-chart-donut"></div>
								<div class="text-right">
									<a href="#">View Details <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Total points spent</h3>
							</div>
							<div class="panel-body">
								<div id="morris-chart-line"></div>
								<div class="text-right">
									<a href="#">View Details <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Points spent pr. device</h3>
							</div>
							<div class="panel-body">
								<div id="morris-chart-bar"></div>
								<div class="text-right">
									<a href="#">View Details <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div><!-- /.row -->
			</div> <!-- /container -->
			</body>
				<script src="assets/js/chart-data.js"></script>	
			</html>

<?php
		}
	}
?>