<?php
$type = $_GET['type'];
	if($type=='sql'){
		importBookSql("1.txt");
		importBookSql("2.txt");
		importBookSql("3.txt");
		importBookSql("4.txt");
		importBookSql("5.txt");
		echo "Done loading the books inside the SQL db";
		echo "</br> <a href='index.php'>Go back</a>";
	}else if(type=='neo4j'){
		
	}else{
		echo "There was an error";
		echo "</br> <a href='index.php'>Go back</a>";
	}
	

function importBookSql($bookName){
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
		$myfile = fopen("res/books/".$bookName, "r") or die("Unable to open file!");
		$title = fgets($myfile);
		$author = fgets($myfile);
		
		$stmt = $conn->prepare("INSERT INTO author(author_name)
				VALUES (?)");
		$stmt->bind_param('s', $author);
		$stmt->execute();
		$authorId =  mysqli_insert_id($conn);
		
		$stmt = $conn->prepare("INSERT INTO book(author_id, book_name)
				VALUES (?,?)");
		$stmt->bind_param('is', $authorId,$title);
		$stmt->execute();
		$bookId =  mysqli_insert_id($conn);
		
		$sql = "SELECT city_id,city_name, city_alternate_name FROM city";
		$result = $conn->query($sql);

		
 		   // output data of each row
		   $stmt = $conn->prepare("INSERT INTO appearance(book_id,city_id)
				VALUES (?,?)");
  			while($row = $result->fetch_assoc()) {
				$cId =  $row["city_id"];
				$cName = $row["city_name"];
				$cNameAlt = $row["city_alternate_name"];
				if( strpos(file_get_contents("res/books/".$bookName),$cName) !== false) {
     					$stmt->bind_param('ii', $bookId, $cId );
						$stmt->execute();
	 			continue;
   				}
				if( strpos(file_get_contents("res/books/".$bookName),$cNameAlt) !== false) {
     					$stmt->bind_param('ii', $bookId, $cId );
						$stmt->execute();
   				}
   			    
    		}
		
		$conn->close();
	 	
}

?>