<?php
    session_start();
    $errorMessage = '';
    if (isset($_POST['verificationCode']))
    {
        $enteredCode=htmlspecialchars($_POST['verificationCode']);
        // after login we move to the main page

        if($enteredCode==$_SESSION['dualAuthKey'])
        {
            if($_SESSION['userType']=='Admin')
            {
                header('Location: adminProfile.php');
                exit;
            }
            else
            {
                header('Location: userProfile.php');
                exit;
            }
        }
        else
        {
            $errorMessage = 'Sorry incorrect verification code, check that you entered it correctly';
        }
       
    }
    else{
        $errorMessage = '';
    }

			

?>


<html>
	<head>
		<title>Dual Authentication</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
		<link rel="stylesheet" href="styles.css" />
        <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
		<link rel="manifest" href="favicon/site.webmanifest">
    
	</head>
    <body>
		<div id="conteneur">
		  <div id="header"> <img src="favicon/favicon-32x32.png"> Figure Annotation | Dual Authentication</div>
		
        
		  <div id="centre">
			<h1 align="center">Please enter the code that was sent to your email.</h1>
            <Strong > <?php echo $errorMessage  ?> </Strong>
            <form action="" method="post" name="frmLogin" id="frmLogin">
		    </div>
            <table width="310" border="1" align="center" cellpadding="2" cellspacing="2">
				<tr>
					
					<td><input name="verificationCode" type="text" id="verificationCode"></td>
					<td><input name="btnVery" type="submit" id="btnVerify" value="Verify"> 
                    <a href="index.php"><input name="cancelBtn" type="button" id="cancelBtn" value="Cancel"></a>

				</tr>
            </table>
            </form>
		</div>
        </body>
        <div class="myFooter">
			<p >Website created by Alexander Razikov | <img src="favicon/favicon-32x32.png"> Figure Annotation  | <a href = "mailto: arazi002@odu.edu">Contact Me</a></p>
		</div>
        
</html>


