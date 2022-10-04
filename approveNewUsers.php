<html>
	<head>
		<title>New Users Requests</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
		<link rel="stylesheet" href="styles.css" />
    
	</head>
		<div id="conteneur">
		  <div id="header">New Users Requests</div>
		
		<?php include 'adminNavbar.php'; ?>
	
		  <div id="centre">
			<h1>List of new users waiting for approval</h1>

			<table width="400" border="1" align="center" cellpadding="2" cellspacing="2">
			<tr>
					<th>Email</th>
					<th>First Name</th>
					<th>Last name</th>
					<th></th>
		
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
			$sqlQuery="SELECT email, firstName, lastName FROM users WHERE isApproved=0 ORDER BY email";
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
							echo "<td>";
							echo "<div class='btn-group'>";
							echo "<a href='approveAction.php?email=" .$singleRow['email'] ."'> Approve </a>";
							echo "</div>";
							echo "</td>";
							echo "</tr>";
				}
			}
		
			 // close the connection
			 $connection->close();
			?>
			</table>
			</div>
		</div>

        <body>

		

        </body>
</html>