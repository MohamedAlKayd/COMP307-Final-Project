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
	displaySub("matter/ImportTACohort.txt", $userid);
	displaySub("matter/ImportCourseQuota.txt", $userid);
}
else if($page == "TAInfoHistory"){
	if(isset($_POST["submitTA"])){
		$taid = $_POST["taid"];
		displayTAhistory($taid);
	}
	else{
		displayAllTAs("TAInfoHistory",$userid);
	}
}
else if($page == "CourseTAHistory"){
	if(isset($_POST["submitTA"])){
		$taid = $_POST["taid"];
		displayhistory("matter/CourseTAHistory.txt", $taid);
	}
	else{
		displayAllTAs("CourseTAHistory",$userid);
	}
}
else if($page == "AddTAToCourse"){
	if(isset($_POST["submitTA"])){
		$taid = $_POST["taid"];
		displayCourses($userid,$taid, "Add", "AddTAToCourse");
	}
	else if(isset($_POST["submitCourse"])){
		$courseid = $_POST["courseid"];
		displayTAs($userid,$courseid, "Add", "AddTAToCourse");
	}
	else if(isset($_POST["submitCourseTA"])){
		$taid = $_GET["TAid"];
		$assigned_hours = $_POST["hours"];
		$courseid = $_POST["courseid"];
		echo $assigned_hours."<br>";
		echo $courseid."<br>";
		echo $taid."<br>";
		addTatoCourse($taid,$courseid,$assigned_hours);
		header("Location: main.php?Page=Administration");
	}
	else if(isset($_POST["submitTACourse"])){
		$courseid = $_GET["Courseid"];
		$assigned_hours = $_POST["hours"];
		$taid = $_POST["taid"];
		echo $assigned_hours."<br>";
		echo $courseid."<br>";
		echo $taid."<br>";
		addTatoCourse($taid,$courseid,$assigned_hours);
		header("Location: main.php?Page=Administration");
	}
	else{
		//displaySub("matter/AddTAToCourse.txt", $userid);
		displayChooseTAorCourse($userid, "AddTAToCourse",  "Add");
	}
}
else if($page == "RemoveTAFromCourse"){
	if(isset($_POST["submitTA"])){
		$taid = $_POST["taid"];
		displayCourses($userid,$taid,"Remove", "RemoveTAFromCourse");
	}
	else if(isset($_POST["submitCourse"])){
		$courseid = $_POST["courseid"];
		displayTAs($userid,$courseid,"Remove", "RemoveTAFromCourse");
	}
	else if(isset($_POST["submitCourseTA"])){
		$taid = $_GET["TAid"];
		$courseid = $_POST["courseid"];
		echo $courseid."<br>";
		echo $taid."<br>";
		removeTafromCourse($taid,$courseid);
		header("Location: main.php?Page=Administration");
	}
	else if(isset($_POST["submitTACourse"])){
		$courseid = $_GET["Courseid"];
		$taid = $_POST["taid"];
		echo $courseid."<br>";
		echo $taid."<br>";
		removeTafromCourse($taid,$courseid);
		header("Location: main.php?Page=Administration");
	}
	else{
		displayChooseTAorCourse($userid, "RemoveTAFromCourse",  "Remove");
	}
}
else if($page == "ImportOldTAStatistics"){
	displaySub("matter/ImportOldTAStatistics.txt", $userid);
}

display("matter/footer.txt");

echo "<body>";
echo "</html>";

function displayTAhistory($taid){
	$ta = getTA($taid);
	$taname = $ta['firstname']." ".$ta[2];
	$avrRating = getAverageTArating($taid);

	$studentComments = getAllCommentsForTA($taid);

	$profComments = getAllLogsForTA($taid);

	$wishlist = getWishList($taid);

	echo "<h2>TA History for ".$taname."</h2>";
	echo "Average Rating: ".$avrRating;

	echo "<h4>Student Comments: </h4>";
	displayComments($studentComments);
	echo "<h4>Profesor Comments: </h4>";
	displayComments($profComments);

	echo "<h4>Wish List: </h4>";
	displayWish($wishlist);

	echo "<h4>Course History: </h4>";
	displayhistory("matter/CourseTAHistory.txt", $taid);
}

function displayWish($wishlist){
	$count = 0;
	foreach($wishlist as $wish){
		if($count > 0){
			echo "<br>";
		}
		
		$courseid = $wish[0];
		$profid = $wish[1];
		$course = getCourse($courseid);
		$coursename = $course['term_year']."-".$course['course_num']."-".$course['course_name'];
		$prof = getProf($profid);
		$profname = $prof['firstname']." ".$prof[2];
		$count += 1;
		echo "Wish List Entry".$count.": Proffesor ".$profname." Wants this TA for The following Course: ".$coursename."<br>";
	}
}


