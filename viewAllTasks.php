
<html>
	<head>
		<title>Admin Page</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
		<link rel="stylesheet" href="styles.css" />
    
	</head>
		<div id="conteneur">
		  <div id="header">View All Tasks</div>
		

          <table width="500" border="1" align="center" cellpadding="2" cellspacing="2">
			<tr>
					<th>User</th>
					<th>Assigned Group</th>
					<th>Assign Datetime</th>
                    <th>#Compound figures</th>
                    <th>#Finished</th>
			</tr>
		<?php include 'adminNavbar.php'; ?>
	
		  <div id="centre">
			<h1>All Tasks</h1>
			</div>
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
            $sqlQuery="SELECT * FROM mydatabase.annotations as annot
            left join users  as user on annot.user_id =  user.userId order by email, group_id";

            $results =$connection->query($sqlQuery);
            $rows=mysqli_num_rows($results);


			if($rows>0)
			{
				while($singleRow =$results->fetch_assoc())
				{
                    echo "<tr>";
					echo "<td width=500>" . $singleRow['email'] . "</td>";
                    echo "<td>" . $singleRow['group_id'] . "</td>";
                    echo "<td  width=200>" . $singleRow['assign_datetime'] . "</td>";
                    echo "<td>" . $singleRow['numb_compound_fig'] . "</td>";
                    echo "<td>" . $singleRow['finished'] . "</td>";
                    echo "</tr>";



                }

            }

        ?>
        </table>
		</div>
</html>