<?php

if($_POST['query']=="q1"){
	getBooksFromCity($_POST['name']);
}else if($_POST['query']=="q2"){
	getCitiesFromBookTitle($_POST['title']);
}else if($_POST['query']=="q3"){
	getCitiesFromAuthorName($_POST['author']);
}else if($_POST['query']=="q4"){
	 getGeolocation($_POST['lat'],$_POST['lng']);
}else{
	echo 'There was a problem';	
}


function getGeolocation($lat, $lng){
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
	
	$servername = "localhost";
		$username = "ovi282";
		$password = "ovi282";
		$dbname = "final_db";
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
  		  die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "SELECT * FROM city  where city_lat > $latInt AND city_lat < $latIntSuperior AND city_lng > $lngInt AND city_lng < $lngIntSuperior";
		
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				
				while($row = $result->fetch_assoc()) {
					$cityName = $row['city_name'];
					$cityId = $row['city_id'];
						$sql2 = "SELECT * FROM appearance  where city_id = $cityId";
						$result2 = $conn->query($sql2);
						if ($result2->num_rows > 0) {
							
							while($row2 = $result2->fetch_assoc()) {
								$bookId = $row2['book_id'];
								$sql3 = "SELECT * FROM book  where book_id = $bookId";
								$result3 = $conn->query($sql3);
								if ($result3->num_rows > 0) {
									
									while($row3 = $result3->fetch_assoc()) {
										$bookName = $row3['book_name'];
										echo 'City: '.$cityName." , Book: ".$bookName." </br>";
									}
								}
							}
						}
					
				}
			}
	
}

function getCitiesFromAuthorName($authorId){
	$servername = "localhost";
		$username = "ovi282";
		$password = "ovi282";
		$dbname = "final_db";
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
  		  die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "SELECT * FROM book  where author_id = $authorId";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$bookName = $row['book_name'];
					$bookId = $row['book_id'];
				echo '<b>BOOK: '.$bookName.'</b> </br>';
				
				$id =  $row['book_id'];
				$sql2 = "SELECT city_id FROM appearance  where book_id = $bookId";
				$result2 = $conn->query($sql2);
				if ($result2->num_rows > 0) {
				while($row2 = $result2->fetch_assoc()) {
					$id2 = $row2['city_id'];
					$sql3 = "SELECT * FROM city  where city_id = $id2";
					$result3 = $conn->query($sql3);
					if ($result3->num_rows > 0) {
					while($row3 = $result3->fetch_assoc()) {
						$cityName = $row3['city_name'];
						$cityLat = $row3['city_lat'];
						$cityLng = $row3['city_lng'];
						echo "City: ".$cityName."  Lat: ".$cityLat." Long: ".$cityLng;
						echo '</br>';
						
					}
					}
				}
				}
				}}
			
}

function getCitiesFromBookTitle($titleId){
	$servername = "localhost";
		$username = "ovi282";
		$password = "ovi282";
		$dbname = "final_db";
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
  		  die("Connection failed: " . $conn->connect_error);
		} 
		
				
				$id =  $row['book_id'];
				$sql2 = "SELECT city_id FROM appearance  where book_id = $titleId";
				$result2 = $conn->query($sql2);
				if ($result2->num_rows > 0) {
				while($row2 = $result2->fetch_assoc()) {
					$id2 = $row2['city_id'];
					$sql3 = "SELECT * FROM city  where city_id = $id2";
					$result3 = $conn->query($sql3);
					if ($result3->num_rows > 0) {
					while($row3 = $result3->fetch_assoc()) {
						$cityName = $row3['city_name'];
						$cityLat = $row3['city_lat'];
						$cityLng = $row3['city_lng'];
						echo "City: ".$cityName."  Lat: ".$cityLat." Long: ".$cityLng;
						echo '</br>';
						
					}
					}
				}
				}
			
}

function getBooksFromCity($city){
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
		
		$cityEsc = mysql_escape_string($city);
		$sql = "SELECT city_id FROM city where city_name = '$cityEsc' OR city_alternate_name = '$cityEsc'";
		
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$id =  $row['city_id'];
			$sql2 = "SELECT book_id FROM appearance  where city_id = $id";
			$result2 = $conn->query($sql2);
			if ($result2->num_rows > 0) {
			while($row2 = $result2->fetch_assoc()) {
				$id2 = $row2['book_id'];
					$sql3 = "SELECT book_name,author_id FROM book  where book_id = $id2";
					$result3 = $conn->query($sql3);
					if ($result3->num_rows > 0) {
					while($row3 = $result3->fetch_assoc()) {
						$id3 = $row3['author_id'];
						$sql4 = "SELECT author_name FROM author where author_id = $id3";
						$result4 = $conn->query($sql4);
						if ($result4->num_rows > 0) {
						while($row4 = $result4->fetch_assoc()) {
								echo '<p>'.$row3['book_name']."  by ".$row4['author_name']."</p> </br>";
						}
						}
					}
					}
			}
			}
			break;
    	}
		}
		
		$conn->close();
	
}

?>