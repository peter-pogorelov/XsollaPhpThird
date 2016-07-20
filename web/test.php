<?php 
$db_name = 'xsolla';
$connect =  mysql_connect("localhost", "root", "root");
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

mysql_query('DROP TABLE tbl');
$result = mysql_query("CREATE TABLE IF NOT EXISTS tbl (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	field1 VARCHAR(100),
	field2 VARCHAR(100),
	field3 VARCHAR(100)
)");
if(!$result) {
	die('Error creating table: ' . mysql_error() . "\n");
}

$result = mysql_query("INSERT INTO tbl (field1, field2, field3) VALUES ('asdasd', 'asd', 'asdasd')");
if(!$result) {
	die('Error creating table: ' . mysql_error() . "\n");
}
$result = mysql_query("INSERT INTO tbl (field1, field2, field3)VALUES ('asdasd3', 'asd2', 'asdasd1')");
$result = mysql_query("INSERT INTO tbl VALUES ('asdasd5', 'asd6', 'a7ssdasd')");
$result = mysql_query("SELECT * FROM tbl");


while($res = mysql_fetch_assoc($result)){
	print_r($res);
}

if(!$result) {
	die('Error creating table: ' . mysql_error() . "\n");
}

mysql_close($connect);