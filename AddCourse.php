<?php

$year = $_POST["year"];
$term = $_POST["term"];
$course_num = $_POST["course_num"];
$course_name = $_POST["course_name"];
$instructor = $_POST["instructor"];

addCourse($year,$term, $course_num, $course_name, $instructor);
header("Location: main.php?Page=Sysop");

function addCourse($year,$term, $course_num, $course_name, $instructor){
        
    $pdo = new PDO("sqlite:" . "DB/Main.db");
    $maxcourseid = $pdo->query("SELECT MAX(courseid) FROM Course");
    $newcourseid = $maxcourseid->fetchColumn() + 1;

    $query = $pdo->prepare("INSERT INTO Course (courseid, term_year, course_num, course_name, instructor_assigned_name) VALUES (?,?,?,?,?)");
    $err1 = $query->execute(array($newcourseid, $term."_".$year, $course_num, $course_name, $instructor));
    
    $pdo = null;
    return ($err1 == 1);

}

?>