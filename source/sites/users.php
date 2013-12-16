<?php
	$userArray = profilesByCSId($_SESSION['CSid']);
?>

<h1>Users</h1>

<table id="userTable" class="tablesorter">
	<thead>
		<tr>
			<th>Name</th><th>Username</th><th>E-Mail</th><th>Phone</th><th>Role</th><th>Points left</th><th>Details</th>
		</tr>
	</thead>
	<tbody>
			<?php 
				foreach($userArray as $user){
					echo '<tr>
							<td>'.$user['name'].'</td>
							<td>'.$user['username'].'</td>
							<td>'.$user['email'].'</td>
							<td>'.$user['phone'].'</td>
							<td>'.$user['role'].'</td>
							<td>'.$user['points'].'</td>
							<td><a href="?page=profile&Pid='.$user['PId'].'"><button type="button" class="btn btn-success btn-xs">Details</button></a></td>
						</tr>';
				}
			?>
	</tbody>	
</table>

<a href="?page=createUser"><button type="button" class="btn btn-devices btn-primary"><span class="glyphicon glyphicon-plus"></span> Add User</button></a>
<div id="warningContainer">
</div>

<script>
$(document).ready(function(){ 
        $("#userTable").tablesorter(); 
    } 
); 
</script>