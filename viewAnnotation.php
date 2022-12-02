
<?php 
//get the information on the annotation panels
session_start();
require 'elastic-php/vendor/autoload.php';
require  'authentication.php';
use Elastic\Elasticsearch\ClientBuilder;
$client = ClientBuilder::create()->build();
if(isset($_GET['fig'])){
    $figName=$_GET['fig'];
}


if(isset($_GET['pic'])){
    $picName=$_GET['pic'];
}

//display the right side
    //display the segemented subfigures
    //display  the 


//display the left side

?>





<html>
	<head>
		<title>Annotation</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
		<link rel="stylesheet" href="styles.css" />
		<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
		<link rel="manifest" href="favicon/site.webmanifest">
        <style>
table {
  font-family: arial, sans-serif;
  border: 1px solid black;
  border-collapse: collapse;
  width: 80%;
  margin-left: 30px;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}

p{
    margin:0 0 0 0;
}
</style>
	</head>
    <body>
		<div id="conteneur">
		  <div id="header"> <img src="favicon/favicon-32x32.png"> Figure Annotation | Annotation</div>
		
		<?php include 'userNavbar.php'; ?>
	
		  <div id="centre">
			<h1>Annotation</h1>
            <form>
            <table>
                <tr>
                    <th>
                    
                    <?php echo $figName; ?>
                        <br> 
                        <img src="figures/<?php echo $picName; ?>" width="200" height ="200">
                        
                        
                        
                        <p>a. Are the original figure segmented correctly?</p>
                        
                        <input type="radio" id="a1" name="q1" value="yes">
                        <label for="a1">yes</label><br>
                        <input type="radio" id="a2" name="q1" value="no">
                        <label for="a2">no</label><br>
                        <input type="radio" id="a3" name="q1" value="unknown">
                        <label for="a3">unknown</label><br>


                        <p>b. How many subfigures should be segmented from the original figure?</p>
                        
                        <input type="text" id="b1" name="q2">
 
                
                    </th>

                    <th>
                        <table>
                            <?php

                            ?>

                        </table>               
                    </th>
                    
                    
                </tr>  
                <tr><th> <input type="submit" value="Submit"></th><th></th></tr> 
            </table>
            
            </form>
			</div>
            
		</div>
		<div class="myFooter">
			<p >Website created by Alexander Razikov | <img src="favicon/favicon-32x32.png"> Figure Annotation  | <a href = "mailto: arazi002@odu.edu">Contact Me</a></p>
		</div>

</body>
</html>