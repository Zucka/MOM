<?php
include_once "configDB.php";

class MySQLHelper
{
	private $con;
	
	function __construct() 
	{
		
		$this->con = mysqli_connect($GLOBALS['server'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);

		//check connection
		if(mysqli_connect_errno($this->con))
		{
			return mysqli_connect_error();
		}
	}
	

	function __destruct() {
	
       mysqli_close($this->con);
	   
    }
	
	   function executeSQL($sql)
   {
		return mysqli_query($this->con, $sql);
		
   }
   /**
   All input as a string, please
   */
   function insertInto($table , $values, $column = null)
   {
		//"INSERT INTO table_name (column1, column2, column3,...) VALUES (value1, value2, value3,...)"
		$sql="INSERT INTO " . $table;
		if($column != NULL)
		{
			$sql.= " " . $column;
		}
		$sql.= " VALUES " . $values;

		
		return self::executeSQL($sql);
   }
   /**
   All input as a string, please
   */
   function update( $table, $columnValue, $where = null)
   {
		/*"UPDATE table_name
		SET column1=value, column2=value2,...
		WHERE some_column=some_value"*/
		
		$sql="UPDATE " . $table . " SET " . $columnValue;
		if($where!=NULL)
		{
			$sql.= " WHERE " . $where;
		}
		
		return self::executeSQL($sql);
   }
   /**
   All input as a string, please
   */
   function delete($table, $where=null)
   {
		$sql="DELETE FROM " . $table . " ";
		if($where != null)
		{
			$sql.= "WHERE " . $where;
		}
		/*"DELETE FROM table_name
		WHERE some_column = some_value"*/
		return self::executeSQL($sql);
   }
   
   /**
   All input as a string, please. SELECT, FROM, WHERE, ORDER BY, DISTINCT keywords are addeed here st. sql query is completed  
   */
   function query($selectValues, $tables, $whereClause = NULL, $ordering = NULL, $otherSQL = NULL, $distinctResults = false)
   {
		$sql="SELECT ";
		if($distinctResults == true)
		{
		$sql .= "DISTINCT ";  
		}
		$sql .= $selectValues . " FROM " . $tables;
		
		if($whereClause != NULL)
		{
			$sql .= " WHERE " . $whereClause;
		}
		if($ordering != NULL)
		{
			$sql .= " ORDER BY " . $ordering;
		}
		if($otherSQL != NULL)
		{
			$sql .= " " . $otherSQL;
		}
		
		/*"SELECT column_name(s)
		FROM table_name
		WHERE column_name operator value
		ORDER BY column_name(s) ASC|DESC" */
   
		return self::executeSQL($sql);
   }
   function real_escape_string($string)
   {
   		return $this->con->real_escape_string($string);
   }  
}
?>