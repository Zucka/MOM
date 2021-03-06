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
			if(isset($_POST['username']) && $_POST['username'] != "" ){
				if(strpos($_POST['username'], " ") === false){
					$username = $_POST['username'];
				}
				else{
					echo "ERROR: Username is invalid.";
					printUserForm($Pid);
					die;
				}
			}
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
		elseif(isset($_POST['doPoints'])){
			require_once("database/db_points.php");
			if($_POST['addOrRemove'] == "add"){
				$result = db_points_add($Pid,$_POST['pointsAmount'],true);
			}
			else{
				$result = db_points_remove($Pid,$_POST['pointsAmount'],true);
			}
			
			if($result === true){
				if($_POST['addOrRemove'] == "add")
					echo "Success, points have been added.";
				else
					echo "Success, points have been removed.";
			}
			else{
				if($result === "ERROR_To_Many_Points")
					echo "ERROR: Removing too many points.";
				else
					echo "ERROR: An error has occurred, please try again later.";
			}
			printUserForm($Pid);
		}
		elseif(isset($_POST['editPassword'])){
			if(isset($_POST['newPassword']) && isset($_POST['newPasswordRepeat']) && $_POST['newPassword'] != "" && $_POST['newPasswordRepeat'] != ""){
				if($_POST['newPassword'] == $_POST['newPasswordRepeat']){
					if(strpos($_POST['newPassword'], " ") === false){
						//$CSId, $profileId = null, $name =null , $username = null, $password= null, $email=null, $points = null,  $role= null, $phoneNo = null) 
						$updateProfile = new Profile($_SESSION['CSid'],$Pid, null, null, $_POST['newPassword'], null, null, null, null);
						$result = simpleUpdateDB($updateProfile);
						
						if($result === true)
							echo "Success, the password have now been updated.";
						else
							echo "ERROR: An error has occurred, please try again later.";
					}
					else{
						echo "ERROR: Password contains invalid values.";
					}
				}
				else
					echo "ERROR: Passwords does not match.";
			}
			else
				echo "ERROR: You have not entered any password.";
				
			printUserForm($Pid);
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
							url: 'ajax/json-charts1.php?chart=useStatistics&PId=<?php echo $Pid ?>',
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
				<!-- Modal -->
				<div class="modal fade" id="pointEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Point Add/Remove</h4>
					  </div>
					  <form class="form-createUserPoints form-horizontal" role="form" id="createUser" action="?page=profile&Pid=<?php echo $Pid;?>" method="post">
						  <div class="modal-body">
							
							<div class="form-group">
								<label class="col-sm-3 control-label">Do: </label>
								<div class="col-sm-8">
									<select name="addOrRemove">
										<option value="add">Add</option>
										<option value="remove">Remove</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Points: </label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="0" name="pointsAmount">
								</div>
							</div>
							
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<input type="submit" class="btn btn-primary" value="Do Points" name="doPoints">
						  </div>
					  </form>
					</div><!-- /.modal-content -->
				  </div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				
				<!-- Modal -->
				<div class="modal fade" id="passwordEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Password Edit</h4>
					  </div>
					  <form class="form-createUserPoints form-horizontal" role="form" id="createUser" action="?page=profile&Pid=<?php echo $Pid;?>" method="post">
						  <div class="modal-body">
							
							<div class="form-group">
								<label class="col-sm-3 control-label">New Password: </label>
								<div class="col-sm-8">
									<input type="password" class="form-control" placeholder="********" name="newPassword">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">New Password Repeat: </label>
								<div class="col-sm-8">
									<input type="password" class="form-control" placeholder="********" name="newPasswordRepeat">
								</div>
							</div>
							
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<input type="submit" class="btn btn-primary" value="Submit Password Change" name="editPassword">
						  </div>
					  </form>
					</div><!-- /.modal-content -->
				  </div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
			
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
								<label class="col-sm-3 control-label">Password: </label>
								<div class="col-sm-6">
									<span class="form-control-static">* * * * * * * * *</span>
								</div>
								<div class="col-sm-2">
									<button class="btn btn-primary btn-sm pointEdit-button" data-toggle="modal" data-target="#passwordEdit">Change</button>
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
									<span class="form-control-static"><?php echo $userDetails['points'] ?></span> <button class="btn btn-primary btn-sm pointEdit-button" data-toggle="modal" data-target="#pointEdit">Edit Points</button>
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