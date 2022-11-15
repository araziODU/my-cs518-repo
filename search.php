<?php
session_start();
require 'elastic-php/vendor/autoload.php';
require  'authentication.php';
use Elastic\Elasticsearch\ClientBuilder;
$client = ClientBuilder::create()->build();
if(isset($_GET['page'])){
    $currentPage=$_GET['page'];
}
else
{
    $currentPage=1;
}

$pageSize=10;
$fromIndex=($currentPage-1)*$pageSize;
// check which search button was pressed


    $crit=$_GET['query'];
   
   
    if($_GET['action'] == 'Search' )
    {

        $query= $client -> search([
            'index' => 'figures',
            'from' => $fromIndex,
            'size' =>  $pageSize,
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            'match' => ['object_title' => $crit]
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
        
        $totalCount= $client->count([
            'index' => 'figures',
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            'match' => ['object_title' => $crit]
                        ]
                    ]
                ]
            ]
                        ]);

            $totalPages= (int) ($totalCount['count']/ $pageSize);
                       
    }
    if($_GET['action'] == 'SearchAnnotationTasks')
    {
        $userIDs=$_SESSION['userID'];
        $connection3=new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
        $sql3="SELECT * FROM users WHERE email='$userIDs'";

        $result3= $connection3->query($sql3);
        $rows3=mysqli_num_rows($result3);
        if($rows3>0)
                {
                    while($singleRow3 =$result3->fetch_assoc())
                    {
                        $email=$singleRow3['email'];
                    }
                }
    
                $query= $client -> search([
                    'index' => 'annotations',
                    'from' => $fromIndex,
                    'size' =>  $pageSize,
                    'body' => [
                        'query' => [
                            'bool' => [
                                'should' => [
                                    'match' => ['assignments.user' => $email]
                                ]
                            ]
                        ]
                    ]
                ]);
        if ( $query['hits']['total'] >= 1)
        {
            $results = $query['hits']['hits'];
        }
        
        $totalCount= $client->count([
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            'match' => ['assignments.user' => $email]
                        ]
                    ]
                ]
            ]
                        ]);

            $totalPages= (int) ($totalCount['count']/ $pageSize);

    }

?>


<html>
	<head>
		<title>Search Results</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
		<link rel="stylesheet" href="styles.css" />
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

