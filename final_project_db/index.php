<a href="import_city.php?type=sql">Import the cities inside the SQL db</a> </br>
<a href="import_book.php?type=sql">Import the books insde the SQL db</a> </br>
<a href="query_neo4j.php?q=1">Import everything into neo4j</a> </br>
</br>
<b>SQL:</b> </br>
<form action="query_sql.php" method="post">
City Name : <input type="text" name="name"><br>
<input type="hidden" value="q1" name="query">
<input type="submit">
</form>
<form action="query_sql.php" method="post">
Book Name: <select id="title" name="title">  
<?php
		$servername = "localhost";
		$username = "ovi282";
		$password = "ovi282";
		$dbname = "final_db";
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
  		  die("Connection failed: " . $conn->connect_error);
		} 
$sql = "SELECT * FROM book";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
$id = $row['book_id'];
$name = $row['book_name'];
echo '<option value="'.$id.'">'.$name.'</option>';
}

?>

</select>
<br>
<input type="hidden" value="q2" name="query">
<input type="submit">
</form>

<form action="query_sql.php" method="post">
Author Name: <select id="author" name="author">  
<?php
		$servername = "localhost";
		$username = "ovi282";
		$password = "ovi282";
		$dbname = "final_db";
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
  		  die("Connection failed: " . $conn->connect_error);
		} 
$sql = "SELECT * FROM author";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
$id = $row['author_id'];
$name = $row['author_name'];
echo '<option value="'.$id.'">'.$name.'</option>';
}

?>

</select>
<br>
<input type="hidden" value="q3" name="query">
<input type="submit">
</form>

<form action="query_sql.php" method="post">
Lat : <input type="text" name="lat"><br>
Long : <input type="text" name="lng"><br>
<input type="hidden" value="q4" name="query">
<input type="submit">
</form>



<b>NEO4J:</b> </br>
<form action="query_neo4j.php" method="post">
City Name : <input type="text" name="name"><br>
<input type="hidden" value="q2" name="query">
<input type="submit">
</form>

<form action="query_neo4j.php" method="post">
Book Name : <input type="text" name="title"><br>
<input type="hidden" value="q3" name="query">
<input type="submit">
</form>

<form action="query_neo4j.php" method="post">
Author Name : <input type="text" name="title"><br>
<input type="hidden" value="q4" name="query">
<input type="submit">
</form>

<form action="query_neo4j.php" method="post">
Lat : <input type="text" name="lat"><br>
Long : <input type="text" name="lng"><br>
<input type="hidden" value="q5" name="query">
<input type="submit">
</form>