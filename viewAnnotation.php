
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

$errorMessage="";
if(isset($_GET['pic'])){
    $picName=$_GET['pic'];
    //echo $picName;
    $query= $client -> search([
      'index' => 'annotations',
      'body' => [
          'query' => [
              'bool' => [
                  'must' => [
                      'match_phrase' => ['compoundfigure_file' => $picName]
                  ]
              ]
          ]
      ]
  ]);

  //echo $query->getBody();
  if ( $query['hits']['total']['value'] >= 1)
  {
    //echo "<br>total items:".$query['hits']['total']['value'];
      $results = $query['hits']['hits'][0]['_source']['assignments']['annotations']['subfigures'];
     // echo "<br>testField:       ".$query['hits']['hits'][0]['_source']['compoundfigure_file']; WORKS
    // echo "<br>testField:       ".$query['hits']['hits'][0]['_source']['assignments']['annotations']['subfigures'][0]['subfigure_id'];
    $queryIdParam=$query['hits']['hits'][0]['_id'];
  }
}
$formattedfig=str_replace(' ',"%20",$figName);
$formattedpic=str_replace(' ',"%20",$picName);
$redirectHeader="viewAnnotation.php?fig=".$formattedfig."&pic=".$formattedpic;
$id='a';
if ( isset($_POST['action']))
                {
                  //validate the compound figure

                  if(!isset($_POST['question1']) || empty($_POST['question2']) )
                  {
                    $errorMessage="Please fill out all questions";
                  
                  }
                  //validate the segemented figures
                  else{
                  
                  $startingFigure='a';
                  $questionNumber=1;
                  $missingData=false;
                 
                  //for each segemented figure check that a. and c. are answered
                  for( $startingFigure; $startingFigure<$id ; $startingFigure++ )
                  {
                    if(!isset($_POST[$startingFigure.'1']) || !isset($_POST[$startingFigure.'3']) )
                    {
                      $missingData=true;
                    }
                    else if($_POST[$startingFigure.'1']=="no" && empty($_POST[$startingFigure.'2']) )
                    {
                      $missingData=true;
                    }
                    else if($_POST[$startingFigure.'3']=="no" && empty($_POST[$startingFigure.'4']) )
                    {
                      $missingData=true;
                    }
                  }
                    if( $missingData==true)
                    {
                      $errorMessage="Please fill out all questions";
                  
                    }
                    else
                    {
                      //update the database to increment the count of completed
                      $connection2 = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
                                
                                $sql2 = "select * from figure_segmented_nipseval_test2007 where  figure_file= '$picName' LIMIT 1"; 
                                $sqlresult2 = $connection2->query($sql2);
                                $rows2=mysqli_num_rows($sqlresult2);
                                if($rows2>0)
                                                {
                                                    $singleRow =$sqlresult2->fetch_assoc();
                                                    $groupId=$singleRow['groupID'];
                                                    $user=$_SESSION['userID'];
                                                    $sql4="select * from users where email =  '$user'";
                                                    $sqlresult4 = $connection2->query($sql4);
                                                    $singleRow4=$sqlresult4->fetch_assoc();
                                                    $userId= $singleRow4['userId'];
                                                    echo $userId;
                                                    $sql3="Update annotations set `finished`=`finished`+1  where `group_id`= '$groupId' and `user_id`=' $userId'";
                                                    $sqlresult3 = $connection2->query($sql3);
                                                }


                      //udpate the annotations in elastic search so they don't show up again
                      $subfigures=array();
                      $startingFigure='a';
                                    foreach( $results as $r)
                                    {
                                      $singleFigure=[
                                        
                                        "subfigure_id" => $r['subfigure_id'],
                                        //storing empty string
                                        "object_correct" => $_POST[$startingFigure.'1'],
                                        "object" => $r['object'], //=> null,
                                        //storing empty string
                                        "aspect_correct" => $_POST[$startingFigure.'3'],
                                        "aspect" => $r['aspect'] //=> null,
                                     
                                       
                
                                    ];
                                        array_push($subfigures, $singleFigure);
                                        $startingFigure++;
                                    }
                      $updated= $client->update([
                        'index' => 'annotations',
                        'id' => $queryIdParam,
                        'body' => [
                          'doc' =>[
                            'assignments'=> [
                                "annotations" => [
                                    "seg_correct" => $_POST['question1'], //"no",
                                    "n_subfigure" =>$_POST['question2'], // $rows2,
                                    "subfigures"=>
                                       $subfigures
        
                                                  
                            ]
                        ]
                        ]
                        ]
                    ]);

                      header('Location: search.php?query=&action=SearchAnnotationTasks');
                    }

                }

                    //if a. is "no" and b. is empty show error
                    // if c is "no" and d is empty show error                  
                }
                
                
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
  table-layout: fixed ;
  width: 90%;
  margin-left: 30px;
}

