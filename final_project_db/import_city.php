<?php
	
	$type = $_GET['type'];
	if($type=='sql'){
		importCitiesSql();
	}else{
		echo "There was an error";
		echo "</br> <a href='index.php'>Go back</a>";
	}
	
	
	
	function importCitiesSql(){
		$servername = "localhost";
		$username = "ovi282";
		$password = "ovi282";
		$dbname = "final_db";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
  		  die("Connection failed: " . $conn->connect_error);
		} 
		$myfile = fopen("res/cities.txt", "r") or die("Unable to open file!");
		$stmt = $conn->prepare("INSERT INTO city(city_id, city_name, city_alternate_name, city_lat, city_lng)
				VALUES (?,?,?,?,?)");
				
	 	while (($line = fgets($myfile)) !== false) {
       		$comp = preg_split("/[\t]/",$line);
			$stmt->bind_param('issdd', $comp[0],$comp[2], $comp[2],$comp[4],$comp[5]);
			$stmt->execute();
    	}
		
		echo "Done loading the cities inside the SQL db";
		echo "</br> <a href='index.php'>Go back</a>";
		
	}



?>