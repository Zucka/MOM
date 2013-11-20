<script>
function setMenuActive(menuId){
	$("#"+menuId).addClass("active");
}
</script>

<?php
	$page = $_GET['page'];
	
	switch($page){
		case "":
		case "dashboard":
			include "";
			?><script>setMenuActive("dashboard");</script><?php
			break;
		
		case "devices":
			include "sites/devices.php";
			?><script>setMenuActive("devices");</script><?php
			break;
			
		case "addTag":
			include "sites/addTag.php";
			?><script>setMenuActive("devices");</script><?php
			break;
		
		case "detailsTag":
			include "sites/detailsTag.php";
			?><script>setMenuActive("devices");</script><?php
			break;
			
		case "addController":
			include "sites/addController.php";
			?><script>setMenuActive("devices");</script><?php
			break;
			
		case "detailsController":
			include "sites/detailsController.php";
			?><script>setMenuActive("devices");</script><?php
			break;
		
		case "users":
			include "";
			?><script>setMenuActive("users");</script><?php
			break;
		
		case "chores":
			include "";
			?><script>setMenuActive("users");</script><?php
			?><script>setMenuActive("chores");</script><?php
			break;
		
		case "rules":
			include "";
			?><script>setMenuActive("users");</script><?php
			?><script>setMenuActive("rules");</script><?php
			break;
		
		case "permissions":
			include "";
			?><script>setMenuActive("users");</script><?php
			?><script>setMenuActive("permissions");</script><?php
			break;

		case "createUser":
			include "sites/createUser.php";
			?><script>setMenuActive("users");setMenuActive("createUser");</script><?php
			break;
		
		case "graf":
			include "";
			?><script>setMenuActive("graf");</script><?php
			break;
		
		case "calendar":
			include "";
			?><script>setMenuActive("calendar");</script><?php
			break;
			
		default:
			include "sites/404.php";
			break;
	}
?>