<?php

	//include database information and user information
	require 'authentication.php';

	//never forget to start the session
	session_start();
	$errorMessage = 'Create a new user account';

	//is email and Password provided?
	if (isset($_POST['email'],$_POST['password'],$_POST['retxtPassword'],$_POST['firstName'],$_POST['lastName'] )){

		//get userID and Password
		
		$loginPassword = htmlspecialchars($_POST['password']);
		$reLoginPassword = htmlspecialchars($_POST['retxtPassword']);
		$firstName = htmlspecialchars($_POST['firstName']);
		$lastName = htmlspecialchars($_POST['lastName']);
		$email = htmlspecialchars($_POST['email']);

		if(!empty($loginPassword) &&!empty($reLoginPassword) &&!empty($firstName) &&!empty($lastName) &&!empty($email))
		{
			if(filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				if ($loginPassword == $reLoginPassword) {
				
					//connect to the database
							$conn = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
					
					//table to store username and password
					$userTable = "users"; 
	
					$ps = md5($loginPassword);
						
					$emailCheck="SELECT * FROM $userTable WHERE email='$email'";
	
					$email_query=$conn->query($emailCheck);
	
					if (mysqli_num_rows($email_query)<1)
					{
						// Formulate the SQL statment to find the user
						$sql = "INSERT INTO $userTable ( `firstname`,`lastname`, `email`, `password`)  VALUES ( '$firstName','$lastName', '$email', '$ps')";
						
						// Execute the query
								$query_result = $conn->query($sql)
							or die( "SQL Query ERROR. User can not be created.");
						
						// Go to the login page
						header('Location: index.php');
							exit;
	
					}
					else 
					{
						$errorMessage = "There is an account with that email already";
					}
	
					 // close the connection
			 $conn->close();
				} 
				else {
					$errorMessage = "Passwords do not match";
					
				}
			}
			else
			{
				$errorMessage = "Please enter a valid email";
			}
		
			
		}
		else{
			$errorMessage = "Please fill out all of the required infromation";

		}
	}
		
	
	else{
		$errorMessage = "Please fill out all of the required infromation";
	}
?>

<html>
	<head>
		<title>Sign Up</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link rel="stylesheet" href="styles.css" />
		<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
		<link rel="manifest" href="favicon/site.webmanifest">
	</head>

	<body>
		

        <div id="conteneur">
		  <div id="header"> <img src="favicon/favicon-32x32.png"> Figure Annotation | Sign Up</div>
		  <div id="centre">
			
		

		<form action="" method="post" name="frmSignup" id="frmSignup">
            <Strong> <?php echo $errorMessage ?> </Strong>
		 <table width="400" border="1" align="center" cellpadding="2" cellspacing="2">
         <tr>
		   <td width="150">Email Address *</td>
		   <td><input name="email" type="text" id="email"></td>
		  </tr>
        


		  <tr>
		   <td width="150">First Name *</td>
		   <td><input name="firstName" type="text" id="firstName"></td>
		  </tr>
		  <tr>

		  <tr>
		   <td width="150">Last Name *</td>
		   <td><input name="lastName" type="text" id="lastName"></td>
		  </tr>
		  <tr>
          <tr>
		   <td width="150">Type Password *</td>
		   <td><input name="password" type="password" id="password"></td>
		  </tr>
		  <tr>
		   <td width="150">Retype Password *</td>
		   <td><input name="retxtPassword" type="password" id="retxtPassword"></td>
		  </tr>
		  <tr>
		   <td width="200">&nbsp;</td>
		   <td><input name="btnLogin" type="submit" id="btnLogin" value="Finish"><a href="index.php"><input name="returnBtn" type="button" id="returnBtn" value="Cancel"></a></td>
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
