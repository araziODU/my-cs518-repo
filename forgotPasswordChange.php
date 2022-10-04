<?php

//include database information and user information
require 'authentication.php';

//never forget to start the session
session_start();
$errorMessage = '';

//connect to the database
$connection = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
//check that the all password fields are set
if (isset($_POST['retxtPassword'],$_POST['newPassword'] )){
  
    $loginUserId =$_SESSION['userID'];

        $newPassword = htmlspecialchars($_POST['newPassword']);
        $reNewPassword = htmlspecialchars($_POST['retxtPassword']);
        //check that the new password and the retyped new password are the filled out 
        if(!empty($newPassword) &&!empty($reNewPassword))
        {
            //check that the new password and retyped new password are the asme
            if ($newPassword == $reNewPassword)
            {
                $ps = md5($newPassword);
                $updatePasswordSql="UPDATE users SET password='$ps' where email='$loginUserId';";
                $query=$connection->query($updatePasswordSql);
                $errorMessage =' Password Updated Successfully';
                header('Location: login.php');
                exit;
            }
            else
            {
                $errorMessage='New password and Retyped New passwords do not match';
            }

        }
        else
        {
            $errorMessage='Please fill out new password and retype the new password';
        }
}

?>



<html>
	<head>
		<title>Reset Password</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
		<link rel="stylesheet" href="styles.css" />
	</head>
		<div id="conteneur">
		  <div id="header">Reset Password</div>
	
		  <div id="centre">
			<h1>Reset Password</h1>
		<form action="" method="post" name="frmResetPswd" id="frmResetPswd">
            <Strong> <?php echo $errorMessage ?> </Strong>
		 <table width="430" border="1" align="center" cellpadding="2" cellspacing="2">
          <tr>
		   <td width="170">Type New Password *</td>
		   <td><input name="newPassword" type="password" id="newPassword"></td>
		  </tr>
		  <tr>
		   <td width="150">Retype New Password *</td>
		   <td><input name="retxtPassword" type="password" id="retxtPassword"></td>
		  </tr>
		  <tr>
		   <td width="200">&nbsp;</td>
		   <td><input name="btnLogin" type="submit" id="btnLogin" value="Reset Password"><a href="login.php"><input name="returnBtn" type="button" id="returnBtn" value="Cancel"></a></td>
		  </tr>
		 </table>
		</form>
        </div>
		</div>     



</html>