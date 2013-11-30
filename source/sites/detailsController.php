<?php
	printDetailsControllerForm($_GET['controller'],'test','','1');

	function printDetailsControllerForm($id,$name,$location,$cost,$errorMsg = '',$hidden = false){
		
		echo '
		<h1>Details Controller: '.$id.'</h1>

		<div class="product_container">
			<div class="outer-center">
				<div class="product inner-center">
					'.$errorMsg.'
					<form action="?page=addController" method="post" class="addForm">
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