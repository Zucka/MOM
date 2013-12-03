<?php
	require_once "../database/DBfunctions.php";
	
	//Need CSId, tagId and active
	$CSId = $_POST['CSId'];
	$tagId = $_POST['tagId'];
	$active = $_POST['active'];
	/*$CSId = $_GET['CSId'];
	$tagId = $_GET['tagId'];
	$active = $_GET['active'];*/
	
	if(isset($CSId) && isset($tagId) && isset($active)){
		$tag = new Tag($CSId,$tagId,null,null,$active);
		$result = simpleUpdateDB($tag);
		
		if($result == true){
			echo "OK";
		}
		else{
			echo $result;
		}		
	}
	else{
		echo "ERROR: Values are not set.";
	}

?>