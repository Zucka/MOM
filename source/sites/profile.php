<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	// require_once "db/db_user.php";

	if (isset($_GET['Pid'])) {$Pid = $_GET['Pid'];} else {$Pid = $_SESSION['PId'];}
	$userDetails = getProfileByProfileId($Pid);
	$tagDetails = null;
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
</head>
<body>
	<div class="container">
		<h1>Profile Details</h1>
	</br>
	<div class="col-sm-12">
		<div class="col-sm-6">
			<h3>User Info: </h3>
			<form class="form-createUser form-horizontal" role="form" id="createUser" action="" method="post">
				<div class="form-group">
					<label class="col-sm-3 control-label">Name: </label>
					<div class="col-sm-8">
						<p class="form-control-static"><?php echo $userDetails['name'] ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Username: </label>
					<div class="col-sm-8">
						<p class="form-control-static"><?php echo $userDetails['username'] ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Phone: </label>
					<div class="col-sm-8">
						<p class="form-control-static"><?php echo $userDetails['phone'] ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Email: </label>
					<div class="col-sm-8">
						<p class="form-control-static"><?php echo $userDetails['email'] ?></p>
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
						<p class="form-control-static"><?php echo $userDetails['role'] ?></p>
					</div>
				</div>
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