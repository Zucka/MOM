<?php

	if (isset($_GET['delete'])) {$delete = $_GET['delete'];} else {$delete = '';}

	if ($delete != '' && is_numeric($delete)) {
		$rule = new Rules($_SESSION['CSid'], null, null, $delete);
		removeObjectFromDB($rule);
		$delete = 1;
	}
	$rules = getRulesFromCSID($_SESSION['CSid'], true);
?>

<!DOCTYPE html>
<head>
	<title>Permissions</title>
</head>
<body>
	<div class="col-md-6">
		<h3 class="headder-access">Permissions<a href="?page=permissionsAdd"><button type="button" class="btn btn-devices btn-primary"><span class="glyphicon glyphicon-plus"></span> Add Permission</button></a></h3>
	<center>
		<table id="permissionsTable" class="tablesorter">
			<thead>
				<tr>
					<th>Permission</th><th>Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				foreach($rules as $rule){
					echo '<tr>';
					echo '<td>'.$rule['rulesVariable']['name'].'</td>';
					echo '
					<td><button class="btn btn-xs btn-warning" type="button" onclick="location.href=\'?page=permissions&delete='.$rule['rulesVariable']['RId'].'\';">Delete</button></td>
				</tr>';
			}
			?>
			</tbody>	
		</table>
		</br>
	</center>
	</div>
		<div class="modal fade" id="migrateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Deletion Successfull</h4>
					</div>
					<div class="modal-body">
						Permission is now deleted
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	<script type="text/javascript">
	$(document).ready(function(){ 
        	$("#permissionsTable").tablesorter(); 
        } 
	); 
	</script>
	<?php 
	if ($delete == 1) {
		echo '<script type="text/javascript">$("#migrateModal").modal("toggle");</script>';
	}
	?>
</body>
</html>