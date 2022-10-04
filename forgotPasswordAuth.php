<?php
    session_start();
    $errorMessage = '';
    if (isset($_POST['verificationCode']))
    {
        $enteredCode=$_POST['verificationCode'];
        // after login we move to the main page

        if($enteredCode==$_SESSION['resetKey'])
        {
           
                header('Location: forgotPasswordChange.php');
                exit;
            
        }
        else
        {
            $errorMessage = 'Sorry incorrect verification code, check that you entered it correctly';
        }
       
    }
    else{
        $errorMessage = 'Please enter the verification code that was sent to your email';
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
</html>


