<?php
    echo "<text> QUOTA </text>";
    $content = $_POST['con'];

    $ar = explode("\n",$content); 

    foreach($ar as $line){
        $ar2 = explode(",",$line);
        $term_year = $ar2[0];
        $course_num = $ar2[1];
        $course_type = $ar2[2];
        $course_name = $ar2[3];
        $instructor = $ar2[4];
        $course_enrollment_num = $ar2[5];
        $TA_quota = $ar2[6];
    
        addToCourseQuotaInfo($term_year,$course_num,$course_type,$course_name,$instructor,$course_enrollment_num,$TA_quota);
    }

    echo "<text> END </text>";

    function addToCourseQuotaInfo($term_year,$course_num,$course_type,$course_name,$instructor_name,$course_enrollment_num,$TA_quota){
        $pdo = new PDO("sqlite:" . "DB/Main.db");
        $courseid = addThisCourse($term_year, $course_num, $course_name, $instructor_name);
    
        $query = $pdo->prepare("INSERT INTO CourseQuotaInfo (courseid,term_year,course_num,course_type,course_name,instructor_name,course_enrollment_num,TA_quota) VALUES (?,?,?,?,?,?,?,?)");
        $err1 = $query->execute(array($courseid,$term_year,$course_num,$course_type,$course_name,$instructor_name,$course_enrollment_num,$TA_quota));
    
        return $err1 == 1;
    }

    function addThisCourse($term_year, $course_num, $course_name, $instructor_assigned_name){
        $pdo = new PDO("sqlite:" . "DB/Main.db");
        $maxCourseid = $pdo->query("SELECT MAX(courseid) FROM Course");
        $newCourseid = $maxCourseid->fetchColumn() + 1;
    
        $coursequery = $pdo->prepare("INSERT INTO Course (courseid, term_year, course_num, course_name, instructor_assigned_name) VALUES (?,?,?,?,?)");
        $err1 = $coursequery->execute(array($newCourseid, $term_year, $course_num, $course_name, $instructor_assigned_name));
    
        return $newCourseid;
    }
   
?>