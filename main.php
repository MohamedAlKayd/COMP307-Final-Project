<?php
// User types are either Sysop,Admin,TA,Student or Prof
$USERTYPE = "Prof";


// --------- COMMON WEBPAGE TOP ---------
echo "<html>";
echo "<head>";
echo "</head>";
echo "<body>";

displayActive("header.txt",$_GET["Page"],$USERTYPE);

// --------- ROUTING WEBPAGE BODY -----------
if (sizeof($_GET)==0 || $_GET["Page"]=="DashBoard") {
	// DashBoard
	display("dashboard.txt");
} else if ($_GET["Page"]=="Administration") {
	// TA Administration
	display("ta_administration.txt");
} else if ($_GET["Page"]=="Management") {
	// TA Management
	display("ta_management.txt");
} else if ($_GET["Page"]=="Rate") {
	// Rate a TA
	display("rate_a_ta.txt");
} else if ($_GET["Page"]=="Sysop") {
	// Sysop Tasks
	display("sysop_task.txt");
} else {
	// ERROR PAGE
	echo "404: Invalid Page!";
}

// --------- COMMON WEBPAGE BOTTOM ----------
display("footer.txt");

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
