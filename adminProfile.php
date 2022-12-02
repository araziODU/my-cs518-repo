

<html>
	<head>
		<title>Admin Profile Page</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
		<link rel="stylesheet" href="styles.css" />
		<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
		<link rel="manifest" href="favicon/site.webmanifest">
	</head>
		<div id="conteneur">
		  <div id="header"> <img src="favicon/favicon-32x32.png"> Figure Annotation | Admin Profile Page</div>
		
		<?php include 'adminNavbar.php'; ?>
	
		  <div id="centre">
			<h1>Admin Profile Information </h1>
			<h2>To edit profile information press "Edit"</h2>
            <?php
	//include information required to access database
	require 'authentication.php'; 

	//start a session 
	session_start();

	//still logged in?
	if (!isset($_SESSION['db_is_logged_in'])
		|| $_SESSION['db_is_logged_in'] != true) {
		//not logged in, move to login page
		header('Location: index.php');
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
	echo "<th></th>";
	echo "</tr>";
	
			
	// fetch table records
	while ($line = $query_result->fetch_assoc()) {
		echo "<tr>\n";
			foreach ($line as $cell) {
				echo "<td> $cell </td>";
			}
			echo "<td  width=200 align=center>";
			echo "<a href='editAdminProfile.php?email=" .$line['email'] ."'>  Edit  </a>";
			echo "</td>";
        echo "</tr>\n";
    }
    echo "</table>";


    // close the connection
    $conn->close();
	}
?>
			</div>
		</div>
		<div class="myFooter">
			<p >Website created by Alexander Razikov | <img src="favicon/favicon-32x32.png"> Figure Annotation  | <a href = "mailto: arazi002@odu.edu">Contact Me</a></p>
		</div>
</html>