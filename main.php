<?php

// User types are either Sysop,Admin,TA,Student or Prof

$USERNAME = " ";
$EMAIL = " ";
$PWD = " ";
$USERTYPE = " ";
$clickedOnRegister="False";
$clickedOnLogin="False";

// REGISTER
if(isset($_POST["formSubmit"])){
    $USERNAME = $_POST["uname"];
    $EMAIL = $_POST["email"];
    $PWD = $_POST["passwd"];
    $USERTYPE = $_POST["utype"];
	$clickedOnRegister="True";
}
// LOGIN
if(isset($_POST["formReg"])){
    $USERNAME = $_POST["uname"];
    $PWD = $_POST["passwd"];  
	$clickedOnLogin="True";  
}

/*
CSV File intialization
*/

if($clickedOnRegister=="True"){
	$file_open = fopen("database.csv", "a+");
}

if($clickedOnLogin=="True"){
$file_open = fopen("database.csv", "r");
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

?>
