<?php
	session_start();
    require  'authentication.php';
	if(!isset($_GET['email'])){
		die('Email not Provided');
	}
	
	$connection = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
	$email = $_GET['email'];

	$sql = "UPDATE users SET isApproved=1 where email = '$email';";
	$result = $connection->query($sql);
    header('Location: approveNewUsers.php');
	
?>