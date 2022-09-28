
<?php

	
	require 'authentication.php';
    session_start();
	$errorMessage = '';
    //are user ID and Password provided?
	if (isset($_POST['email']) && isset($_POST['password'])) {

		//get userID and Password
		$loginUserId = $_POST['email'];
		$loginPassword = $_POST['password'];
		
		//connect to the database
        $connection = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
		
		// Authenticate the user
		if (authenticateUser($connection, $loginUserId, $loginPassword))
		{
			$approvalField='isApproved';
			$isApproved=getFieldInfo($connection, $loginUserId, $loginPassword,$approvalField);

			if($isApproved=='1')
			{
				//the user id and password match,
				// set the session	
				$_SESSION['db_is_logged_in'] = true;
				$_SESSION['userID'] = $loginUserId;
				$fieldName='type';
				$fieldValue= getFieldInfo($connection, $loginUserId, $loginPassword,$fieldName);
				
				// after login we move to the main page
				if($fieldValue=='Admin')
				{
					header('Location: admin.php');
					exit;
				}
				else
				{
					header('Location: user.php');
					exit;
				}
			}
			else
			{
				$errorMessage='Sorry, your account has not been approved yet. Contact your administrator';
			}
			
		} else {
			$errorMessage = 'Sorry, wrong username / password';
		}
	}

?>

<html>
	<head>
		<title>Sign In</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link rel="stylesheet" href="styles.css" />
	</head>

	<body>
        <div id="conteneur">
		  <div id="header">Sign In</div>
		
	
		  <div id="centre">
			<h1 align="center">Welcome, please log in.</h1>
		
			<Strong > <?php echo $errorMessage  ?> </Strong>
		<form action="" method="post" name="frmLogin" id="frmLogin">
			 <table width="450" border="1" align="center" cellpadding="2" cellspacing="2">
				  <tr>
					<td width="150">Email</td>
					<td><input name="email" type="text" id="email"></td>
				  </tr>
				  <tr>
					<td width="150">Password</td>
					<td><input name="password" type="password" id="password"></td>
				  </tr>
				  <tr>
					<td width="150">&nbsp;</td>
					<td><input name="btnLogin" type="submit" id="btnLogin" value="Login"> 
					<a href="signup.php"><input name="signupBtn" type="button" id="signupBtn" value="Sign Up"></a>
					<a href="forgotPassword.php"><input name="btnForgotPassword" type="button" id="btnForgotPassword" value="Forgot Password?"></a></td>
				  </tr>
			 </table>
		</form>
        
		If you don't have an account, please <a href="signup.php" >sign up</a>.

         </div>
		</div>
	</body>
</html>

