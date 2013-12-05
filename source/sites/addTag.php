<?php
	if(isset($_POST['cancel'])){
		 header('location:?page=devices');
	}
	elseif(isset($_POST['add'])){ //Add Tag if valid
		//Check if all required fields have been filled out
		if($_POST['id'] != "" && $_POST['user'] != ""){
			//Check if TagID already exists and if user exists in this system
			if( isTagIdAvailable($_POST['id']) && existsProfileInCS($_POST['user'],$_SESSION['CSid']) ){
				//Add Tag to System
				$newTag = new Tag($_SESSION['CSid'],$_POST['id'],$_POST['user'],$_POST['name'],"1");
				$result = simpleInsertIntoDB($newTag);
				
				if($result == true){
					echo "Tag has been added<br/>";
					echo '<a href="/?page=devices"><button type="button" class="btn btn-default">Return to overview</button></a>';
					
				}	
				else{
					echo "ERROR: An error has occurred, please try to add the tag again.";
					printAddTagForm($_POST['id'],$_POST['user'],$_POST['name'],'',true);//Add button to return with all information still intact.
				}
			}
			else{
				printAddTagForm($_POST['id'],$_POST['user'],$_POST['name'],'<h3 style="color:red;">That id is already in use by someone else.</h3>');
			}			
		}
		else{
			printAddTagForm($_POST['id'],$_POST['user'],$_POST['name'],'<h3 style="color:red;">You need to chose a user and give an id number.</h3>');
		}		
	}
	else{
		printAddTagForm("","","","");
	}
	
	function printAddTagForm($id,$user,$name,$errorMsg,$hidden = false){
		if($hidden != true){		
			echo '
			<h1>Add Tag</h1>

			<div class="product_container">
				<div class="outer-center">
					<div class="product inner-center">
						'.$errorMsg.'
						<form action="?page=addTag" method="post" class="addForm">
							<table>
								<tr>
									<td>Id:</td> <td><input type="text" name="id" placeholder="0101010101" value="'.$id.'"> *</td>
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
							</table>
							
							<button class="btn" name="cancel" value="cancel"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</button> <button class="btn" name="add" value="addTag"><span class="glyphicon glyphicon-floppy-disk"></span> Add Tag</button>
						</form>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			';
		}
		else{
			echo '
				<form action="?page=addTag" method="post" class="addForm">
					<input type="hidden" name="id" value="'.$id.'">
					<input type="hidden" name="user" value="'.$profile.'">
					<input type="hidden" name="name" value="'.$name.'">
					<button class="btn" name="cancel" value="cancel"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</button> <button class="btn" name="add" value="addTag"><span class="glyphicon glyphicon-floppy-disk"></span>Try Again</button>
				</form>
			';
		}
	}
?>

