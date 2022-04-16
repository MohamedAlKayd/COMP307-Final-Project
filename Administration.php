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
	display("matter/ta_administration.txt");
}
else if($page == "ImportTACohort"){
	display("matter/ImportTACohort.txt");
}
else if($page == "TAInfoHistory"){
	display("matter/TAInfoHistory.txt");
}
else if($page == "CourseTAHistory"){
	display("matter/CourseTAHistory.txt");
}
else if($page == "AddTAToCourse"){
	display("matter/AddTAToCourse.txt");
}
else if($page == "RemoveTAFromCourse"){
	display("matter/RemoveTAFromCourse.txt");
}
else if($page == "ImportOldTAStatistics"){
	display("matter/ImportOldTAStatistics.txt");
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