<?php

// User types are either Sysop,Admin,TA,Student or Prof

$USERNAME = " ";
$EMAIL = " ";
$PWD = " ";
$USERTYPE = " ";
$clickedOnRegister="False";
$clickedOnLogin="False";


// LOGIN
if(isset($_POST["login"])){
    $EMAIL = $_POST["email"];
	$PWD = $_POST["passwd"]; 
	$USERTYPE = $_POST["utype"]; 
	$clickedOnLogin="True";  
}

/*
CSV File intialization
*/

if($clickedOnRegister=="True"){
	/*$file_open = fopen("database.csv", "a+");*/
	
}

if($clickedOnLogin=="True"){
	/*$file_open = fopen("database.csv", "r");*/
	
}


// --------- COMMON WEBPAGE TOP ---------
echo "<html>";
echo "<head>";
echo "</head>";
echo "<body>";

displayActive("matter/header.txt",$_GET["Page"],$USERTYPE);

// --------- ROUTING WEBPAGE BODY -----------
if (sizeof($_GET)==0 || $_GET["Page"]=="DashBoard") {
	// DashBoard
	display("matter/dashboard.txt");

} 
/*4 Main Pages*/
else if ($_GET["Page"]=="Administration") {
	// TA Administration
	display("matter/ta_administration.txt");
} else if ($_GET["Page"]=="Management") {
	// TA Management
	display("matter/ta_management.txt");
} else if ($_GET["Page"]=="Rate") {
	// Rate a TA
	display("matter/rate_a_ta.txt");
} else if ($_GET["Page"]=="Sysop") {
	// Sysop Tasks
	display("matter/sysop_task.txt");

/* 6 TA Administration Pages*/
} else if ($_GET["Page"]=="ImportTACohort") {
	display("matter/ImportTACohort.txt");
}
else if ($_GET["Page"]=="TAInfoHistory") {
	display("matter/TAInfoHistory.txt");
}
else if ($_GET["Page"]=="CourseTAHistory") {
	display("matter/CourseTAHistory.txt");
}
else if ($_GET["Page"]=="AddTAToCourse") {
	display("matter/AddTAToCourse.txt");
}
else if ($_GET["Page"]=="RemoveTAFromCourse") {
	display("matter/RemoveTAFromCourse.txt");
}
else if ($_GET["Page"]=="ImportOldTAStatistics") {
	display("matter/ImportOldTAStatistics.txt");
}

/* 3 Sysop Tasks Pages*/
else if ($_GET["Page"]=="ManageUsers") {
	display("matter/ManageUsers.txt");
}
else if ($_GET["Page"]=="ImportProfessorandCourse") {
	display("matter/ImportProfessorandCourse.txt");
}
else if ($_GET["Page"]=="ManualAddProfessorandCourse") {
	display("matter/ManualAddProfessorandCourse.txt");
}

/* 3 TA Management Pages*/
else if ($_GET["Page"]=="SelectCourse") {
	display("matter/SelectCourse.txt");
}
else if ($_GET["Page"]=="Channel") {
	display("matter/Channel.txt");
}
else if ($_GET["Page"]=="AllTAsReport") {
	display("matter/AllTAsReport.txt");
}

/* 4 Select Course Pages*/
else if ($_GET["Page"]=="OfficeHoursResponsibilitiesSheet") {
	display("matter/OfficeHoursResponsibilitiesSheet.txt");
}
else if ($_GET["Page"]=="TAEvaluationTAWorkload") {
	display("matter/TAEvaluationTAWorkload.txt");
}
else if ($_GET["Page"]=="ProfsTAPerformanceLog") {
	display("matter/ProfsTAPerformanceLog.txt");
}
else if ($_GET["Page"]=="TAWishList") {
	display("matter/TAWishList.txt");
}

/*1 Office Hours Responsibilites Sheet Page*/
else if ($_GET["Page"]=="Export") {
	display("matter/Export.txt");
}

/*1 Prof's TA Performance Log Page*/
else if ($_GET["Page"]=="EdStatsImport") {
	display("matter/EdStatsImport.txt");
}

/*1 Manage User's Page*/
else if ($_GET["Page"]=="ConfirmationEmail") {
	display("matter/ConfirmationEmail.txt");
}

/*1 Rate a TA's Page*/
else if ($_GET["Page"]=="RateReviewaTAbyCourse") {
	display("matter/RateReviewaTAbyCourse.txt");
}

/*Error Page*/
else {
	echo "404: Invalid Page!";
}

// --------- COMMON WEBPAGE BOTTOM ----------
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

//used at login
//returns true if there exists a user with that username and password for that user type
function userExists($username,$password,$USERTYPE) {
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT COUNT(*) FROM User u, " . $USERTYPE . " t
		WHERE u.username == ? and u.password == ? and u.userid == t.userid");

	$query->execute(array($username,$password));	

	$row = $query->fetch();
	$count = $row[0];

	$pdo = null;
	return $count == 1;
	
}

//used to register new student user
//returns true if the student was succesfully registered
function registerStudent($username,$password,$firstname,$lastname,$email,$studentid){
	if(userExists($username,$password,"Student") or studentExists($studentid)){
		return False;
	}

	$pdo = new PDO("sqlite:" . "DB/Main.db");
	
	$maxuserid = $pdo->query("SELECT MAX(userid) FROM User");
	$newuserid = $maxuserid->fetchColumn() + 1;
	
	$userquery = $pdo->prepare("INSERT INTO User (userid, username, password) VALUES (?,?,?)");
	$err1 = $userquery->execute(array($newuserid, $username, $password));
	$studentquery = $pdo->prepare("INSERT INTO Student (studentid, userid, firstname, lastName, email) VALUES (?,?,?,?,?)");
	$err2 = $studentquery->execute(array($studentid, $newuserid, $firstname, $lastname, $email));
	
	$pdo = null;
	return ($err1 == 1) and ($err1 == 1);
}

//return true if a student with that studentid exists
function studentExists($studentid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");
	
	$pre = $pdo->prepare("SELECT Count(*) FROM Student Where studentid == ?");
	$pre->execute(array($studentid));
	
	$row = $pre->fetch();
	$count = $row[0];
	
	$pdo = null;
	return $count == 1;
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




	

?>
