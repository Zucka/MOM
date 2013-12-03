<?php
	if(isset($_POST['cancel'])){
		 header('location:?page=devices');
	}
	elseif(isset($_POST['add'])){ //Add Controller if valid
		//Check if all required fields have been filled out
		//The first check of isset, is to avoid errors, the last part is to make sure they have a valid value.
		if((isset($_POST['id']) && isset($_POST['name']) && isset($_POST['cost'])) && (is_numeric($_POST['id']) && $_POST['name'] != "" && is_numeric($_POST['cost']))){
			//Check if ControllerID already exists and if user exists in this system
			//'SELECT COUNT(*) FROM controller WHERE CSerieNo = $input' - return nothing or 0
			//'SELECT COUNT(*) FROM profile WHERE CSId = $input1 AND PId = $input2' - return 1
			if(true){
				//Add Controller to System
				if(isset($_POST['location'])){
					$location = $_POST['location'];
				}
				else{
					$location = null;
				}
				$newController = new Controller($_SESSION['CSid'], $_POST['id'] , $_POST['name'] , $location, $_POST['cost'] , '!' );
				$result = simpleInsertIntoDB($newController);
				
				if($result == true){
					echo "Controller has been added<br/>";
					echo '<a href="/?page=devices"><button type="button" class="btn btn-default">Return to overview</button></a>';
				}	
				else{
					echo "ERROR: An error has occurred, please try to add the controller again.";
					printAddControllerForm($_POST['id'],$_POST['name'],$_POST['location'],$_POST['cost'],'',true); //Add button to return with all information still intact.
					
				}
			}
			else{
				printAddControllerForm($_POST['id'],$_POST['name'],$_POST['location'],$_POST['cost'],'<h3 style="color:red;">That id is already in use by someone else.</h3>');
			}			
		}
		else{
			printAddControllerForm($_POST['id'],$_POST['name'],$_POST['location'],$_POST['cost'],'<h3 style="color:red;">You need to give an id number, a cost and a name for the device.</h3>');
		}		
	}
	else{
		printAddControllerForm("","","","","");
	}
	
	function printAddControllerForm($id,$name,$location,$cost,$errorMsg,$hidden = false){
		if($hidden != true){
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
								<tr>
									<td>Cost/Hour:</td> <td><input type="text" name="cost" placeholder="1" value="'.$cost.'"> *</td>
								</tr>
							</table>
							
							<button class="btn" name="cancel" value="cancel"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</button> <button class="btn" name="add" value="addTag"><span class="glyphicon glyphicon-floppy-disk"></span> Add Controller</button>
						</form>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			';
		}
		else{
			echo '
			<form action="?page=addController" method="post" class="addForm">
				<input type="hidden" name="id" value="'.$id.'">
				<input type="hidden" name="name"  value="'.$name.'">
				<input type="hidden" name="location" value="'.$location.'">
				<input type="hidden" name="cost" value="'.$cost.'">
				<button class="btn" name="cancel" value="cancel"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</button> <button class="btn" name="add" value="addTag"><span class="glyphicon glyphicon-floppy-disk"></span> Try Again </button>
			</form>';
		}
	}
?>

