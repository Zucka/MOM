<script>
function setMenuActive(menuId){
	$("#"+menuId).addClass("active");
}
</script>

<?php
	if (isset($_GET['page'])) {$page = $_GET['page'];} else {$page = 'dashboard';}
	
	switch($page){
		case "":
		case "dashboard":
			include "sites/dashboard.php";
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
			include "sites/users.php";
			?><script>setMenuActive("users");</script><?php
			break;
		case "chores":
			include "sites/chores.php";
			?><script>setMenuActive("users");</script><?php
			?><script>setMenuActive("chores");</script><?php
			break;
		case "rules":
			include "sites/rules.php";
			?><script>setMenuActive("users");</script><?php
			?><script>setMenuActive("rules");</script><?php
			break;
		case "rulesAdd":
			include "sites/createRules.php";
			?><script>setMenuActive("users");</script><?php
			?><script>setMenuActive("rules");</script><?php
			break;
		case "permissions":
			include "sites/permissions.php";
			?><script>setMenuActive("users");</script><?php
			?><script>setMenuActive("permissions");</script><?php
			break;
		case "permissionsAdd":
			include "sites/createPermissions.php";
			?><script>setMenuActive("users");</script><?php
			?><script>setMenuActive("permissions");</script><?php
			break;
		case "createUser":
			include "sites/createUser.php";
			?><script>setMenuActive("users");setMenuActive("createUser");</script><?php
			break;
		case "profile":
			include "sites/profile.php";
			?><script>setMenuActive("users");setMenuActive("users");</script><?php
			break;
		case "graf":
			include "sites/graph.php";
			?><script>setMenuActive("graf");</script><?php
			break;
		case "calendar":
			include "sites/calendar.php";
			?><script>setMenuActive("calendar");</script><?php
			break;	
		case "createChore":
			include "sites/createChore.php";
			?><script>setMenuActive("chores");setMenuActive("createChore");</script><?php
			break;
		default:
			include "sites/404.php";
			break;
	}
?>