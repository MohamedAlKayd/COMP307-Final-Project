<?php
/*Variables used to rate a TA*/
$studentid = $_GET["Student"];
display("RateReviewaTAbyCourseTOP.txt");

if ($_GET["Page"] == "getCourse") {
	echo "<form method=\"post\" action=\"rateTA.php?Page=getTA&Student=".$studentid."\">";

	echo "<h2>Course</h2>";
	echo "For Which Course do you want to rate a TA?<br>";
	echo "<select name=\"course\">";
    	echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
    	
	$courseArray = getCoursesForStudent($studentid);
	foreach($courseArray as $row){
		echo "<option value=\"" . $row['courseid'] . "\" >".$row['term_year']."-".$row['course_num']."-".$row['course_name']."</option>";
	}

    	echo "</select><br><br>";

}
else if ($_GET["Page"]=="getTA") {
	$courseid = $_POST['course'];
	$course = getCourse($courseid);

	echo "<form method=\"post\" action=\"rateTA.php?Page=rateTA&Student=".$studentid."&Course=".$courseid."\">";
	echo"<h2>TA</h2>";
	echo "TA's for ".$course['term_year']."-".$course['course_num']."-".$course['course_name'].";<br>";
	echo "Choose the TA you are rating?<br>";
	echo "<select name=\"TA\">";
	echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
	
	$TAarray =  getTAforCourse($courseid);
	foreach($TAarray as $row){
		echo "<option value=\"" . $row['taid'] . "\" >"."TA-".$row['taid']." ".$row['firstname']." ".$row[2]."</option>";
	}

	
	echo "</select><br><br>";

}

else if ($_GET["Page"]=="rateTA") {
	$taid = $_POST['TA'];
	$ta = getTA($taid);
	echo "Rate and Review "."TA-".$ta['taid']." ".$ta['firstname']." ".$ta[2].":";
	echo "<form method=\"post\" action=\"rateTA.php?Page=reviewSubmit&Student=".$studentid."&Course=".$_GET["Course"]."&TA=".$taid."\">";

	display("RateReviewaTA.txt");
}
else if ($_GET["Page"]=="reviewSubmit") {
	$courseid = $_GET['Course'];
	$TAid = $_GET['TA'];
	$rating = $_POST['rating'];
	$review =$_POST['comment']; 
	if(addTAreview($courseid,$TAid,$rating,$review)){
		echo "SUBMITTED";
	}
	else{
		echo "FAILLED";
	}
}

if($_GET["Page"]!="reviewSubmit"){
	display("RateReviewaTAbyCoursebottom.txt");
}
display("footer.txt");

function display($path) {
  $file = fopen($path,"r");
  while(!feof($file)) {
    $line = fgets($file);
    echo $line;
  }
  fclose($file);
}

//Used to save a new TA review in the database
//returns true if the review was added
function addTAreview($courseid, $TAid, $rating, $review){
	$pdo = new PDO("sqlite:" . "../DB/Main.db");
	$maxreviewid = $pdo->query("SELECT MAX(reviewid) FROM TAreview");
	$newreviewid = $maxreviewid->fetchColumn() + 1;

	$query = $pdo->prepare("INSERT INTO TAreview (reviewid, taid, courseid, rating, review) VALUES (?,?,?,?,?)");
	$err1 = $query->execute(array($newreviewid, $TAid, $courseid, $rating, $review));
	
	$pdo = null;
	return ($err1 == 1);

}

//returns an array of rows (access each rows like this: foreach($arrray as $row){})
//each row is a course taken by that student
//each row is an array of this form $row = ['courseid', 'term_year', 'course_num', 'course_name']
function getCoursesForStudent($studentid){

	$pdo = new PDO("sqlite:" . "../DB/Main.db");

	$query = $pdo->prepare("SELECT c.courseid, c.term_year, c.course_num, c.course_name 
		FROM TakingCourse tc, Course c 
		WHERE tc.studentid == ? and tc.courseid == c.courseid;");
	
	$query->execute(array($studentid));
	
	return $query->fetchAll();
}


//returns an array of rows (access each rows like this: foreach($arrray as $row){})
//each row is a TA associated with that course
//each row is an array of this form $row = ['taid', 'firstname', 'lastname']
function getTAforCourse($courseid){
	$pdo = new PDO("sqlite:" . "../DB/Main.db");

	$query = $pdo->prepare("SELECT ta.taid, ta.firstname, ta.lastname 
		FROM TA ta, AssistingCourse ac
		WHERE ta.taid == ac.taid and ac.courseid == ?");
	$query->execute(array($courseid));

	return $query->fetchAll();
}

function getCourse($courseid){
	$pdo = new PDO("sqlite:" . "../DB/Main.db");

	$query = $pdo->prepare("SELECT c.term_year, c.course_num, c.course_name 
		FROM Course c 
		WHERE c.courseid == ?;");
	
	$query->execute(array($courseid));
	
	return $query->fetch();
}

function getTA($taid){
	$pdo = new PDO("sqlite:" . "../DB/Main.db");

	$query = $pdo->prepare("SELECT ta.taid, ta.firstname, ta.lastname 
		FROM TA ta
		WHERE ta.taid == ?");
	
	$query->execute(array($taid));
	
	return $query->fetch();
}


?>