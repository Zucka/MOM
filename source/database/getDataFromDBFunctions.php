<?php
	function profilesByCSId($CSId)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columntemp = $theColumns['Profile'];
		$table = $theTables['Profile'];
		$whereClause = $columntemp[1] . " = " . $CSId ;
		$result = $db->query('*', $table, $whereClause );
		$returnArray = null;
		while($row = mysqli_fetch_assoc($result))
		{
			$returnArray[] = $row; 
		}
		return $returnArray;
	}
	
	function controllersByCSId($CSId)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnController = $theColumns['Controller'];
		$table = $theTables['Controller'];
		$whereClause = $columnController[1] . " = " . $CSId;
		$result = $db->query( ' * ', $table, $whereClause );
		
		$returnArray = null;
		while($row = mysqli_fetch_assoc($result))
		{
			$partresult = controllersLastUsedHelpFunc($row['CSerieNo']);
			foreach($partresult as $key => $value)
			{
				$row[$key]= $value;
			}
			$returnArray[] = $row; 
		}
		return $returnArray;
	}

	function tagsByCSId($CSId)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnTag = $theColumns['Tag'];
		$columnProfile = $theColumns['Profile'];
		$table = $theTables['Tag'] . " tag , " . $theTables['Profile'] . " profile";
		$whereClause =  "tag." . $columnTag[1] . " = " . $CSId . " AND " . "tag." . $columnTag[2] . " = profile." . $columnProfile[0];
		$result = $db->query('*', $table, $whereClause );
		$returnArray = null;
		while($row = mysqli_fetch_assoc($result))
		{
			$partresult = tagLastUsed($row['TSerieNo']);
			foreach($partresult as $key => $value)
			{
				$row[$key]= $value;
			}
			$returnArray[] = $row; 
		}
		return $returnArray;
	}

	function controllersLastUsedHelpFunc($controllerID, $limitFrom=0, $limitTo=1)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnCUBT = $theColumns['Controller_used_by_tag'];
		$columnTag = $theColumns['Tag'];
		$columnUser = $theColumns['Profile'];

		$table = $theTables['Controller_used_by_tag'] . " cubt, " . $theTables['Tag'] . " tag," . $theTables['Profile']. " pro";
		$tagDeviceClause = " tag." . $columnTag[0] . " = cubt." . $columnCUBT[0];
		$tagUserClause = "tag." . $columnTag[2] . " = pro." . $columnUser[0];  
		$controller = "cubt." . $columnCUBT[1] . " = " . $controllerID;
		$whereClause = $controller . " AND " . $tagDeviceClause ." AND " . $tagUserClause;
		$ordering = 'lastTimeUsedFrom DESC';
		$otherSQL = 'LIMIT '. $limitFrom . ', '. $limitTo ;
		$selectValues= $columnCUBT[2] . " AS lastTimeUsedFrom , ". $columnCUBT[3] . " AS lastTimeUsedTo, pro.". $columnUser[2] . " AS lastUsedByProfile";
		
		$result = $db->query( $selectValues , $table, $whereClause, $ordering , $otherSQL);
		return  mysqli_fetch_assoc($result);
	}
	
	function tagLastUsed($tagID, $limitFrom = 0, $limitTo = 1)
	{
		$db= new MySQLHelper();
		global $theTables;
		global $theColumns;
		$columnCon =  $theColumns['Controller'];
		$columnCUBT = $theColumns['Controller_used_by_tag'];

		$table = $theTables['Controller_used_by_tag'] . " cubt, "  . $theTables['Controller']. " con";
		$tagControllerClause =   "cubt." . $columnCUBT[1] . " = con." .  $columnCon[0]; 
		$tag = "cubt." . $columnCUBT[0] . " = " . $tagID;
		$whereClause = $tag . " AND " . $tagControllerClause ;
		$ordering = 'lastTimeUsedFrom DESC';
		$otherSQL = 'LIMIT '. $limitFrom . ', '. $limitTo ;
		$selectValues= $columnCUBT[2] . " AS lastTimeUsedFrom , ". $columnCUBT[3] . " AS lastTimeUsedTo, con.". $columnCon[2] . " AS lastUsedController";
		$result = $db->query( $selectValues , $table, $whereClause, $ordering , $otherSQL);
	
		return mysqli_fetch_assoc($result);
	}
	

?>