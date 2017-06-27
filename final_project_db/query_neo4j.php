<?php
require("vendor/autoload.php");

if($_GET['q']=="1"){
	importCityToNeo4j();
	importBookNeo4j("1.txt");
	importBookNeo4j("2.txt");
	importBookNeo4j("3.txt");
	importBookNeo4j("4.txt");
	importBookNeo4j("5.txt");
	echo "Done loading the data inside the NEO4J db";
	echo "</br> <a href='index.php'>Go back</a>";
}else if($_POST['query']=="q2"){
	getBooksFromCity($_POST['name']);
}else if($_POST['query']=="q3"){
	getCitiesFromBookTitle($_POST['title']);
}else if($_POST['query']=="q4"){
	getCitiesFromAuthorName($_POST['author']);
}else if($_POST['query']=="q5"){
	 getGeolocation($_POST['lat'],$_POST['lng']);
}else{
	echo 'There was a problem';	
}

function getGeoLocation($lat,$lng){
	$latInt = floor($lat);
	$latIntSuperior = $latInt + 1;
	if($lng<0){
		$lng = $lng * (-1);
		$lngInt = floor($lng);
		$lngIntSuperior = $lngInt + 1;
		$lngInt = $lngInt * (-1);
		$lngIntSuperior = $lngIntSuperior * (-1);
	}else{
		$lngInt = floor($lng);
		$lngIntSuperior = $lngInt + 1;
	}
	$client = new Everyman\Neo4j\Client(
            (new Everyman\Neo4j\Transport\Curl('127.0.0.1',7474))
                ->setAuth('neo4j','admin')
   );
   		

   $queryString = '
		MATCH (c:City)-[:APPEARS_IN]->(b:Book)
		WHERE c.cityLat > '.$latInt.' AND c.cityLat < '.$latIntSuperior.' AND c.cityLng > '.$lngInt.' AND c.cityLng < '.$lngIntSuperior.'
		RETURN c, b';
		$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
		$result = $query->getResultSet();
		foreach ($result as $row) {
			$city = $row['c']->getProperty('cityName');
			$book = $row['b']->getProperty('bookName');
			echo "City: ".$city." Book: ".$book;
		}
		
}

function getCitiesFromAuthorName($author){
	$client = new Everyman\Neo4j\Client(
            (new Everyman\Neo4j\Transport\Curl('127.0.0.1',7474))
                ->setAuth('neo4j','admin')
   );
	 $queryString = '
		MATCH (a:Author)-[:WROTE]->(b:Book)
		WHERE a.authorName = "'.$author.'" 
		RETURN b';
		$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
		$result = $query->getResultSet();
		foreach ($result as $row) {
			$book = $row['b']->getProperty('bookName');
			getCitiesFromBookTitle($book);
			echo "Book: ".$book;
		}
}

function getCitiesFromBookTitle($name){
	$client = new Everyman\Neo4j\Client(
            (new Everyman\Neo4j\Transport\Curl('127.0.0.1',7474))
                ->setAuth('neo4j','admin')
   );
   $queryString = '
		MATCH (c:City)-[:APPEARS_IN]->(b:Book)
		WHERE b.bookName = "'.$name.'" 
		RETURN c';
		$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
		$result = $query->getResultSet();
		foreach ($result as $row) {
			$city = $row['c']->getProperty('cityName');
			$lat = $row['c']->getProperty('cityLat');
			$lng = $row['c']->getProperty('cityLng');
			
			echo "City: ".$city." Lat: ".$lat." Long: ".$lng;
		}
   
   
}

function getBooksFromCity($name){
	$client = new Everyman\Neo4j\Client(
            (new Everyman\Neo4j\Transport\Curl('127.0.0.1',7474))
                ->setAuth('neo4j','admin')
   );
   
  		$queryString = '
		MATCH (c:City)-[:APPEARS_IN]->(b:Book)
		WHERE c.cityName = "'.$name.'" 
		RETURN b';
		$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
		$result = $query->getResultSet();
		foreach ($result as $row) {
			$book = $row['b']->getProperty('bookName');
			
			$queryString = '
			MATCH (a:Author)-[:WROTE]->(b:Book)
			WHERE b.bookName = "'.$book.'" 
			RETURN a';
			$query2 = new Everyman\Neo4j\Cypher\Query($client, $queryString);
			$result2 = $query2->getResultSet();
			foreach ($result2 as $row2) {
				$author = $row2['a']->getProperty('authorName');
				echo $book." by ".$author."</br>";
			}
		}
}


function importCityToNeo4j(){
	$client = new Everyman\Neo4j\Client(
            (new Everyman\Neo4j\Transport\Curl('127.0.0.1',7474))
                ->setAuth('neo4j','admin')
   );
 	
	 $myfile = fopen("res/cities.txt", "r") or die("Unable to open file!");
	 while (($line = fgets($myfile)) !== false) {
		 $transaction = $client->beginTransaction();
       		$comp = preg_split("/[\t]/",$line);
			$queryString = '
		CREATE (:City {cityId: "'.$comp[0].'", cityName: "'.$comp[2].'", cityAlternateName: "'.$comp[2].'", cityLat: "'.$comp[4].'", cityLng:  "'.$comp[5].'"});';
		$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
		$result = $transaction->addStatements($query,true);
    	}
}

function importBookNeo4j($bookName){
		$client = new Everyman\Neo4j\Client(
            (new Everyman\Neo4j\Transport\Curl('127.0.0.1',7474))
                ->setAuth('neo4j','admin')
  		);
		$myfile = fopen("res/books/".$bookName, "r") or die("Unable to open file!");
		$title = fgets($myfile);
		$author = fgets($myfile);
		
		$transaction = $client->beginTransaction();
		$queryString = '
		CREATE (:Author {authorName: "'.$author.'"});';
		$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
		$result = $transaction->addStatements($query,true);
		
		
		$transaction = $client->beginTransaction();
		$queryString = '
		CREATE (:Book {bookName: "'.$title.'"});';
		$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
		$result = $transaction->addStatements($query,true);
		
			$transaction = $client->beginTransaction();
		$queryString = 'MATCH (a:Author),(b:Book)
		WHERE a.authorName = "'.$author.'" AND b.bookName = "'.$title.'"
		CREATE (a)-[r:WROTE]->(b)';
		$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
		$result = $transaction->addStatements($query,true);
		
		
		
		$queryString = '
		MATCH (n:City) RETURN n';
		$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
		$result = $query->getResultSet();
		foreach ($result as $row) {
			$cId = $row['n']->getProperty('cityId');
   			$cName = $row['n']->getProperty('cityName');
			$cNameAlt = $row['n']->getProperty('cityAlternateName');
			if( strpos(file_get_contents("res/books/".$bookName),$cName) !== false) {
     					$transaction = $client->beginTransaction();
						$queryString = 'MATCH (c:City),(b:Book)
						WHERE c.cityName = "'.$cName.'" AND b.bookName = "'.$title.'"
						CREATE (c)-[r:APPEARS_IN]->(b)';
						$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
						$result = $transaction->addStatements($query,true);
	 			continue;
   				}
				if( strpos(file_get_contents("res/books/".$bookName),$cNameAlt) !== false) {
     					$transaction = $client->beginTransaction();
						$queryString = 'MATCH (c:City),(b:Book)
						WHERE c.cityAlternateName = "'.$cNameAlt.'" AND b.bookName = "'.$title.'"
						CREATE (c)-[r:APPEARS_IN]->(b)';
						$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
						$result = $transaction->addStatements($query,true);
   				}
			}
		
	 	
}



?>