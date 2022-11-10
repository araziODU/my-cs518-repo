<?php
require 'elastic-php/vendor/autoload.php';
use Elastic\Elasticsearch\ClientBuilder;
$client = ClientBuilder::create()->build();
// check which search button was pressed
if(isset($_GET['query'])){

    $crit=$_GET['query'];
    
    if($_GET['action'] == 'Search' )
    {

        $query= $client -> search([
            'from' => 0,
            'size' => 5,
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
  
    }
    if($_GET['action'] == 'Search Annotation Tasks')
    {

    }
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
		  <div id="header">User Page</div>
		
		<?php include 'userNavbar.php'; ?>
	
		  <div id="centre">
			<h1>Welcome to my website.</h1>
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
                            <?php echo $r['_source']['object_title']; ?>
                        </a></th>
                        <th><img src="figures/<?php echo $r['_source']['figure_file']; ?>" width="200" height ="200" ></th>
                        </tr>
                    </div>

                 <?php
                }
            }
            ?>
            </table>
            
           
		</div>
</html>