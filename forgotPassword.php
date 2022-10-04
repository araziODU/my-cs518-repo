<?php
//start a session 
session_start();
//using the PHPMailer to send mail, ALL CODE FOR PHPMailer was taken from https://github.com/PHPMailer/PHPMailer and all credit goes to them. All of PHPmailer files are in "PHPMailer" folder
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'authentication.php';


$errorMessage='';


if (isset($_POST['email']))
{
	//get userID 
	$loginUserId = $_POST['email'];

	
		//connect to the database
        $connection = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
		// Authenticate the user
		if (authenticateUserForgotPassword($connection, $loginUserId))
		{
			//send an email and redirect to the verification page using PHPMailer
				//code modeled after https://github.com/PHPMailer/PHPMailer examples
				//generate a dual authenticaiton key
				$_SESSION['resetKey']=random_int(100000, 999999);
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
				$mail->Subject='Password Reset';
				$mail->Body='Your Reset Password Key is : '. $_SESSION['resetKey'];
				if (!$mail->send()) {
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				} else {
					header('Location: forgotPasswordAuth.php');
				}
		}
		
		 // close the connection
 		$connection->close();

}
?>

<html>
	<head>
		<title>Forgot Password</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
		<link rel="stylesheet" href="styles.css" />
    
	</head>
		<div id="conteneur">
		  <div id="header">Forgot Password</div>
		
	
		  <div id="centre">
			<h1>Enter your email:</h1>

			<Strong > <?php echo $errorMessage  ?> </Strong>
            <form action="" method="post" name="frmForgot" id="frmForgot">
		    </div>
            <table width="400" border="1" align="center" cellpadding="2" cellspacing="2">
				<tr>
					<td>Email</td>
					<td><input name="email" type="text" id="email"></td>
					<td><input name="btnVery" type="submit" id="btnVerify" value="Submit"> 
                    <a href="login.php"><input name="cancelBtn" type="button" id="cancelBtn" value="Cancel"></a>
					
				</tr>
            </table>
			<br>
            </form>

			</div>
		</div>
</html>