function displayComments($comments){
	$count = 0;
	foreach($comments as $content){
		if($count > 0){
			echo "<br>";
		}
		$count += 1;
		echo "Comment ".$count.": ".$content[0]."<br>";
	}
}

function getWishList($taid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

    $query = $pdo->prepare("SELECT courseid,profid FROM TAWishlist WHERE taid == ?");

    $query->execute(array($taid));

	$pdo = null;
    return $query->fetchAll();
}

function getAllLogsForTA($taid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

    $query = $pdo->prepare("SELECT comment FROM TAPerformanceLog WHERE taid == ?");

    $query->execute(array($taid));

	$pdo = null;
    return $query->fetchAll();
}

//returtns all comments a Ta has received
//returns an array of rows
//each row is a comment
//each row is an array of this form $row = ['review']
function getAllCommentsForTA($taid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

    $query = $pdo->prepare("SELECT review FROM TAreview WHERE taid == ?");

    $query->execute(array($taid));

	$pdo = null;
    return $query->fetchAll();
}

function getAverageTArating($taid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

    $query = $pdo->prepare("SELECT avg(rating) FROM TAreview WHERE taid == ?");

    $query->execute(array($taid));

	$pdo = null;
    return $query->fetch()[0];
}

function displayAllTAs($page,$userid){
	echo "<form method=\"post\" action=\"Administration.php?Page=".$page."&Userid=".$userid."\">";

		echo "<h2>TA History</h2>";
		echo "Select A TA<br>";
		echo "<select name=\"taid\">";
			echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
			
		$TAArray = getTAs();
		foreach($TAArray as $row){
			echo "<option value=\"" . $row['taid'] . "\" >".$row['firstname']." ".$row[2]."</option>";
		}

		echo "</select><br><br>";
		echo "<input type=\"submit\" name=\"submitTA\" value=\"submit\"><br><br><br><br>";
		echo "</form>";
}


function removeTafromCourse($taid,$courseid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");
	
	$query = $pdo->prepare("DELETE FROM AssistingCourse WHERE taid == ? and courseid == ?");

	$err1 = $query->execute(array($taid,$courseid));

	return ($err1 == 1); 
}

