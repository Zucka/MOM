<?php
	if(isset($_POST['cancel'])){
		header('location:?page=devices');
	}
	elseif(isset($_POST['save'])){
		if(isset($_POST['user']) && $_POST['user'] != "" ) //Should also check if user is part of CSId
			$user = $_POST['user'];
		else
			$user = null;
		
		if(isset($_POST['name']))
			$name = $_POST['name'];
		else
			$name = "";
		
		$updateTag = new Tag($_SESSION['CSid'],$_POST['id'],$user,$name);
		$result = simpleUpdateDB($updateTag);
		
		if($result == true)
			echo "Success, your tag have now been updated.";
		else
			echo "ERROR: An error has occurred, please try again later.";
		
		printDetailTagForm($_POST['id'],$user,$name,"");
	}
	elseif(isset($_POST['delete'])){  
		$deletionTag = new Tag($_SESSION['CSid'],$_POST['id']);
		$result = removeObjectFromDB($deletionTag);
		
		if($result == true){
			echo "Tag have been deleted.";
		}
		else{
			echo "An error has occurred, please try again later.";
		}
	}
	else{
		$result = getTagByTagId($_GET['tag']);
		if(!empty($result) && $result['CSId'] == $_SESSION['CSid']){
			printDetailTagForm($result['TSerieNo'],$result['profileId'],$result['tagname'],"");
		}
		else
			echo "This tag does not exist in your system.";
	}

	function printDetailTagForm($id,$user,$name,$errorMsg,$hidden = false){
		echo '
			<h1>Details Tag:'.$id.'</h1>

			<div class="product_container">
				<div class="outer-center">
					<div class="product inner-center">
						'.$errorMsg.'
						<form action="?page=detailsTag" onsubmit="return validate(this);" method="post" class="addForm">
							<table>
								<tr>
									<td>Id:</td> <td><input type="text" name="placeHolder" value="'.$id.'" disabled > <input type="hidden" name="id" value="'.$id.'"> *</td>
								</tr>
								<tr>
									<td>User:</td> 
									<td>
										<select name="user">';
											foreach(profilesByCSId($_SESSION['CSid']) as $profile){
												if($profile['PId'] == $user)
													$selected = "selected";
												else
													$selected = "";

												echo '<option value="'.$profile['PId'].'" '.$selected.'>'.$profile['name'].'</option>';
											}
										echo '</select>*
									</td>
								</tr>
								<tr>
									<td>Name:</td> <td><input type="text" name="name" placeholder="John\'s Keyring" value="'.$name.'"></td>
								</tr>
							</table>';
							
							printTagUsage($id,0,10);
							
							echo '
							<button class="btn" name="cancel" value="cancel"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</button> <button class="btn" name="save" value="saveTag"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button> <button class="btn" name="delete" value="deleteTag"><span class="glyphicon glyphicon-trash"></span> Delete</button>
						</form>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			';
	}
	
	function printTagUsage($tagId,$offset,$numberOfActivities){
		$result = getTagActivity($tagId,$offset,$numberOfActivities);
		
		echo '
		<h3 class="headder-devices">Log</h3>
		<table id="logTable" class="tablesorter">
			<thead>
				<tr>
					<th>Device Name</th><th>From</th><th>To</th>
				</tr>
			</thead>
			<tbody>';
			
			if(!empty($result)){
				foreach($result as $row){
					echo '<tr><td>'.$row['lastUsedController'].'</td><td>'.$row['lastTimeUsedFrom'].'</td><td>'.$row['lastTimeUsedTo'].'</td></tr>';
				}
			}
			else
				echo '<tr><td>This tag have not been used yet</td></tr>';
				
		echo '
			</tbody>
		</table>
		';
	}
	
?>

<script>
	function validate(form){
		clicked = $("button[clicked=true]").val();
		if(clicked == "deleteTag"){
			return confirm('Do you really want to delete this tag?');
		}
		else{
			return true;
		}
	}
	$(document).ready(function() {
		$("#logTable").tablesorter();
		
		$("form button").click(function() {
			$("input button", $(this).parents("form")).removeAttr("clicked");
			$(this).attr("clicked", "true");
		});
	});
</script>