td, th {
  border: 5px solid #dddddd;
  text-align: left;
  padding: 8px;
  width: 25% ;
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
      <Strong style="color: red"> <?php echo $errorMessage ?> </Strong>
            <form action="" method="post">
            <table>
              
                <tr>
                    <th>
                    <h2>Compound Figure</h2>
                    
                        <br> 
                        <img src="figures/<?php echo $picName; ?>" width="50%" height ="50%"><br>
                       <p>File name: <?php echo $picName; ?> </p>
                       <p>Figure name: <?php echo $figName; ?> </p>
                        
                        
                        <br><p>a. Are the original figure segmented correctly?</p>
                        
                        <input type="radio" id="compound1" name="question1" value="yes">
                        <label for="compound1">yes</label><br>
                        <input type="radio" id="compound2" name="question1" value="no">
                        <label for="compound2">no</label><br>
                        <input type="radio" id="compound3" name="question1" value="unknown">
                        <label for="compound3">unknown</label><br>


                        <p>b. How many subfigures should be segmented from the original figure?</p>
                        
                        <input type="text" id="compound4" name="question2">
 
                
                    </th>

                    <th>
                    <h2>Segmented Figures</h2>
                        <table>
              
                            <?php
                            if(isset($results)) {
                              
                             
                              
                              //echo $results['hits'][0]['_source']['assignments'];//['annotations']['subfigures'][0]['subfigure_id'];
                              foreach($results as $r) //['hits']['_source']['assignments']['annotations']['subfigures']
                              {  $idNumb=1;
                                $q=1;
                                $connection = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
                                $subName=$r['subfigure_id'];
                                $sql = "select * from figure_segmented_nipseval_test2007 where  subfigure_file= '$subName'"; 
                                $sqlresult = $connection->query($sql);
                                $rows=mysqli_num_rows($sqlresult);
                                if($rows>0)
                                                {
                                                    $singleRow =$sqlresult->fetch_assoc();
                                                }
                            ?>

                  <div class="result">
                        
                        
                        <th><img src="figures/<?php 
                       
                        echo $r['subfigure_id']; 
                        
                        ?>" width="80%" height ="80%" >
                       <p>Caption: <?php echo $singleRow['caption']?> </p> 
                       <p>Figure ID: <?php echo $singleRow['figid']?></p> 
                       <p>Object: <?php echo $singleRow['object']?></p> 
                       <p>Aspect: <?php echo $singleRow['aspect']?></p> <br>

                       <p>a. Is the object identified correctly?</p>
                        <input type="radio" id=<?php echo $id.$idNumb; ?> name=<?php echo $id.$q; ?> value="yes">
                        <label for=<?php echo $id.$idNumb ?>>yes</label><br>
                        <input type="radio" id=<?php $idNumb++; echo $id.$idNumb; ?> name=<?php echo $id.$q; ?> value="no">
                        <label for=<?php echo $id.$idNumb; ?>>no</label><br>
                        <input type="radio" id=<?php $idNumb++; echo $id.$idNumb; ?> name=<?php echo $id.$q; ?> value="unknown">
                        <label for=<?php echo $id.$idNumb; ?>>unknown</label><br>

                        <p>b. What's the correct object if you answered "no" above?</p>
                        
                        <input type="text" id=<?php $idNumb++; echo $id.$idNumb; ?> name=<?php $q++; echo $id.$q; ?>>


                        <p>c. Is the aspect identified correctly?</p>
                        <input type="radio" id=<?php $idNumb++; echo $id.$idNumb; ?> name=<?php $q++; echo $id.$q; ?> value="yes">
                        <label for=<?php echo $id.$idNumb ?>>yes</label><br>
                        <input type="radio" id=<?php $idNumb++; echo $id.$idNumb; ?> name=<?php echo $id.$q; ?> value="no">
                        <label for=<?php echo $id.$idNumb; ?>>no</label><br>
                        <input type="radio" id=<?php $idNumb++; echo $id.$idNumb; ?> name=<?php echo $id.$q; ?> value="unknown">
                        <label for=<?php echo $id.$idNumb; ?>>unknown</label><br>

                        <p>d. What's the correct aspect if you answered "no" above?</p>
                        
                        <input type="text" id=<?php $idNumb++; echo $id.$idNumb; ?> name=<?php $q++; echo $id.$q; ?>>
                       
                      </th>

                              </div>

                            <?php
                             $id++;
                             
                            }
                            }
                            ?>

                        </table>    

                    </th>
                    
                    
                </tr>  
                <tr><th> <input type="submit"  name="action"  value="Search" /></th><th></th></tr> 
                
            </table>
            
            </form>
			</div>
            
		</div>
		<div class="myFooter">
			<p >Website created by Alexander Razikov | <img src="favicon/favicon-32x32.png"> Figure Annotation  | <a href = "mailto: arazi002@odu.edu">Contact Me</a></p>
		</div>

</body>
</html>