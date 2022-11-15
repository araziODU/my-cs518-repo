<?php
			 session_start();
			 require 'authentication.php';	
require 'elastic-php/vendor/autoload.php';
use Elastic\Elasticsearch\ClientBuilder;
$client = ClientBuilder::create()->build();

//get the all figure table metadata

$connection = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
$sql = "select * from
(
	select * 
    , row_number() over( partition by figure_file order by figure_file) as rowNum
    
   from figure_segmented_nipseval_test2007
) as a
where rowNum =1 and id >1500"; // run this twice starting from last index ran to increment this as it times out
$result = $connection->query($sql);
$rows=mysqli_num_rows($result);

//$dataArray=[];
if($rows>0)
			{
				while($singleRow =$result->fetch_assoc())
				{
                    $indexed= $client->index([
                        'index' => 'figures',
                        'type' => 'figure',
                        'body' => [
                            'id' =>   $singleRow['id'],
                            'patentID' => $singleRow['patentID'],
                            'patentdate' => $singleRow['patentdate'],
                            'figid' =>  $singleRow['figid'],
                            'caption' => $singleRow['caption'],
                            'object' => $singleRow['object'],
                            'aspect' => $singleRow['aspect'],
                            'figure_file' => $singleRow['figure_file'],
                            'object_title' =>  $singleRow['object_title'],
                            'groupID' => $singleRow['groupID'],
                        ]
                        ]);
				}
			}
            echo "success 1/4";

$connection = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
$sql = "select * from
(
	select * 
    , row_number() over( partition by figure_file order by figure_file) as rowNum
    
   from figure_segmented_nipseval_test2007
) as a
where rowNum =1 and id >1000 and id <=1500"; // run this twice starting from last index ran to increment this as it times out
$result = $connection->query($sql);
$rows=mysqli_num_rows($result);

//$dataArray=[];
if($rows>0)
			{
				while($singleRow =$result->fetch_assoc())
				{
                    $indexed= $client->index([
                        'index' => 'figures',
                        'type' => 'figure',
                        'body' => [
                            'id' =>   $singleRow['id'],
                            'patentID' => $singleRow['patentID'],
                            'patentdate' => $singleRow['patentdate'],
                            'figid' =>  $singleRow['figid'],
                            'caption' => $singleRow['caption'],
                            'object' => $singleRow['object'],
                            'aspect' => $singleRow['aspect'],
                            'figure_file' => $singleRow['figure_file'],
                            'object_title' =>  $singleRow['object_title'],
                            'groupID' => $singleRow['groupID'],
                        ]
                        ]);
				}
			}
                         
			  // close the connection
			  $connection->close();

            header('Location: indexLastQuarterFigures.php');
    


?>