
<?php
session_start();
	
//using the PHPMailer to send mail, ALL CODE FOR PHPMailer was taken from https://github.com/PHPMailer/PHPMailer and all credit goes to them. All of PHPmailer files are in "PHPMailer" folder
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;

	require 'PHPMailer/src/Exception.php';
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';
	require 'authentication.php';
    
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
				$_SESSION['userType']=$fieldValue;
				
				//send an email and redirect to the verification page using PHPMailer
				//code modeled after https://github.com/PHPMailer/PHPMailer examples
				//generate a dual authenticaiton key
				$_SESSION['dualAuthKey']=random_int(100000, 999999);
				$mail = new PHPMailer(true);
				$mail->isSMTP();
				$mail->SMTPDebug = SMTP::DEBUG_SERVER;
				$mail->Host = 'smtp.gmail.com';
				$mail->Port = 465;
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
				$mail->SMTPAuth = true;
				$mail->Username ='twofactorauthenticationcs518@gmail.com';
				$mail->Password='xtkftvijmdqrhfye';
				$mail->setFrom('noreply@gmail.com','noreply');
				$mail->addAddress($loginUserId);
				$mail->Subject='Two Factor Authentication';
				$mail->Body='Your two factor authentication key is: '. $_SESSION['dualAuthKey'];
				if (!$mail->send()) {
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				} else {
					header('Location: dualAuth.php');
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
		<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
		<link rel="manifest" href="favicon/site.webmanifest">
	</head>

	<body>
        <div id="conteneur">
		  <div id="header"> <img src="favicon/favicon-32x32.png"> Figure Annotation | Sign In</div>
		
	
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
		<div class="myFooter">
			<p >Website created by Alexander Razikov | <img src="favicon/favicon-32x32.png"> Figure Annotation  | <a href = "mailto: arazi002@odu.edu">Contact Me</a></p>
		</div>
	</body>
</html>

