

<html>
	<head>
		<title>Profile Page</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
		<link rel="stylesheet" href="styles.css" />
	</head>
		<div id="conteneur">
		  <div id="header">Profile Page</div>
		
		<?php include 'adminNavbar.php'; ?>
	
		  <div id="centre">
			<h1>Profile Information </h1>
            <?php
	//include information required to access database
	require 'authentication.php'; 

	//start a session 
	session_start();

	//still logged in?
	if (!isset($_SESSION['db_is_logged_in'])
		|| $_SESSION['db_is_logged_in'] != true) {
		//not logged in, move to login page
		header('Location: login.php');
		exit;
	} else {

    //logged in
	// Connect database server
    $conn = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);

	// Prepare query
	$table = "users";
	$uid = $_SESSION['userID'];
	$sql = "SELECT email, firstname, lastname FROM $table where email = '$uid'";

	// Execute query
    $query_result = $conn->query($sql);
    if (!$query_result) {
        echo "Query is wrong: $sql";
            die;
    }

	// Output query results: HTML table
	echo "<table border=1>";
	echo "<tr>";
			
	// fetch attribute names
   	while ($fieldMetadata = $query_result->fetch_field()) {
		echo "<th>".$fieldMetadata->name."</th>";
    }
	echo "</tr>";
			
	// fetch table records
	while ($line = $query_result->fetch_assoc()) {
		echo "<tr>\n";
			foreach ($line as $cell) {
				echo "<td> $cell </td>";
			}
        echo "</tr>\n";
    }
    echo "</table>";
		
    // close the connection
    $conn->close();
	}
?>
			</div>
		</div>
</html>