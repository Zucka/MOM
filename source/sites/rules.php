<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	$rules = getRulesFromCSID($_SESSION['CSid']);
	$accessIF = array();
?>

<!DOCTYPE html>
<head>
	<title>Rules</title>
</head>
<body>
	<div class="container">
		<h3 class="headder-rule">Rules</h3>
		<table id="ruleTable" class="tablesorter">
			<thead>
				<tr>
					<th>Name</th><th>Action</th><th>Repeat</th><th>From</th><th>To</th><th>Days</th><th>Points</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				foreach($rules as $rule){
					if ($rule['conditions'][0]['name'] == 'Controller on' || $rule['conditions'][0]['name'] == 'Controller off') {
						$accessIF[] = $rule;
						continue;
					}
					echo '<tr>
					<td>'.$rule['rulesVariable']['name'].'</td>
					<td>'.$rule['actions'][0]['name'].'</td>
					<td>'; 
					if ($rule['conditions'][0]['ekstra_attribute']['weekly']) {echo 'Weekly';}
					elseif ($rule['conditions'][0]['ekstra_attribute']['ndWeekly']) {echo 'Every other week';}
					elseif ($rule['conditions'][0]['ekstra_attribute']['rdWeekly']) {echo 'Every third week';}
					elseif ($rule['conditions'][0]['ekstra_attribute']['firstInMonth']) {echo 'First in a month';}
					elseif ($rule['conditions'][0]['ekstra_attribute']['lastInMonth']) {echo 'Last in a month';}
					else {echo 'Once / From';}
					echo '</td>
					<td>'.$rule['conditions'][0]['ekstra_attribute']['timeFrom'].'</td>
					<td>'; if ($rule['conditions'][0]['ekstra_attribute']['timeTo'] === $rule['conditions'][0]['ekstra_attribute']['timeFrom']) {}
					else {
						echo $rule['conditions'][0]['ekstra_attribute']['timeTo'];
					}echo '</td>
					<td>'.$rule['conditions'][0]['ekstra_attribute']['weekdays'].'</td>
					<td>'.$rule['actions'][0]['points'].'</td>
				</tr>';
			}
			?>
			</tbody>	
		</table>
		<h3 class="headder-access">Can access if</h3>
		<table id="accessIfTable" class="tablesorter">
			<thead>
				<tr>
					<th>Name</th><th>Device</th><th>Action</th><th>Device</th><th>State</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				foreach($accessIF as $rule){
					$thisif = getControllerByControllerId($rule['conditions'][0]['controllerId']);
					$ifthis = getControllerByControllerId($rule['actions'][0]['controllerId']);
					echo '<tr>';
					echo '<td>'.$rule['rulesVariable']['name'].'</td>';
						echo '<td>'.$thisif[0]['name'].'</td>';
						echo '<td>can be turned on if</td>';
						echo '<td>'.$ifthis[0]['name'].'</td>';
					if ($rule['conditions'][0]['name'] == 'Controller on') {
						echo '<td>is on</td>';
					}
					elseif ($rule['conditions'][0]['name'] == 'Controller off') {
						echo '<td>is of</td>';
					}
					echo '
				</tr>';
			}
			?>
			</tbody>	
		</table>
		</br>
		<button class="btn btn-lg btn-primary" type="button" onclick="location.href='?page=rulesAdd';">Add rule</button>
	</div> <!-- /container -->
	<script type="text/javascript">
	$(document).ready(function(){ 
        	$("#ruleTable").tablesorter(); 
        	$("#accessIfTable").tablesorter();
    	} 
	); 
	</script>
</body>
</html>