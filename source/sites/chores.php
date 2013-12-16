<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/spc/source/database/db_points.php');
	
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'give')
{
	$points = isset($_GET['points']) ? $_GET['points'] : '';
	$profile = isset($_GET['profile']) ? $_GET['profile'] : '';

	if (db_points_add($profile,$points,true)) //todo add profile_did_chore
	{
		header('location: ?page=chores&status=1');
	}
	else
	{
		header('location: ?page=chores&status=2');
	}
}
elseif ($action == 'details')
{ //todo
?>

<?php
}
else
{
	$choreArray = getChoresFromCSID($_SESSION['CSid']);
	$profileArray = profilesByCSId($_SESSION['CSid']);
	$profileSelect = '<select name="profile">';
	foreach ($profileArray as $profile)
	{
		$profileSelect .= '<option value="'.$profile['PId'].'">'.$profile['name'].'</option>';
	}
	$profileSelect .= '</select>';
	$status = isset($_GET['status']) ? $_GET['status'] : 0;
?>

<h1>Chores</h1>
<?php
switch ($status) {
	case 1:
		echo '<div class="alert alert-success">Successfully gave points</div>';
		break;
	case 2:
		echo '<div class="alert alert-danger">Something went wrong while giving points</div>';
		break;
	
	default:
		# code...
		break;
}
	
?>
<table id="choreTable" class="tablesorter">
	<thead>
		<tr>
			<th>Name</th><th>Description</th><th class="col-xs-1">Points</th><th class="col-xs-2">To</th><th class="col-xs-1">Give</th>
		</tr>
	</thead>
	<tbody>
			<?php
				foreach($choreArray as $chore){
					echo '<tr>
							<td>'.$chore['name'].'</td>
							<td>'.$chore['description'].'</td>
							<form action="?page=chores&action=give" method="post">
							<td><input type="text" name="points" class="form-control input-sm" value="'.$chore['defaultPoints'].'" /></td>
							<td>'.$profileSelect.'</td>
							<td><button type="submit" class="btn btn-success btn-xs">Give</button></td>
							</form>
						</tr>';
				}
			?>
	</tbody>	
</table>

<a href="?page=createChore"><button type="button" class="btn btn-devices btn-primary"><span class="glyphicon glyphicon-plus"></span> Add Chore</button></a>

<script>
$(document).ready(function(){ 
        $("#choreTable").tablesorter(); 
    } 
); 
</script>
<?php
}
?>