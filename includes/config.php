<?php

ob_start(); // Turn on output buffering
session_start();

date_default_timezone_set("Asia/Kuala_Lumpur");

try{
	$con = new PDO("**Your Host Name**", "**Your DB name**", "**Your DB Password**");
	$con-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch(PDOException $e){
	echo "Connection failed: " . $e->getMessage();
}

?>
