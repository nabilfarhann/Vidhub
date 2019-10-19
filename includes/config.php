<?php

ob_start(); // Turn on output buffering
session_start();

date_default_timezone_set("Asia/Kuala_Lumpur");

try{
	$con = new PDO("mysql:dbname=heroku_4f44aeeb4439d12;host=us-cdbr-iron-east-02.cleardb.net", "bdfe6f5cb6b764", "975b0a1f");
	$con-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch(PDOException $e){
	echo "Connection failed: " . $e->getMessage();
}

?>