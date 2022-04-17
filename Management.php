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
	displaySub("matter/SelectCourse.txt", $userid);
}
else if($page == "ProfsTAPerformanceLog"){
	displaySub("matter/ProfsTAPerformanceLog.txt", $userid);
}
else if($page == "TAWishList"){
	displaySub("matter/TAWishList.txt", $userid);
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