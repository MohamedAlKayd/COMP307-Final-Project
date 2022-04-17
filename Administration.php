<?php

/*Variables used for Administration*/
$userid = $_GET["Userid"];
$usertype = getUserType($userid);
$page = $_GET["Page"];

echo "<html>";
echo "<head>";
echo "</head>";
echo "<body>";

displayActive("matter/header.txt","Administration",$usertype);


if(empty($page)){
	displaySub("matter/ta_administration.txt", $userid);
}
else if($page == "ImportTACohort"){
	displaySub("matter/ImportTACohort.php", $userid);
}
else if($page == "TAInfoHistory"){
	displaySub("matter/TAInfoHistory.txt", $userid);
}
else if($page == "CourseTAHistory"){
	displaySub("matter/CourseTAHistory.txt", $userid);
}
else if($page == "AddTAToCourse"){
	displaySub("matter/AddTAToCourse.txt", $userid);
	
}
else if($page == "RemoveTAFromCourse"){
	displaySub("matter/RemoveTAFromCourse.txt", $userid);
}
else if($page == "ImportOldTAStatistics"){
	displaySub("matter/ImportOldTAStatistics.txt", $userid);
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


function getUserType($userid){
  $pdo = new PDO("sqlite:" . "DB/Main.db");

  $query = $pdo->prepare("SELECT usertype FROM UserInfo WHERE userid == ?");

  $query->execute(array($userid));

	$pdo = null;
  return $query->fetch()[0];
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