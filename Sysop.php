<?php
ini_set("~/public_html/working/tmp");
/*Variables used for Sysop*/
$userid = $_GET["Userid"];
$usertype = getUserType($userid);
$page = $_GET["Page"];

echo "<html>";
echo "<head>";
echo "</head>";
echo "<body>";

displayActive("matter/header.txt","Sysop",$usertype);
displayButtons("matter/SysopMenu.txt", $page,$userid);

if($page == "ManageUsers"){
	if(isset($_POST["Edit"])){
		$OtherUserid = $_POST["User"];
		displayEditUser($OtherUserid);
	}
	else if(isset($_POST["Remove"])){
		$OtherUserid = $_POST["User"];
		$Otherusertype = getUserType($OtherUserid);
		echo "ID:".$OtherUserid."<br>";
		echo "TYPE:".$Otherusertype."<br>";
		removeUser($OtherUserid, $Otherusertype);
		echo "REMOVED";
		header("Location: Sysop.php?Page=ManageUsers&Userid=".$userid);
	}
	else{
		echo "<form method=\"post\" action=\"Sysop.php?Page=ManageUsers&Userid=".$userid."\">";
		echo "<div class=\"search_categories\">";
		echo "<div class=\"select-menu\">";	
		echo "<h2>Users</h2>";
		echo "<text style=\"margin:15px;\"> Select A User:</text>";
		echo "<br>";
		echo "<select name=\"User\">";
			echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
			
		$UserArray = getUsers();
		foreach($UserArray as $row){
			echo "<option value=\"" . $row['userid'] . "\" >".$row['usertype'].": ".$row['firstname']." ".$row['lastname'].",".$row['username'].",".$row['password']."</option>";
		}

		echo "</select><br><br>";
	    //echo "<div class=\"field\">";	
		echo "<input style=\"margin:15px;\" type=\"submit\" name=\"Edit\" value=\"Edit\">";
		//echo "</div>";	

        //echo "<div class=\"field\">";
		echo "<input style=\"margin:5px;\" type=\"submit\" name=\"Remove\" value=\"Remove\">";
		//echo "</div>";
		echo "</form>";
		
		echo "</div>";
		echo "</div>";
	}
}

else if($page == "AddUser"){
	display("matter/AddUser.txt");
}
else if($page == "AddCourse"){
	display("matter/AddCourse.txt");
}
else if($page == "ImportProfs"){
	display("matter/ImportProfs.txt");
}
else if($page == "ImportCourses"){
	display("matter/ImportCourses.txt");
}



display("matter/footer.txt");

echo "<body>";
echo "</html>";



function displayEditUser($Userid){
	$usertype = getUserType($Userid);
	$User = getUser($Userid);

	echo "<form id = \"Student\" action=\"EdditUser.php?Userid=".$Userid."\" method=\"post\">";
    echo "<h3>Enter New Information</h3>";

	
    
	echo "<div class=\"field\">";
		echo "First name: ";
    	echo "<input type=\"text\" placeholder=\"".$User['firstname']."\" name=\"fname\"";
    echo "</div>";
	echo "<div class=\"field\">";
		echo "Last name: ";
    	echo "<input type=\"text\" placeholder=\"".$User['lastname']."\" name=\"lname\"";
    echo "</div>";
	echo "<div class=\"field\">";
		echo "Username: ";
    	echo "<input type=\"text\" placeholder=\"".$User['username']."\" name=\"username\"";
    echo "</div>";
	echo "<div class=\"field\">";
		echo "Email: ";
    	echo "<input type=\"text\" placeholder=\"".$User['email']."\" name=\"email\"";
    echo "</div>";

	if($usertype == "Student"){
		$stid = getStudentId($Userid);
		echo "<div class=\"field\">";
		echo "Studentid: ";
    	echo "<input type=\"text\" placeholder=\"".$stid."\" name=\"studentid\"";
    	echo "</div>";
	}

	echo "</select><br><br>";
	echo "<input type=\"submit\" name=\"Commit Change\" value=\"Commit Change\">";
	echo "</form>";
}


//returns an array of rows (access each rows like this: foreach($arrray as $row){})
//each row is a User
//each row is an array of this form $row = ['userid','firstname','lastname','username','password','usertype']
function getUsers(){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT userid,firstname,lastname,username,password,usertype 
	FROM UserInfo
	ORDER BY usertype");

	$query->execute();

$pdo = null;
	return $query->fetchAll();
}

function getStudentId($userid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT studentid 
	FROM Student
	Where userid == ?"
	);

	$query->execute(array($userid));

	$pdo = null;
	return $query->fetch()[0];
}

function getUser($userid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT userid,firstname,lastname,username,email,usertype 
	FROM UserInfo
	Where userid == ?"
	);

	$query->execute(array($userid));

	$pdo = null;
	return $query->fetch();
}

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

function displayButtons($path,$target,$userid) {
	$file = fopen($path,"r");
	while(!feof($file)) {
		  $line = fgets($file);
		  if (strstr($line,$target)){
			  $line=str_replace("\">","\"><b>",$line);
			  $line=str_replace("</","</b></",$line);
		  }
		  if (strstr($line,"STANDIN")){
			$line=str_replace("STANDIN",$userid,$line);
		  }
		  echo $line;  
	}
	fclose($file);
}



function removeUser($userid, $usertype){
	$pdo = new PDO("sqlite:" . "DB/Main.db");
	
	$query = $pdo->prepare("DELETE FROM User WHERE userid == ?");

	$err1 = $query->execute(array($userid));

	$query2 = $pdo->prepare("DELETE FROM ".$usertype." WHERE userid == ?");
	
	$err2 = $query2->execute(array($userid));

	return ($err1 == 1) and ($err1 == 1); 
}

?>