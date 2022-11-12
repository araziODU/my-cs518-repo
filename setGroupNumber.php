

<html>
	<head>
		<title>Admin Page</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
		<link rel="stylesheet" href="styles.css" />
    
	</head>
		<div id="conteneur">
		  <div id="header">Admin Page</div>
		
		<?php include 'adminNavbar.php'; ?>
	
		  <div id="centre">
			<h1>Assign Task</h1>
			</div>


			<table width="500" border="1" align="center" cellpadding="2" cellspacing="2">
			<tr>
					<th>User</th>
					<th>Assigned groups(a drop-down menu)</th>
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
			$userId = $_GET['userId'];

            $_SESSION['userIdForGroup']=$_GET['userId'];
			//connect to the database
			$connection = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
			$sqlQuery="SELECT userId, firstName, lastName FROM users WHERE userId='$userId' ORDER BY firstName";
			$results =$connection->query($sqlQuery);

			$rows=mysqli_num_rows($results);


			if($rows>0)
			{
				while($singleRow =$results->fetch_assoc())
				{
							echo "<tr>";
							echo "<td>" . $singleRow['firstName'] .$singleRow['lastName']. "</td>";
							
                           echo"<td style=text-align:center> <form action=addUserToGroup.php>
                            <select name=group >";

                            $sqlQuery2="SELECT DISTINCT groupID FROM figure_segmented_nipseval_test2007";
							$connection2 = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
							$results2 =$connection2->query($sqlQuery2);
                            $rows2=mysqli_num_rows($results2);
                            if($rows2>0)
							{
								while($singleRow2 =$results2->fetch_assoc())
								{
									echo "<option value=".$singleRow2['groupID'].">".$singleRow2['groupID']."</option>";
									
								}
							}



                            echo"</td>
                            <td> <input type=submit value=Submit> </td>
                            </form>
							";

							
							echo"</tr>";
			
				
			
					}
				}

				
			  // close the connection
			  $connection->close();
			?>
			</table>	
		</div>
</html>