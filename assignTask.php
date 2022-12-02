<html>
	<head>
	
		<title>Admin Page</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
		<link rel="stylesheet" href="styles.css" />
		<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
		<link rel="manifest" href="favicon/site.webmanifest">
	</head>
		<div id="conteneur">
		  <div id="header"> <img src="favicon/favicon-32x32.png"> Figure Annotation | Admin Page</div>
		
		<?php include 'adminNavbar.php'; ?>
	
		  <div id="centre">
			<h1>Assign Tasks</h1>
			</div>


			<table width="500" border="1" align="center" cellpadding="2" cellspacing="2">
			<tr>
					<th>User</th>
					<th> Currently Assigned groups</th>
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

			


			$sqlQuery="SELECT userId, firstName, lastName ,email FROM users WHERE isApproved=1 ORDER BY firstName";
			$results =$connection->query($sqlQuery);

			$rows=mysqli_num_rows($results);


			if($rows>0)
			{
				while($singleRow =$results->fetch_assoc())
				{
							echo "<tr>";
							echo "<td>" . $singleRow['email'] . "</td>";
							
							echo"<td style=text-align:center>";
							$sqlQuery2="SELECT DISTINCT group_id FROM annotations WHERE user_id=".$singleRow['userId'] ." ORDER BY group_id";
							$connection2 = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
							$results2 =$connection2->query($sqlQuery2);
							$rows2=mysqli_num_rows($results2);
							if($rows2>0)
							{
								$first=true;
								while($singleRow2 =$results2->fetch_assoc())
								{
									if($first==true)
									{
										echo $singleRow2['group_id'];
										$first=false;
									}
									else
									{
										echo ", ".$singleRow2['group_id'];
									}
									
								}
							}



							 echo"</td>";
							echo"<td style=text-align:center><a href='setGroupNumber.php?userId=" .$singleRow['userId'] ."'>Assign Group  </a></td>";
							
							
							echo"</tr>";
			
				
			
					}
				}

				
			  // close the connection
			  $connection->close();
			?>
			</table>	
		</div>
		<div class="myFooter">
			<p >Website created by Alexander Razikov | <img src="favicon/favicon-32x32.png"> Figure Annotation  | <a href = "mailto: arazi002@odu.edu">Contact Me</a></p>
		</div>
</html>