</style>
	</head>
		<div id="conteneur">
            
		 
		
		<?php if ($_SESSION['userType']=='Admin')
        {
            echo " <div id=header>Admin Page</div>";
            include 'adminNavBar.php';
        }
        else
        {
            echo " <div id=header>User Page</div>";
            include 'userNavBar.php';
        }
            ?>
	
		  <div id="centre">
			<h1>My assigned annotation tasks</h1>
            <h2>Total Results for your search:<?php  echo $totalCount['count'];  ?> </h2>
			</div>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Thumbnail</th>
                </tr>

            <?php
            if(isset($results)) {
                foreach($results as $r)
                {
                ?>
                    <div class="result">
                        
                        <tr>
                        
                        <th><a href="#<?php echo $r['_id']; ?>" >
                            <?php 
                            if($_GET['action'] == 'SearchAnnotationTasks')
                            {
                                //echo $r['_source']['assignments']['annotations']['subfigures'][0]['object'];
                                {
                                    $connection4 = new mysqli($server, $sqlUsername, $sqlPassword, $databaseName);
                                    $objName= $r['_source']['compoundfigure_file'];
                                
                                    echo '<br>';
                                    
            
                                    $sql4 = "select * from
                                    (
                                        select * 
                                        , row_number() over( partition by figure_file order by figure_file) as rowNum
                                        
                                    from figure_segmented_nipseval_test2007
                                    ) as a
                                    where rowNum =1 and figure_file= '$objName'"; // run this twice starting from last index ran to increment this as it times out
                                    
                                    $result4 = $connection4->query($sql4);
                                    $rows4=mysqli_num_rows($result4);
            
                                    //$dataArray=[];
                                    if($rows4>0)
                                                {
                                                    $singleRow4 =$result4->fetch_assoc();
                                                    
                                                        echo  $singleRow4['object'];
                                                    
                                        
                                                }
                                            }
            
                                    
                            }
                            else
                            
                            echo $r['_source']['object_title']; ?>
                        </a></th>
                        <th><img src="figures/<?php 
                        if($_GET['action'] == 'SearchAnnotationTasks')
                        {
                            echo $r['_source']['compoundfigure_file'];
                        }
                        else
                        echo $r['_source']['figure_file']; 
                        
                        ?>" width="200" height ="200" ></th>
                        </tr>
                    </div>

                 <?php
                }
            }
            ?>
            </table>
            <br>
            <table>
            <tr>
            <th><a href="search.php?query=<?php echo $crit."&action=".$_GET['action']."&page=1"; ?>"> <<  </a> </th>
            <th style="text-align:center">
            
            <?php
            $pagesToDisplay=4;

            $after=$totalPages-$currentPage;
            if($after>=2){
                if( $currentPage-2> 0)
            {
                $pagesBefore=$currentPage-2;
                echo "<a href=search.php?query=".$crit."&action=".$_GET['action']."&page=".$pagesBefore."> ".$pagesBefore." </a>";
                $pagesToDisplay--;
            }
            if( $currentPage-1> 0)
            {
                $pagesBefore=$currentPage-1;
                echo "<a href=search.php?query=".$crit."&action=".$_GET['action']."&page=".$pagesBefore."> ".$pagesBefore." </a>";
                $pagesToDisplay--;
            }

            }
            if($after==1){
                if( $currentPage-3> 0)
            {
                $pagesBefore=$currentPage-3;
                echo "<a href=search.php?query=".$crit."&action=".$_GET['action']."&page=".$pagesBefore."> ".$pagesBefore." </a>";
                $pagesToDisplay--;
            }
            if( $currentPage-2> 0)
            {
                $pagesBefore=$currentPage-2;
                echo "<a href=search.php?query=".$crit."&action=".$_GET['action']."&page=".$pagesBefore."> ".$pagesBefore." </a>";
                $pagesToDisplay--;
            }
            if( $currentPage-1> 0)
            {
                $pagesBefore=$currentPage-1;
                echo "<a href=search.php?query=".$crit."&action=".$_GET['action']."&page=".$pagesBefore."> ".$pagesBefore." </a>";
                $pagesToDisplay--;
            }

            }

            if($after<=0){
                if( $currentPage-4> 0)
                {
                    $pagesBefore=$currentPage-4;
                    echo "<a href=search.php?query=".$crit."&action=".$_GET['action']."&page=".$pagesBefore."> ".$pagesBefore." </a>";
                    $pagesToDisplay--;
                }
                if( $currentPage-3> 0)
                {
                    $pagesBefore=$currentPage-3;
                    echo "<a href=search.php?query=".$crit."&action=".$_GET['action']."&page=".$pagesBefore."> ".$pagesBefore." </a>";
                    $pagesToDisplay--;
                }
                if( $currentPage-2> 0)
                {
                    $pagesBefore=$currentPage-2;
                    echo "<a href=search.php?query=".$crit."&action=".$_GET['action']."&page=".$pagesBefore."> ".$pagesBefore." </a>";
                    $pagesToDisplay--;
                }
                if( $currentPage-1> 0)
                {
                    $pagesBefore=$currentPage-1;
                    echo "<a href=search.php?query=".$crit."&action=".$_GET['action']."&page=".$pagesBefore."> ".$pagesBefore." </a>";
                    $pagesToDisplay--;
                }
            }
            

            

                ?>
            <a href="search.php?query=<?php echo $crit."&action=".$_GET['action']."&page=".$currentPage; ?>" style="color:#FF0000;"><?php echo $currentPage; ?></a> 
            <?php
            for($pagesBefore=$currentPage+1; $pagesBefore<=$totalPages && ($pagesBefore-$currentPage)<=$pagesToDisplay; $pagesBefore++) {
                echo "<a href=search.php?query=".$crit."&action=".$_GET['action']."&page=".$pagesBefore."> ".$pagesBefore." </a>";
            }
                ?>
        </th>
            <th><a href="search.php?query=<?php echo $crit."&action=".$_GET['action']."&page=".$totalPages; ?>"> >> </a> </th>
            </tr>
                </table>
		</div>
</html>