<?php
	session_start();
    require  'authentication.php';
	require 'elastic-php/vendor/autoload.php';
    use Elastic\Elasticsearch\ClientBuilder;
	if(!isset($_GET['group'])){
		die('Group not Provided');
	}
    $client = ClientBuilder::create()->build();
	$connection = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
	$group = $_GET['group'];
    $date = date('Y-m-d H:i:s');

    $connection3=new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
    $sql3="select * from users where userID=".$_SESSION['userIdForGroup'];
    $result3= $connection3->query($sql3);
    $rows3=mysqli_num_rows($result3);
    if($rows3>0)
			{
				while($singleRow3 =$result3->fetch_assoc())
				{
                    $email=$singleRow3['email'];
                }
            }

    $totalCount= $client->count([
        'index' => 'figures',
        'type' => 'figure',
        'body' => [
            'query' => [
                'bool' => [
                    'should' => [
                        'match_phrase' => ['groupID' =>  $group]
                    ]
                ]
            ]
        ]
                    ]);

    $sql = "INSERT  INTO annotations 
                        (`user_id`,
                        `group_id`,
                        `assign_datetime`,
                        `numb_compound_fig`,
                        `finished`)

                        VALUES
                        ( ".$_SESSION['userIdForGroup'].",
                        $group,
                        CURRENT_TIMESTAMP,
                        ".$totalCount['count'].",
                        0
                        )
                        ";
	$result = $connection->query($sql);

    
    $date = date('Y-m-d H:i:s');
   

    $query= $client -> search([
        'index' => 'figures',
        'type' => 'figure',
        'size' => 10000,
        'body' => [
            'query' => [
                'bool' => [
                    'should' => [
                        'match_phrase' => ['groupID' => $group]
                    ]
                ]
            ]
        ]
    ]);
     //echo $query->getBody();
     if ( $query['hits']['total'] >= 1)
     {
         $results = $query['hits']['hits'];
     }
     $indexedNumb=0;
     if(isset($results)) {
        foreach($results as $r)
        {
            //get number of sub figures
            $connection2 = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
            $filename=$r['_source']['figure_file'];
            $query2="SELECT * from figure_segmented_nipseval_test2007 where figure_file = '$filename'";

            $results2 =$connection2->query($query2);
            $rows2=mysqli_num_rows($results2);
            $subfigures=array();
            if($rows2>0)
			{
				while($singleRow2 =$results2->fetch_assoc())
				{
                    $bool=true;

                    $singleFigure=[
                        "subfigure_id" => $singleRow2['subfigure_file'],
                       
                        //storing empty string
                        "object_correct" => null,
                        "object" => null, //$singleRow2['object'],
                        //storing empty string
                        "aspect_correct" => null,
                        "aspect" => null, //$singleRow2['aspect']
                        

                    ];
                        array_push($subfigures, $singleFigure);

                }
            }

            $obj =json_encode($subfigures);
            $indexed= $client->index([
                'index' => 'annotations',
                'type' => 'annotation',
                'body' => [
                    'compoundfigure_file' =>   $r['_source']['figure_file'],
                    'assignments'=> [
                        "assign_id" => $r['_source']['id'],
                        "user" =>  $email,
                        "datetime" =>  $date,
                        "annotations" => [
                            "seg_correct" => null, //"no",
                            "n_subfigure" =>null, // $rows2,
                            
                            "subfigures"=>
                               $subfigures

                                          
                    ]
                ]
                ]
            ]);
            $indexedNumb++;
        }
    }
     

	 // close the connection
	 $connection->close();
     header('Location: assignTask.php');
	
?>