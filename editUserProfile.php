
<?php

//include information required to access database
require 'authentication.php'; 

//start a session 
session_start();
$errorMessage='';
//still logged in?
if (!isset($_SESSION['db_is_logged_in'])
	|| $_SESSION['db_is_logged_in'] != true) {
	//not logged in, move to login page
	header('Location: index.php');
	exit;
} 
if(!isset($_GET['email'])){
	die('Email not Provided');
}
//logged in
// Connect database server
$conn = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);

// Prepare query
$table = "users";
$uid = $_SESSION['userID'];
$sql = "SELECT email, firstName, lastName FROM $table where email = '$uid'";

// Execute query
$query_result = $conn->query($sql);
if (!$query_result) {
	echo "Query is wrong: $sql";
		die;
}
$row=$query_result->fetch_assoc();

//is email and Password provided?
if (isset($_POST['email'],$_POST['firstName'],$_POST['lastName'] )){
	$firstName = htmlspecialchars($_POST['firstName']);
	$lastName = htmlspecialchars($_POST['lastName']);
	$email = htmlspecialchars($_POST['email']);

	
	//is name filled out
	if(!empty($firstName))
		{
			$sql = "UPDATE users SET firstName='$firstName' where email = '$uid';";
			$result = $conn->query($sql);
			
		}
		if(!empty($lastName))
		{
			$sql = "UPDATE users SET lastName='$lastName' where email = '$uid';";
			$result = $conn->query($sql);
		}
		if(!empty($email))
		{
			$sql = "UPDATE users SET email='$email' where email = '$uid';";
			$result = $conn->query($sql);
			$_SESSION['userID']=$email;
		}
	
	

		header('Location: userProfile.php');

		 // close the connection
 		$conn->close();
		exit;


}


?>
<html>
	<head>
		<title>Profile Page</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
		<link rel="stylesheet" href="styles.css" />
		<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
		<link rel="manifest" href="favicon/site.webmanifest">
	</head>
		<div id="conteneur">
		  <div id="header"> <img src="favicon/favicon-32x32.png"> Figure Annotation | Profile Page</div>
		
		<?php include 'userNavbar.php'; ?>
	

	<body>
		
		  <div id="centre">
			<h2>Leave fields blank if no edits are needed for those fields.</h2>
			
		
			
		
			<form action="" method="post" name="frmEdit" id="frmEdit">
            <Strong> <?php echo $errorMessage ?> </Strong>
		 <table width="700" border="1" align="center" cellpadding="2" cellspacing="2">
         <tr>
		   <td width="400">Email Address </td>
		   <td><?php echo $row['email']?></td>
		   <td><input name="email" type="text" id="email"></td>
		  </tr>
        


		  <tr>
		   <td width="400">First Name </td>
		   <td><?php echo $row['firstName']?></td>
		   <td><input name="firstName" type="text" id="firstName"></td>
		  </tr>


		  <tr>
		   <td width="400">Last Name</td>
		   <td><?php echo $row['lastName']?></td>
		   <td><input name="lastName" type="text" id="lastName"></td>
		  </tr>


		  <tr>
		   <td width="400">&nbsp;</td>
		   <td align='center'><input name="btnFinish" type="submit" id="btnFinish" value="Finish"><a href="userProfile.php"><input name="returnBtn" type="button" id="returnBtn" value="Cancel"></a></td>
		  </tr>
		 </table>
		</form>
        </div>
		</div>
		<div class="myFooter">
			<p >Website created by Alexander Razikov | <img src="favicon/favicon-32x32.png"> Figure Annotation  | <a href = "mailto: arazi002@odu.edu">Contact Me</a></p>
		</div>
	</body>
</html>


