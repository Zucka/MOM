<?php
	if($_POST['cancel'] != ""){
		 header('location:?page=devices');
	}
	elseif($_POST['add'] != ""){ //Add Tag if valid
		//Check if all required fields have been filled out
		if($_POST['id'] != "" && $_POST['name'] != ""){
			//Check if TagID already exists and if user exists in this system
			if(true){
				//Add Tag to System
			}
			else{
				printAddTagForm($_POST['id'],$_POST['name'],$_POST['location'],'<h3 style="color:red;">That id is already in use by someone else.</h3>');
			}			
		}
		else{
			printAddTagForm($_POST['id'],$_POST['name'],$_POST['location'],'<h3 style="color:red;">You need to give an id number and a name for the device.</h3>');
		}		
	}
	else{
		printAddTagForm("","","","");
	}
	
	function printAddTagForm($id,$name,$location,$errorMsg){
		echo '
		<h1>Add Controller</h1>

		<div class="product_container">
			<div class="outer-center">
				<div class="product inner-center">
					'.$errorMsg.'
					<form action="?page=addController" method="post" class="addForm">
						<table>
							<tr>
								<td>Id:</td> <td><input type="text" name="id" placeholder="0101010101" value="'.$id.'"> *</td>
							</tr>
							<tr>
								<td>Name:</td> <td><input type="text" name="name" placeholder="Xbox" value="'.$name.'"> *</td>
							</tr>
							<tr>
								<td>Location:</td> <td><input type="text" name="location" placeholder="Living Room" value="'.$location.'"></td>
							</tr>
						</table>
						
						<button class="btn" name="cancel" value="cancel"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</button> <button class="btn" name="add" value="addTag"><span class="glyphicon glyphicon-floppy-disk"></span> Add Tag</button>
					</form>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		';
	}
?>

