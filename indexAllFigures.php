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
where rowNum =1 and id >1000";
$result = $connection->query($sql);
$rows=mysqli_num_rows($result);

//$dataArray=[];
if($rows>0)
			{
				while($singleRow =$result->fetch_assoc())
				{
                    /*
                    $indexed= [
                        $singleRow['id'],
                        $singleRow['patentID'],
                        $singleRow['patentdate'],
                        $singleRow['figid'],
                        $singleRow['caption'],
                        $singleRow['object'],
                        $singleRow['aspect'],
                        $singleRow['figure_file'],
                        $singleRow['subfigure_file'],
                        $singleRow['object_title'],
                        $singleRow['groupID']
                    ];
                    // store it in an array
                    array_push($dataArray, $indexed);
                    //index in bulk
                    */
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

/*
//index in bulk
$indexed= $client->index([
    'index' => 'figures',
    'type' => 'figure',
    'body' => [
        'id' => $id,
        'patentID' => $patentID,
        'patentdate' => $patentdate,
        'figid' => $figid,
        'caption' => $caption,
        'object' => $object,
        'aspect' => $aspect,
        'figure_file' => $figure_file,
        'subfigure_file' => $subfigure_file,
        'object_title' => $object_title,
        'groupID' => $groupID,
    ]
    ]);
*/
?>