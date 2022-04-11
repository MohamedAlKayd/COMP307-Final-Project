<?php

// User types are either Sysop,Admin,TA,Student or Prof

$USERNAME = " ";
$EMAIL = " ";
$PWD = " ";
$USERTYPE = " ";
$clickedOnRegister="False";
$clickedOnLogin="False";

if(isset($_POST["signup"])){
	$FNAME = $_POST["fname"];
	$LNAME = $_POST["lname"];
    $USERNAME = $_POST["uname"];
    $EMAIL = $_POST["email"];
    $PWD = $_POST["passwd"];
    $STID = $_POST["stdid"];
	$clickedOnRegister="True";
}

if($clickedOnRegister){
    if(registerStudent($USERNAME, $PWD, $FNAME, $LNAME, $STID)){
		echo "Resigtration Succesful!";
	}
 
}
else{
    echo "There's Something wrong! ";
    echo "Please check your student ID. ";
    echo "Your record does not exist in our database. ";
}
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
} else if ($_GET["Page"]=="Administration") {
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
} else {
	// ERROR PAGE
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
function addTAreview($studentid, $TAid, $rating, $review){
	$pdo = new PDO("sqlite:" . "DB/Main.db");
	$maxreviewid = $pdo->query("SELECT MAX(reviewid) FROM TAreview");
	$newreviewid = $maxreviewid->fetchColumn() + 1;

	$query = $pdo->prepare("INSERT INTO TAreview (reviewid, taid, studentid, rating, review) VALUES (?,?,?,?,?)");
	$err1 = $query->execute(array($newreviewid, $TAid, $studentid, $rating, $review));
	
	$pdo = null;
	return ($err1 == 1);

}



	

?>
