<html>
	<head>
		<title>Approved Users</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
		<link rel="stylesheet" href="styles.css" />
		<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
		<link rel="manifest" href="favicon/site.webmanifest">
	</head>
		<div id="conteneur">
		  <div id="header"> <img src="favicon/favicon-32x32.png"> Figure Annotation | Approved Users</div>
		
		<?php include 'adminNavbar.php'; ?>
	
		  <div id="centre">
			<h1>List of all approved Users</h1>

			<table width="400" border="1" align="center" cellpadding="2" cellspacing="2">
			<tr>
					<th>Email</th>
					<th>First Name</th>
					<th>Last name</th>
		
			</tr>
			<?php
			 session_start();
			 require 'authentication.php';	
			

			//is the person accessing this page logged in?
			if (!isset($_SESSION['db_is_logged_in'])
				|| $_SESSION['db_is_logged_in'] != true) {
				// not logged in, go to login page
				header('Location: index.php');
				exit;
			} 
			
			//connect to the database
			$connection = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
			$sqlQuery="SELECT email, firstName, lastName FROM users WHERE isApproved=1 ORDER BY email";
			$results =$connection->query($sqlQuery);

			$rows=mysqli_num_rows($results);


			if($rows>0)
			{
				while($singleRow =$results->fetch_assoc())
				{
							echo "<tr>";
							echo "<td>" . $singleRow['email'] . "</td>";
							echo "<td>" . $singleRow['firstName'] . "</td>";
							echo "<td>" . $singleRow['lastName'] . "</td>";
							echo "</tr>";
				}
			}
			  // close the connection
			  $connection->close();
			?>
			</table>
			</div>
		</div>
		<div class="myFooter">
			<p >Website created by Alexander Razikov | <img src="favicon/favicon-32x32.png"> Figure Annotation  | <a href = "mailto: arazi002@odu.edu">Contact Me</a></p>
		</div>

        <body>

		

        </body>
</html>