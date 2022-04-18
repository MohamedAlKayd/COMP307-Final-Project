<?php
    echo "start";
    $content = $_POST['con'];

    $ar = explode("\n",$content); 

    foreach($ar as $line){
        $ar2 = explode(",",$line);
        $term_year = $ar2[0];
        $course_num = $ar2[1];
        $course_name = $ar2[2];
        $instructor = $ar2[3];
        addCourse($term_year, $course_num, $course_name, $instructor);
    }

    echo "END";

    function addCourse($term_year, $course_num, $course_name, $instructor){
        
        $pdo = new PDO("sqlite:" . "DB/Main.db");
        $maxcourseid = $pdo->query("SELECT MAX(courseid) FROM Course");
        $newcourseid = $maxcourseid->fetchColumn() + 1;
    
        $query = $pdo->prepare("INSERT INTO Course (courseid, term_year, course_num, course_name, instructor_assigned_name) VALUES (?,?,?,?,?)");
        $err1 = $query->execute(array($newcourseid, $term_year, $course_num, $course_name, $instructor));
        
        $pdo = null;
        return ($err1 == 1);
    
    }
   
?>