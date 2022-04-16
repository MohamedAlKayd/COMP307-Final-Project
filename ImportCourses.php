<?php
    echo "Start<br>";
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    echo $target_file."<br>";
    echo $_FILES["file"]["error"]."<br>";    

    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

    $file = fopen($target_file, 'r');
    while (($line = fgetcsv($file)) !== FALSE) {
        echo "in<br>";
        $term_year = $line[0];
        $course_num = $line[1];
        $course_name = $line[2];
        $instructor = $line[3];
        addCourse($term_year, $course_num, $course_name, $instructor);
    }
    fclose($file);
    //unlink($target_file);
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