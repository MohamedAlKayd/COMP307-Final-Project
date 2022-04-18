<?php

/*Variables used for Management*/
$userid = $_GET["Userid"];
$usertype = getUserType($userid);
$page = $_GET["Page"];

echo "<html>";
echo "<head>";
echo "</head>";
echo "<body>";

displayActive("matter/header.txt","Management",$usertype);



if(empty($page)){
	displaySub("matter/ta_management.txt", $userid);
}

else if($page == "SelectCourse"){
	$profid = getProfid($userid);
	echo "<form method=\"post\" action=\"Management.php?Page=CourseSelected&Userid=".$userid."\">";
	
	
	echo "<div class=\"search_categories\">";
	echo "<div class=\"select-menu\">";
	echo "<h2 style=\"margin:22px; padding:8px;\"> Course</h2>";
	echo "<text style=\"margin:22px; padding:8px;\"> Select A Course </text><br>";
	
	echo "<select name=\"course\">";
    	echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
    	
	$courseArray = getCourseProf($profid);
	foreach($courseArray as $row){
		echo "<option value=\"" . $row['courseid'] . "\" >".$row['term_year']."-".$row['course_num']."-".$row['course_name']."</option>";
	}

	echo "</select><br><br>";
	echo "</div>";
	echo "</div>";
	echo "<input id=\"info\" type=\"submit\" name=\"submit\" value=\"submit\"><br><br><br><br>";
	
	echo "</form>";
	
}
else if($page == "CourseSelected"){
	$courseid = $_POST['course'];
	displaySub2("matter/SelectCourse.txt", $userid,$courseid);
}
else if($page == "ProfsTAPerformanceLog"){
	$courseid = $_GET["Courseid"];
	echo "<form method=\"post\" action=\"Management.php?Page=AddProfsTAPerformanceLog&Userid=".$userid."&Courseid=".$courseid."\">";
	
	echo "<div class=\"search_categories\">";
	echo "<div class=\"select-menu\">";
	echo "<h2>TA Performance Log</h2>";
	echo "<text> Select A TA </text> <br>";
	echo "<select name=\"taid\">";
    	echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
    	
	$TAArray = getTAforCourse($courseid);
	foreach($TAArray as $row){
		echo "<option value=\"" . $row['taid'] . "\" >".$row['firstname']." ".$row[2]."</option>";
	}
    echo "</select><br><br>";

	echo "<text> Write a Note <text> <br>";
	echo "<textarea name=\"note\" rows=\"5\" cols=\"50\"></textarea><br><br>";
    echo "</div>";
	echo "</div>";
	echo "<input id=\"info\" type=\"submit\" name=\"submit\" value=\"submit\"><br><br><br><br>";
	echo "</form>";
}
else if($page == "AddProfsTAPerformanceLog"){
	$courseid = $_GET["Courseid"];
	$profid = getProfid($userid);
	$taid = $_POST['taid'];
	$note = $_POST['note'];
	
	addToTAPerformanceLog($taid,$courseid,$profid,$note);
	header("Location: main.php?Page=Management");
}
else if($page == "TAWishList"){
	$courseid = $_GET["Courseid"];
	echo "<form method=\"post\" action=\"Management.php?Page=AddTAWishList&Userid=".$userid."&Courseid=".$courseid."\">";
	
	echo "<div class=\"search_categories\">";
	echo "<div class=\"select-menu\">";
	echo "<h2>TA Wish List</h2>";
	echo "<text> Select TA to Add to Wish List <text> <br>";
	echo "<select name=\"taid\">";
    	echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
    	
	$TAArray = getTAs();
	foreach($TAArray as $row){
		echo "<option value=\"" . $row['taid'] . "\" >".$row['firstname']." ".$row[2]."</option>";
	}
    echo "</select><br><br>";
	echo "</div>";
	echo "</div>";
	echo "<input id=\"info\" type=\"submit\" name=\"submit\" value=\"submit\"><br><br><br><br>";
	echo "</form>";
}
else if($page == "AddTAWishList"){
	$courseid = $_GET["Courseid"];
	$profid = getProfid($userid);
	$taid = $_POST['taid'];

	addToTAWishlist($taid,$courseid,$profid);
	header("Location: main.php?Page=Management");
}
else{
	displaySub("matter/ta_management.txt", $userid);
}



display("matter/footer.txt");

echo "<body>";
echo "</html>";

//returtns all TAs
//returns an array of rows
//each row is a TA
//each row is an array of this form $row = ['taid','firstname','lastname']
function getTAs(){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

    $query = $pdo->prepare("SELECT taid,firstname,lastname FROM TA");

    $query->execute();

	$pdo = null;
    return $query->fetchAll();
}

function addToTAWishlist($taid,$courseid,$profid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("INSERT INTO TAWishlist (taid,courseid,profid) VALUES (?,?,?)");
	$err1 = $query->execute(array($taid,$courseid,$profid));

	return $err1 == 1;
}

function addToTAPerformanceLog($taid,$courseid,$profid,$comment){
	$pdo = new PDO("sqlite:" . "DB/Main.db");
	$maxlogid = $pdo->query("SELECT MAX(logid) FROM TAPerformanceLog");
	$newlogid = $maxlogid->fetchColumn() + 1;

	$query = $pdo->prepare("INSERT INTO TAPerformanceLog (logid,taid,courseid,profid,comment) VALUES (?,?,?,?,?)");
	$err1 = $query->execute(array($newlogid,$taid,$courseid,$profid,$comment));

	return $err1 == 1;
}

function getProfid($userid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

    $query = $pdo->prepare("SELECT proffesorid FROM Prof Where userid == ?");

    $query->execute(array($userid));

	$pdo = null;
    return $query->fetch()[0];
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
	$pdo = null;
	return $query->fetchAll();
}

//returtns all Courses
//returns an array of rows
//each row is a TA
//each row is an array of this form $row = ['courseid', 'term_year', 'course_num', 'course_name', 'instructor_assigned_name']
function getCourseProf($profid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT c.courseid, c.term_year, c.course_num, c.course_name, c.instructor_assigned_name 
		FROM Course c, TeachingCourse tc
		WHERE tc.proffesorid == ? and c.courseid == tc.courseid");
	$query->execute(array($profid));
	$pdo = null;
	return $query->fetchAll();
}

function display($path) {
  $file = fopen($path,"r");
  while(!feof($file)) {
    $line = fgets($file);
    echo $line;
  }
  fclose($file);
}

function displaySub($path, $userid){
	$file = fopen($path,"r");
	while(!feof($file)) {
		$line = fgets($file);
		if (strstr($line,"STANDIN")){
			$line=str_replace("STANDIN",$userid, $line);
		}
  	if($line != ""){
			echo $line;
		}
  }
}

function displaySub2($path, $userid, $courseid){
	$file = fopen($path,"r");
	while(!feof($file)) {
		$line = fgets($file);
		if (strstr($line,"STANDIN")){
			$line=str_replace("STANDIN",$userid, $line);
		}
		if (strstr($line,"STAND2IN")){
			$line=str_replace("STAND2IN",$courseid, $line);
		}
  	if($line != ""){
			echo $line;
		}
  }
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