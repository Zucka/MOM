<?php
	/*session_start();
	
	if ($_SESSION['session_id'] != session_id())
	{
        header('location:login.php');
	} */
?>
<head>
	<?php include "include/headInclude.php"; ?>
</head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	  <!-- Brand and toggle get grouped for better mobile display -->
	  <div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		  <span class="sr-only">Toggle navigation</span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="/">Smart Parental Control</a>
	  </div>

	  <!-- Collect the nav links, forms, and other content for toggling -->
	  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<ul class="nav navbar-nav navbar-right">
		  <li><a href="#">Help</a></li>
		  <li><a href="#">Web-Shop</a></li>
		  <li><a href="#">Log Out</a></li>
		</ul>
	  </div><!-- /.navbar-collapse -->
	</nav>
	<div id="wrapper">
		<div class="row" id="lowerWrapper">
			<div class="col-md-2" id="leftMenu">
				<ul class="nav nav-pills nav-stacked">
				  <li id="dashboard"><a href="?page=dashboard"><span class="glyphicon glyphicon-home"></span> Dashboard</a></li>
				  <li id="devices"><a href="?page=devices"><span class="glyphicon glyphicon-hdd"></span> Devices</a></li>
				  <li class="dropdown" id="users">
					<a class="dropdown-toggle" data-toggle="dropdown" href="?page=users">
					  <span class="glyphicon glyphicon-user"></span> Users <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
					  <li id="chores"><a href="?page=chores"><span class="glyphicon glyphicon-list-alt"></span> Chores</a></li>
					  <li id="rules"><a href="?page=rules"><span class="glyphicon glyphicon-tower"></span> Rules</a></li>
					  <li id="permissions"><a href="?page=permissions"><span class="glyphicon glyphicon-lock"></span> Permissions</a></li>
					</ul>
				  </li>
				  <li id="graf"><a href="?page=graf"><span class="glyphicon glyphicon-stats"></span> Graf</a></li>
				  <li id="calendar"><a href="?page=calendar"><span class="glyphicon glyphicon-calendar"></span> Calendar</a></li>
				</ul>
			</div>
			<div class="col-md-10" id="content">
				<?php include 'switch.php';?>
			</div>
		</div>
	</div>
</body>