<?php

namespace Xsolla\Model
{
	class SQLModel extends TableModel
	{
		private $address = 'localhost';
		private $login = 'root';
		private $password = 'root';
		private $connection = null;
		private $db_name = 'xsolla';
		private $table_name = 'storage';
		private $headers = null;
		
		public function __construct(){
		}
		
		public function __destruct(){
		}
		
		public function load(){
			$connect = mysql_connect("localhost", "root", "root");
			if (!$connect) {
				die("Connection failed: " . mysql_error());
			}
			
			$db = mysql_select_db($db_name, $connect);
			
			if(!$db){
				$sql = 'CREATE DATABASE ' . $db_name;

				if (!mysql_query($sql, $connect)) {
				  die('Error creating database: ' . mysql_error() . "\n");
				}
			}
			
			//If db is not previously specified it's full of junk
			$result = mysql_query(sprintf("CREATE TABLE IF NOT EXISTS %s (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				field1 VARCHAR(100),
				field2 VARCHAR(100),
				field3 VARCHAR(100)
			)", $table_name));
			
			if(!$result) {
				die('Error creating table: ' . mysql_error() . "\n");
			}
			
			$result = mysql_query(sprintf("SELECT COLUMN_NAME 
				FROM INFORMATION_SCHEMA.COLUMNS 
				WHERE TABLE_NAME = '%s' ", $table_name));
				
			//preloading headers from table
			while($res = mysql_fetch_assoc($result)){
				$headers[] = $res['COLUMN_NAME'];
			}
		}
		
		public function save(){
			mysql_close($connect);
		}
		
		public function getHeaders() {
			return array_map(function($head) {return $head;}, $headers);
		}
		
		public function addRow(array $row){
			$request = sprintf("INSERT INTO %s VALUES (", $table_name);
			for($i = 0; $i < count($headers); $i++) {
				if($i = count($headers) - 1)
					$request .= $header . ",";
				else 
					$request .= $header . ") VALUES (";
			}
			
			for($i = 0; $i < count($row); $i++) {
				if($i = count($row) - 1)
					$request .= "'" . $row[$i] . "',";
				else 
					$request .= "'" . $row[$i] . "')";
			}
			
			mysql_query($request);
		}
		/**
		 * @param int $offset
		 * @param array $row
		 * @return boolean
		 */
		public function updateRow($offset, array $row){}
		/**
		 * @param int $offset
		 * @return array|null
		 */
		public function getRow($offset){}
		/**
		 * @param $offset integer
		 * @return boolean
		 */
		public function deleteRow($offset){}
		/**
		 * @return array
		 */
		public function getRows(){}
		/**
		 * @return int
		 */
		public function countRows(){}
	}
}