function addTatoCourse($taid,$courseid,$assigned_hours){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("INSERT INTO AssistingCourse (taid,courseid,assigned_hours) VALUES (?,?,?)");
	$err1 = $query->execute(array($taid,$courseid,$assigned_hours));

	return $err1 == 1;
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

function getTAforNotCourse($courseid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT DISTINCT ta.taid, ta.firstname, ta.lastname 
		FROM TA ta, AssistingCourse ac
		WHERE ta.taid == ac.taid and ac.courseid != ?");
	$query->execute(array($courseid));
	$pdo = null;
	return $query->fetchAll();
}

function getCourse($courseid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT c.term_year, c.course_num, c.course_name 
		FROM Course c 
		WHERE c.courseid == ?;");
	
	$query->execute(array($courseid));
	
	return $query->fetch();
}

function displayTAs($userid,$courseid,$function, $page){
	$course = getCourse($courseid);
	$coursename = $course['term_year']."-".$course['course_num']."-".$course['course_name'];
	echo "<form method=\"post\" action=\"Administration.php?Page=".$page."&Userid=".$userid."&Courseid=".$courseid."\">";

		echo "<h2>".$function."TA </h2>";
		echo "Select A TA for ".$coursename."<br>";
		echo "<select name=\"taid\">";
			echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
		
		if ($function == "Add"){
			$TAArray = getTAs();
		}
		else{
			$TAArray = getTAforCourse($courseid);
		}
		foreach($TAArray as $row){
			echo "<option value=\"" . $row['taid'] . "\" >".$row['firstname']." ".$row[2]."</option>";
		}
		echo "</select><br><br>";
		if ($function == "Add"){
			echo "Select assigned_hours<br>";
			echo "<select name=\"hours\">";
				echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
				echo "<option value=\"90\">90</option>";
				echo "<option value=\"180\" >180</option>";
			echo "</select><br><br>";
		}

		echo "<input type=\"submit\" name=\"submitTACourse\" value=\"".$function."\"><br><br><br><br>";
		echo "</form>";
}

function getTA($taid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT ta.taid, ta.firstname, ta.lastname 
		FROM TA ta
		WHERE ta.taid == ?");
	
	$query->execute(array($taid));
	
	$pdo = null;
	return $query->fetch();
}

function getProf($profid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT p.proffesorid, p.firstname, p.lastname 
		FROM Prof p
		WHERE p.proffesorid == ?");
	
	$query->execute(array($profid));
	
	$pdo = null;
	return $query->fetch();
}

function displayCourses($userid,$taid, $function, $page){
	$ta = getTA($taid);
	$taname = $ta['firstname']." ".$ta[2];
	echo "<form method=\"post\" action=\"Administration.php?Page=".$page."&Userid=".$userid."&TAid=".$taid."\">";

		echo "<h2>".$function." TA</h2>";
		echo "Select A Course for ".$taname."<br>";
		echo "<select name=\"courseid\">";
			echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
		

		if ($function == "Add"){
			$CourseArray = getCourses();
		}
		else{
			$CourseArray = getCourseforTA($taid);
		}
		foreach($CourseArray as $row){
			echo "<option value=\"" . $row['courseid'] . "\" >".$row['term_year']."-".$row['course_num']."-".$row['course_name']."</option>";
		}

		echo "</select><br><br>";

		if ($function == "Add"){
			echo "Select assigned_hours<br>";
			echo "<select name=\"hours\">";
				echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
				echo "<option value=\"90\">90</option>";
				echo "<option value=\"180\" >180</option>";
			echo "</select><br><br>";
		}

		echo "<input type=\"submit\" name=\"submitCourseTA\" value=\"".$function."\"><br><br><br><br>";
		echo "</form>";
}

function displayChooseTAorCourse($userid, $page, $function){
	echo "<div>";
	echo "<form method=\"post\" action=\"Administration.php?Page=".$page."&Userid=".$userid."\">";

	echo "<h2>".$function." TA</h2>";
	echo "Select A TA<br>";
	echo "<select name=\"taid\">";
		echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
			
	$TAArray = getTAs();
	foreach($TAArray as $row){
		echo "<option value=\"" . $row['taid'] . "\" >".$row['firstname']." ".$row[2]."</option>";
	}

	echo "</select><br><br>";
	echo "<input type=\"submit\" name=\"submitTA\" value=\"submit\"><br><br><br><br>";
	echo "</form>";
	echo "</div>";

	echo "<div>";
	echo "<form method=\"post\" action=\"Administration.php?Page=".$page."&Userid=".$userid."\">";

	echo "Select A Course<br>";
	echo "<select name=\"courseid\">";
		echo "<option value=\"----------------------------------------------------------\" >----------------------------------------------------------</option>";
			
	$CourseArray = getCourses();
	foreach($CourseArray as $row){
		echo "<option value=\"" . $row['courseid'] . "\" >".$row['term_year']."-".$row['course_num']."-".$row['course_name']."</option>";
	}

	echo "</select><br><br>";
	echo "<input type=\"submit\" name=\"submitCourse\" value=\"submit\"><br><br><br><br>";
	echo "</form>";
	echo "</div>";
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


function displayhistory($path, $taid){
	$file = fopen($path,"r");
	$ta = getTAinfo($taid);
	$fname = $ta[0];
	$lname = $ta[1];

	while(!feof($file)) {
		$line = fgets($file);
		if (strstr($line,"NAMESTANDIN")){
			$line=str_replace("NAMESTANDIN",$fname." ".$lname, $line);
		}
		if (strstr($line,"ADDCONTENTHERE")){
			$line = "";
			$CourseArray = getCourseforTA($taid);
			foreach($CourseArray as $row){
				$coursename = $row['course_num']."-".$row['course_name'];
				$term_year = $row['term_year'];
				$prof = $row['instructor_assigned_name'];

				echo "<tr>";
    				echo "<td>".$term_year."</td>";
    				echo "<td>".$coursename."</td>";
					echo "<td>".$prof."</td>";
  				echo "</tr>";

			}
		}
  	if($line != ""){
			echo $line;
		}
  }
}

function getCourseforNotTA($taid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT DISTINCT c.courseid, c.term_year, c.course_num, c.course_name, c.instructor_assigned_name 
		FROM Course c, AssistingCourse ac
		WHERE ac.taid != ? and c.courseid == ac.courseid
		ORDER BY c.term_year");
	$query->execute(array($taid));
	$pdo = null;
	return $query->fetchAll();
}

//returns an array of rows (access each rows like this: foreach($arrray as $row){})
//each row is a Course associated with that TA
//each row is an array of this form $row = ['courseid', 'term_year', 'course_num', 'course_name', 'instructor_assigned_name']
function getCourseforTA($taid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT c.courseid, c.term_year, c.course_num, c.course_name, c.instructor_assigned_name 
		FROM Course c, AssistingCourse ac
		WHERE ac.taid == ? and c.courseid == ac.courseid
		ORDER BY c.term_year");
	$query->execute(array($taid));
	$pdo = null;
	return $query->fetchAll();
}

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

function getTAinfo($taid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

    $query = $pdo->prepare("SELECT firstname,lastname FROM TA Where taid == ?");

    $query->execute(array($taid));

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