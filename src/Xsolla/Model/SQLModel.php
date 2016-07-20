<?php

namespace Xsolla\Model
{
	class SQLModel implements TableModel
	{
		private $address = 'localhost';
		private $login = 'root';
		private $password = 'root';
		private $connect = null;
		private $db_name = 'xsolla';
		private $table_name = 'storage';
		private $headers = null;
		
		public function load(){
			$this->connect = mysql_connect("localhost", "root", "root");
			if (!$this->connect) {
				die("Connection failed: " . mysql_error());
			}
			
			$db = mysql_select_db($this->db_name, $this->connect);
			
			if(!$db){
				$sql = 'CREATE DATABASE ' . $this->db_name;

				if (!mysql_query($sql, $this->connect)) {
				  die('Error creating database: ' . mysql_error() . "\n");
				}
			}
			
			//If db is not previously specified it's full of junk
			$result = mysql_query(sprintf("CREATE TABLE IF NOT EXISTS %s (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				field1 VARCHAR(100),
				field2 VARCHAR(100),
				field3 VARCHAR(100)
			)", $this->table_name));
			
			if(!$result) {
				die('Error creating table: ' . mysql_error() . "\n");
			}
			
			$result = mysql_query(sprintf("SELECT COLUMN_NAME 
				FROM INFORMATION_SCHEMA.COLUMNS 
				WHERE TABLE_NAME = '%s' ", $this->table_name));
				
			//preloading headers from table
			while($res = mysql_fetch_assoc($result)){
				$this->headers[] = $res['COLUMN_NAME'];
			}
		}
		
		public function save(){
			mysql_close($this->connect);
		}
		
		public function getHeaders() {
			return array_map(function($head) {return $head;}, $this->headers);
		}
		
		public function addRow(array $row){
			$request = sprintf("INSERT INTO %s VALUES (", $this->table_name);
			for($i = 0; $i < count($this->headers); $i++) {
				if($i = count($this->headers) - 1)
					$request .= $this->headers[$i] . ",";
				else 
					$request .= $this->headers[$i] . ") VALUES (";
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
		public function updateRow($offset, array $row){
			$request = sprintf("UPDATE %s SET ", $this->table_name);
			for($i = 0; $i < count($headers); $i++) {
				if($i = count($headers) - 1)
					$request .= $headers[$i] . "=" . $row[$i];
				else 
					$request .= $header . "=" . $row[$i];
			}
			
			$request .= sprintf(' WHERE id = %d', $offset);
			
			return mysql_query($request);
		}
		/**
		 * @param int $offset
		 * @return array|null
		 */
		public function getRow($offset){
			$request = sprintf("SELECT * FROM %s WHERE id = %d", $this->table_name, $offset);
			$result = mysql_query($request);
			$res = mysql_fetch_assoc($result);
			$ret = array();
			foreach($res as $key => $value) {
				$ret[] = $value;
			}
			
			return $ret;
		}
		/**
		 * @param $offset integer
		 * @return boolean
		 */
		public function deleteRow($offset){
			$request = sprintf("DELETE FROM %s WHERE id = %d", $this->table_name, $offset);
			$result = mysql_query($request);
			
			return $result;
		}
		/**
		 * @return array
		 */
		public function getRows(){
			$request = sprintf("SELECT * FROM %s", $this->table_name);
			$result = mysql_query($request);
			$ret = array();
			while($res = mysql_fetch_assoc($result)){
				$temp = array();
				foreach($res as $key => $value) {
					$temp[] = $value;
				}
				$ret[] = $temp;
			}
			
			return $ret;
		}
		/**
		 * @return int
		 */
		public function countRows(){
			$request = sprintf("SELECT COUNT(*) FROM %s", $this->table_name);
			$result = mysql_query($request);
			$res = mysql_fetch_assoc($result);
			return $res['COUNT(*)'];
		}
	}
}