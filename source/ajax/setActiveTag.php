<?php
	require_once "../database/DBfunctions.php";
	
	//Need CSId, profileId and active
	/*$CSId = $_POST['CSId'];
	$profileId = $_POST['profileId'];
	$active = $_POST['active'];*/
	$CSId = $_GET['CSId'];
	$profileId = $_GET['profileId'];
	$active = $_GET['active'];
	
	if(isset($CSId) && isset($profileId) && isset($active)){
		$tag = new Tag($CSId,$profileId,null,null,$active);
		$result = simpleUpdateDB($tag);
		
		if($result == true){
			echo $result;
		}
		else{
			echo $result;
		}		
	}
	else{
		echo "ERROR: Values are not set.";
	}

?>