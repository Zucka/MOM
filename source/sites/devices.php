<script>
	globalCSId = <?php echo $_SESSION['CSid'];?>;
</script>

<?php
	function printStatus($status){
		switch($status){
			case "GREEN":
				return '<img src="/assets/icon/green.ico" class="statusSymbol">';
			break;
			
			case "RED":
				return '<img src="/assets/icon/red.ico" class="statusSymbol">';
			break;
			
			case "!":
				return '<img src="/assets/icon/warning.ico" class="statusSymbol">';
			break;
			
			default:
				return "DB Error";
			break;
		}
	}
	
	function printActiveTag($status , $profileId){
		if($status == '1')
			$statusPrint = 'checked';
		else
			$statusPrint = '';
		
		return '<input type="checkbox" name="tagId" class="activeToggler" value="'.$profileId.'" '.$statusPrint.'>';
	}

	$controllerArray = controllersByCSId($_SESSION['CSid']);
	$tagArray = tagsByCSId($_SESSION['CSid']);
?>

<h1>Devices</h1>

<h3 class="headder-devices">Controller</h3><a href="?page=addController"><button type="button" class="btn btn-devices"><span class="glyphicon glyphicon-plus"></span> Add Controller</button></a>
<table id="controller" class="tablesorter">
	<thead>
		<tr>
			<th>Status</th><th>Name</th><th>Location</th><th>Last User, Time</th><th>Details</th>
		</tr>
	</thead>
	<tbody>
			<?php 
				foreach($controllerArray as $controller){
					echo "<tr>
							<td>".printStatus($controller['status'])."</td>
							<td>".$controller['name']."</td>
							<td>".$controller['location']."</td>
							<td>";	
							if(isset($controller['lastUsedByProfile'])){
								echo $controller['lastUsedByProfile'].": ".$controller['lastTimeUsedFrom']." - ";
								if(isset($controller['lastTimeUsedTo']))
									echo $controller['lastTimeUsedTo'];
								else
									echo "In Use";
							}
							else
								echo "Has not been used yet";
							echo'</td>
							<td><a href="?page=detailsController&controller='.$controller['CSerieNo'].'"><button type="button" class="btn btn-default btn-xs">Details</button></a></td>
						</tr>';
				}
			?>
	</tbody>	
</table>

<h3 class="headder-devices">Tag</h3><a href="?page=addTag"><button type="button" class="btn btn-devices"><span class="glyphicon glyphicon-plus"></span> Add Tag</button></a>
<table id="tag" class="tablesorter">
	<thead>
		<tr>
			<th>Active</th><th>User</th><th>Tag Name</th><th>Last Used, time</th><th>Details</th>
		</tr>
	</thead>
	<tbody>
			<?php 
				print_r($tagArray);
			
				foreach($tagArray as $tag){
					echo "<tr>
							<td>".printActiveTag($tag['active'],$tag['profileId'])."</td>
							<td>".$tag['profilename']."</td>
							<td>".$tag['name']."</td>
							<td>";
							if(isset($tag['lastUsedController'])){
								echo $tag['lastUsedController'].": ".$tag['lastTimeUsedFrom']." - "; 
								if(isset($tag['lastTimeUsedTo']))
									echo $tag['lastTimeUsedTo'];
								else
									echo "In Use";
							}
							else
								echo "Has not been used yet";
							echo'</td>
							<td><a href="?page=detailsTag&tag='.$tag['TSerieNo'].'"><button type="button" class="btn btn-default btn-xs">Details</button></a></td>
						</tr>';
				}
			?>
		
	</tbody>
</table>
<div id="warningContainer">
</div>

<script>
$(document).ready(function(){ 
        $("#controller").tablesorter(); 
		$("#tag").tablesorter(); 
    } 
); 

$(document).ready(function(){
		$("input.activeToggler").click(function() {
			if($(this).is(':checked')) //See if Active or false
				var isChecked = "1";
			else
				var isChecked = "0";
				
			var profileId = $(this).val();
			
			$.ajax({
				type: "POST",
				url: "ajax/setActiveTag.php",
				data: { CSId: globalCSId, profileId: profileId, active: isChecked }
			}).done(function( msg ) {
				alert( "Data Saved: " + msg );
			});
			
			(function (el) {
				setTimeout(function () {
					el.children().remove('div');
				}, 2500);
			}($("#warningContainer").append(getAlertString('info','Testing Msg'))));
		});
	}
);

function getAlertString(alertType,alertMsg){
	return '<div class="alert alert-'+alertType+' fade in informationDevices"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+alertMsg+'</div>';
}
</script>