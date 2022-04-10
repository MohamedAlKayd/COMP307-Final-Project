<?php

echo "<h1>Main.db Tables: </h1>";

echo "User <br>";
echo "userid, username, password, ticket, ticketexpires <br>";
displayTable("User");

echo "<br>";

echo "Student <br>";
echo "studentid, userid, firstname, lastName, email <br>";
displayTable("Student");

echo "<br>";

echo "TA <br>";
echo "taid, userid, firstname, lastName, email <br>";
displayTable("TA");

echo "<br>";

echo "Prof <br>";
echo "proffesorid, userid, firstname, lastName, email <br>";
displayTable("Prof");

echo "<br>";

echo "Admin <br>";
echo "adminid, userid, firstname, lastName, email <br>";
displayTable("Admin");

echo "<br>";

echo "Sysop <br>";
echo "sysopid, userid, firstname, lastName, email <br>";
displayTable("Sysop");

echo "<br>";

echo "TAreview <br>";
echo "reviewid, taid, studentid, rating, review <br>";
displayTable("TAreview");





function displayTable($table){
	$pdo = new PDO("sqlite:" . "Main.db");
	$result = $pdo->query("SELECT * from ".$table);
	$res = $result->fetchAll(PDO::FETCH_NUM);
	foreach($res as $row){
		echo implode(", ", $row);
		echo "<br>";
	}
}

?>