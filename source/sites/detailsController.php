<?php
	if(isset($_POST['cancel'])){
		header('location:?page=devices');
	}
	elseif(isset($_POST['save'])){ //Currently it is only viewing, not saving.
		printDetailsControllerForm($_GET['controller'],'test','','1');
	}
	elseif(isset($_POST['delete'])){
		$deletionController = new Controller($_SESSION['CSid'],$_POST['id']);
		$result = removeObjectFromDB($deletionController);
		
		if($result == true){
			echo "Controller have been deleted.";
		}
		else{
			echo "An error has occurred, please try again later.";
		}
	}
	else{
		printDetailsControllerForm($_GET['controller'],'test','','1');
	}

	function printDetailsControllerForm($id,$name,$location,$cost,$errorMsg = '',$hidden = false){
		
		echo '
		<h1>Details Controller: '.$id.'</h1>

		<div class="product_container">
			<div class="outer-center">
				<div class="product inner-center">
					'.$errorMsg.'
					<form action="?page=detailsController" onsubmit="return validate(this);" method="post" class="addForm">
						<table>
							<tr>
								<td>Id:</td> <td><input type="text" name="placeholder" value="'.$id.'" disabled> <input type="hidden" name="id" value="'.$id.'"> *</td>
							</tr>
							<tr>
								<td>Name:</td> <td><input type="text" name="name" placeholder="Xbox" value="'.$name.'"> *</td>
							</tr>
							<tr>
								<td>Location:</td> <td><input type="text" name="location" placeholder="Living Room" value="'.$location.'"></td>
							</tr>
							<tr>
								<td>Cost/Hour:</td> <td><input type="text" name="cost" placeholder="1" value="'.$cost.'"> *</td>
							</tr>
						</table>
						
						<button class="btn" name="cancel" value="cancel"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</button> <button class="btn" name="save" value="saveController"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button> <button class="btn" name="delete" value="deleteController"><span class="glyphicon glyphicon-trash"></span> Delete</button>
					</form>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		';
	}
?>

<script>
	function validate(form){
		clicked = $("button[clicked=true]").val();
		if(clicked == "deleteController"){
			return confirm('Do you really want to delete this controller?');
		}
		else{
			return true;
		}
	}
	$(document).ready(function() {
		
		$("form button").click(function() {
			$("input button", $(this).parents("form")).removeAttr("clicked");
			$(this).attr("clicked", "true");
		});
	});
</script>