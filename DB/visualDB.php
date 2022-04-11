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
echo "reviewid, taid, courseid, rating, review <br>";
displayTable("TAreview");

echo "<br>";

echo "Course <br>";
echo "courseid, term_month_year, course_num, course_name, instructor_assigned_name <br>";
displayTable("Course");

echo "<br>";

echo "TakingCourse <br>";
echo "studentid,courseid <br>";
displayTable("TakingCourse");

echo "<br>";

echo "AssistingCourse <br>";
echo "taid,courseid <br>";
displayTable("AssistingCourse");

echo "<br>";

echo "TeachingCourse <br>";
echo "proffesorid,courseid <br>";
displayTable("TeachingCourse");


echo "<h2>Main.db Views: </h2>";

echo "UserInfo <br>";
echo "userid,firstname,lastname,username,password,usertype <br>";
displayTable("UserInfo");







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