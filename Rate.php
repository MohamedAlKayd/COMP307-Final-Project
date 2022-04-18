<?php
/*Variables used to rate a TA*/
$userid = $_GET["Userid"];
$studentid = getStudentid($userid);
$usertype = getUserType($userid);

echo "<html>";
echo "<head>";
echo "</head>";
echo "<body>";

displayActive("matter/header.txt","Rate",$usertype);
display("matter/RateReviewaTAbyCourseTOP.txt");

if (sizeof($_GET)==1 || $_GET["Page"] == "getCourse") {
	echo "<form method=\"post\" action=\"Rate.php?Page=getTA&Userid=".$userid."&Student=".$studentid."\">";

	echo "<h2>Course</h2>";
	echo "<text style=\"margin:15px; padding:8px\">For Which Course do you want to rate a TA?</text><br>";
	
	echo "<div class=\"search_categories\">";
	echo "<div class=\"select-menu\">";
	echo "<select name=\"course\">";
    	echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
    	
	$courseArray = getCoursesForStudent($studentid);
	foreach($courseArray as $row){
		echo "<option value=\"" . $row['courseid'] . "\" >".$row['term_year']."-".$row['course_num']."-".$row['course_name']."</option>";
	}

		echo "</select><br><br>";
		echo "</div>";
		echo "</div>";	

}
else if ($_GET["Page"]=="getTA") {
	$courseid = $_POST['course'];
	$course = getCourse($courseid);

	echo "<form method=\"post\" action=\"Rate.php?Page=rateTA&Userid=".$userid."&Student=".$studentid."&Course=".$courseid."\">";
	echo "<h2>TA</h2>";
	echo "<text> TA's for ".$course['term_year']."-".$course['course_num']."-".$course['course_name'].";</text><br>";
	echo "<text> Choose the TA you are rating? <text> <br>";

	echo "<div class=\"search_categories\">";
	echo "<div class=\"select-menu\">";	
	echo "<select name=\"TA\">";
	echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
	
	$TAarray =  getTAforCourse($courseid);
	foreach($TAarray as $row){
		echo "<option value=\"" . $row['taid'] . "\" >"."TA-".$row['taid']." ".$row['firstname']." ".$row[2]."</option>";
	}

	
	echo "</select><br><br>";
	echo "</div>";
	echo "</div>";

}

else if ($_GET["Page"]=="rateTA") {
	$taid = $_POST['TA'];
	$ta = getTA($taid);
	echo "<text> Rate and Review "."TA-".$ta['taid']." ".$ta['firstname']." ".$ta[2].":";
	echo "</text>";
	echo "<form method=\"post\" action=\"Rate.php?Page=reviewSubmit&Userid=".$userid."&Student=".$studentid."&Course=".$_GET["Course"]."&TA=".$taid."\">";

	display("matter/RateReviewaTA.txt");
}
else if ($_GET["Page"]=="reviewSubmit") {
	$courseid = $_GET['Course'];
	$TAid = $_GET['TA'];
	$rating = $_POST['radio'];
	$review =$_POST['comment']; 
	
	if(addTAreview($courseid,$TAid,$rating,$review)){
		echo "<text> SUBMITTED </text>";
	}
	else{
		echo "<text>FAILLED </text>";
	}
}

if($_GET["Page"]!="reviewSubmit"){
	display("matter/RateReviewaTAbyCoursebottom.txt");
}
display("matter/footer.txt");
echo "<body>";
echo "</html>";

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
	$pdo = new PDO("sqlite:" . "DB/Main.db");
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

	$pdo = new PDO("sqlite:" . "DB/Main.db");

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
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT ta.taid, ta.firstname, ta.lastname 
		FROM TA ta, AssistingCourse ac
		WHERE ta.taid == ac.taid and ac.courseid == ?");
	$query->execute(array($courseid));

	return $query->fetchAll();
}

function getCourse($courseid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT c.term_year, c.course_num, c.course_name 
		FROM Course c 
		WHERE c.courseid == ?;");
	
	$query->execute(array($courseid));
	
	return $query->fetch();
}

function getTA($taid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT ta.taid, ta.firstname, ta.lastname 
		FROM TA ta
		WHERE ta.taid == ?");
	
	$query->execute(array($taid));
	
	return $query->fetch();
}

function getStudentid($userid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT s.studentid FROM Student s WHERE s.userid == ?");
	
	$query->execute(array($userid));
	
	return $query->fetch()[0];
}

function getUserType($userid){
  $pdo = new PDO("sqlite:" . "DB/Main.db");

  $query = $pdo->prepare("SELECT usertype FROM UserInfo WHERE userid == ?");

  $query->execute(array($userid));

	$pdo = null;
  return $query->fetch()[0];
}

function displayActive($path,$target,$USERTYPE) {
  $admin = "Admin";
  $ta = "TA";
  $student = "Student";
  $prof = "Prof";
  $sysop = "Sysop";

  $file = fopen($path,"r");
  if (sizeof($target)==0) {
    $target="Page=DashBoard";
  }
  else $target="Page=".$target;

  if($USERTYPE == $admin){
		$hideList = array(
			0 => "SysopTasks"
		);
	}
	else if($USERTYPE == $prof || $USERTYPE == $ta){
		$hideList = array(
			0 => "SysopTasks",
			1 => "TAAdministration"
		);
	}
	else if($USERTYPE == $student){
		$hideList = array(
			0 => "SysopTasks",
			1 => "TAAdministration",
			2 => "TAManagement"
		);
	}
	else $hideList = array();

  while(!feof($file)) {
		$line = fgets($file);
		if (strstr($line,$target)){
			$line=str_replace("\">","\"><b>",$line);
			$line=str_replace("</","</b></",$line);
		}


		foreach ($hideList as $key => $value) {
			if (strstr($line,$value)){
	     	$line="";
	    }
		}

  	if($line != ""){
			echo $line;
		}
  }
  fclose($file);
}


?>