<?php
	/*session_start();
	
	if ($_SESSION['session_id'] != session_id())
	{
        header('location:login.php');
	} */

	include "include/headInclude.php";
?>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Smart Parental Control</a>
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
<div class="row" id="lowerWrapper">
	<div class="col-md-2">
		<ul class="nav nav-pills nav-stacked">
		  <li class="active"><a href="#">Dashboard</a></li>
		  <li><a href="#">Devices</a></li>
		  <li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			  Users <span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
			  <li><a href="#">Chores</a></li>
			  <li><a href="#">Rules</a></li>
			  <li><a href="#">Permissions</a></li>
			</ul>
		  </li>
		  <li><a href="#">Graf</a></li>
		  <li><a href="#">Calendar</a></li>
		</ul>
	</div>
	<div class="col-md-10">
		index.php
	</div>
</div>