<?php
//returtns all comments a Ta has received
//returns an array of rows
//each row is a comment
//each row is an array of this form $row = ['review']
function getAllCommentsForTA($taid){
	$pdo = new PDO("sqlite:" . "Main.db");

    $query = $pdo->prepare("SELECT review FROM TAreview WHERE taid == ?");

    $query->execute(array($taid));

	$pdo = null;
    return $query->fetchAll();
}


function getAverageTArating($taid){
	$pdo = new PDO("sqlite:" . "Main.db");

    $query = $pdo->prepare("SELECT avg(rating) FROM TAreview WHERE taid == ?");

    $query->execute(array($taid));

	$pdo = null;
    return $query->fetch()[0];
}

//returtns all Courses
//returns an array of rows
//each row is a TA
//each row is an array of this form $row = ['courseid', 'term_year', 'course_num', 'course_name', 'instructor_assigned_name']
function getCourses(){
	$pdo = new PDO("sqlite:" . "Main.db");

    $query = $pdo->prepare("SELECT courseid, term_year, course_num, course_name, instructor_assigned_name FROM Course");

    $query->execute();

	$pdo = null;
    return $query->fetchAll();
}

//returtns all TAs
//returns an array of rows
//each row is a TA
//each row is an array of this form $row = ['taid','firstname','lastname']
function geTAs(){
	$pdo = new PDO("sqlite:" . "Main.db");

    $query = $pdo->prepare("SELECT taid,firstname,lastname FROM TA");

    $query->execute();

	$pdo = null;
    return $query->fetchAll();
}

//returns an array of rows (access each rows like this: foreach($arrray as $row){})
//each row is a Course associated with that TA
//each row is an array of this form $row = ['courseid', 'term_year', 'course_num', 'course_name', 'instructor_assigned_name']
function getCourseforTA($taid){
	$pdo = new PDO("sqlite:" . "Main.db");

	$query = $pdo->prepare("SELECT c.courseid, c.term_year, c.course_num, c.course_name, c.instructor_assigned_name 
		FROM Course c, AssistingCourse ac
		WHERE ac.taid == ? and c.courseid == ac.courseid");
	$query->execute(array($taid));
	$pdo = null;
	return $query->fetchAll();
}


//returns an array of rows (access each rows like this: foreach($arrray as $row){})
//each row is a TA associated with that course
//each row is an array of this form $row = ['taid', 'firstname', 'lastname']
function getTAforCourse($courseid){
	$pdo = new PDO("sqlite:" . "Main.db");

	$query = $pdo->prepare("SELECT ta.taid, ta.firstname, ta.lastname 
		FROM TA ta, AssistingCourse ac
		WHERE ta.taid == ac.taid and ac.courseid == ?");
	$query->execute(array($courseid));
	$pdo = null;
	return $query->fetchAll();
}
?>