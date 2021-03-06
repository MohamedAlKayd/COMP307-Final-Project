<?php

$userid = $_GET["Userid"];

$USERTYPE = getUserType($userid);

echo "<html>";
echo "<head>";
echo "</head>";
echo "<body>";
displayActive("matter/header.txt",$_GET["Page"],$USERTYPE);
// --------- ROUTING WEBPAGE BODY -----------
echo "<text>WELCOME ".$USERTYPE."</text><br>";
if($USERTYPE == "Student"){
	if($_GET["Page"] == "SelectCourse"){
		displayCourses($userid,"Taking");
	}
	else if($_GET["Page"] == "CourseSelected"){
		$courseid = $_POST['course'];
		$studentid = getStudentid($userid);
		if(strlen($courseid) > 15){
			echo "<script>alert(\"Error Course Not Added\")</script>";
		}
		else if(addCourseForStudent($studentid,$courseid)){
			echo "<script>alert(\"Course was Added\")</script>";
		}
		else{
			echo "<script>alert(\"Error Course Not Added\")</script>";
		}
		displayCourseButton("matter/ta_management.txt",$userid);
	}
	else{
		displayCourseButton("matter/ta_management.txt",$userid);
	}
}
else if($USERTYPE == "Prof"){
	if($_GET["Page"] == "SelectCourse"){
		displayCourses($userid,"Teaching");
	}
	else if($_GET["Page"] == "CourseSelected"){
		$courseid = $_POST['course'];
		$teacherid = getTeacherid($userid);
		if(strlen($courseid) > 15){
			echo "<script>alert(\"Error Course Not Added\")</script>";
		}
		else if(addCourseForTeacher($teacherid,$courseid)){
			echo "<script>alert(\"Course was Added\")</script>";
		}
		else{
			echo "<script>alert(\"Error Course Not Added\")</script>";
		}
		displayCourseButton("matter/ta_management.txt",$userid);
	}
	else{
		displayCourseButton("matter/ta_management.txt",$userid);
	}
}
// --------- COMMON WEBPAGE BOTTOM ----------
display("matter/footer.txt");
echo "<body>";
echo "</html>";

function addCourseForStudent($studentid,$courseid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");
	$query = $pdo->prepare("INSERT INTO TakingCourse (studentid,courseid) VALUES (?,?)");
	$err1 = $query->execute(array($studentid,$courseid));
	
	return $err1 == 1;
}

function addCourseForTeacher($teacherid,$courseid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");
	$query = $pdo->prepare("INSERT INTO TeachingCourse (proffesorid,courseid) VALUES (?,?)");
	$err1 = $query->execute(array($teacherid,$courseid));
	
	return $err1 == 1;
}

function getStudentid($userid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT s.studentid FROM Student s WHERE s.userid == ?");
	
	$query->execute(array($userid));
	
	return $query->fetch()[0];
}

function getTeacherid($userid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT proffesorid FROM Prof WHERE userid == ?");
	
	$query->execute(array($userid));
	
	return $query->fetch()[0];
}

function displayCourses($userid,$standin){
	echo "<form method=\"post\" action=\"dashboard.php?Page=CourseSelected&Userid=".$userid."\">";
    echo "<div class=\"search_categories\">";
	echo "<div class=\"select-menu\">";
	echo "<h2>Add Courses You are ".$standin."</h2>";
	echo "<text> Select A Course </text><br>";
	echo "<select name=\"course\">";
    	echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
    	
	$courseArray = getCourses();
	foreach($courseArray as $row){
		echo "<option value=\"" . $row['courseid'] . "\" >".$row['term_year']."-".$row['course_num']."-".$row['course_name']."</option>";
	}

	echo "</select><br><br>";
	echo "</div>";
	echo "</div>";
	echo "<input id=\"info\" type=\"submit\" name=\"submit\" value=\"Add\"><br><br><br><br>";
	echo "</form>";
}

function displayCourseButton($path,$userid) {
	$file = fopen($path,"r");
	while(!feof($file)) {
	  $line = fgets($file);
	  if (strstr($line,"STANDIN")){
		$line=str_replace("STANDIN",$userid,$line);
		$line=str_replace("Management","dashboard",$line);
	}
	  echo $line;
	}
	fclose($file);
  }

//returtns all Courses
//returns an array of rows
//each row is a TA
//each row is an array of this form $row = ['courseid', 'term_year', 'course_num', 'course_name', 'instructor_assigned_name']
function getCourses(){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

    $query = $pdo->prepare("SELECT courseid, term_year, course_num, course_name, instructor_assigned_name FROM Course");

    $query->execute();

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


function getUserType($userid){
  $pdo = new PDO("sqlite:" . "DB/Main.db");

  $query = $pdo->prepare("SELECT usertype FROM UserInfo WHERE userid == ?");

  $query->execute(array($userid));

	$pdo = null;
  return $query->fetch()[0];
}



 ?>
