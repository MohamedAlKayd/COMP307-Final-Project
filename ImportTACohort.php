<?php
    $content = $_POST['con'];

    $ar = explode("\n",$content); 

    foreach($ar as $line){
        $ar2 = explode(",",$line);
        $term_year = $ar[0];
        $TA_name = $ar2[1];
        $student_ID = $ar2[2];
        $legal_name = $ar2[3];
        $email = $ar2[4];
        $grad_ugrad = $ar2[5];
        $supervisor_name = $ar2[6];
        $priority = $ar2[7];
        $hours = $ar2[8];
        $date_applied = $ar2[9];
        $location = $ar2[10];
        $phone = $ar2[11];
        $degree = $ar2[12];
        $courses_applied_for = $ar2[13];
        $open_to_other_courses = $ar2[14];
        $notes = $ar2[15];
        if(!addToTACohortInfo($term_year,$TA_name,$student_ID,$legal_name,$email,$grad_ugrad,$supervisor_name,$priority,$hours,$date_applied,$location,$phone,$degree,$courses_applied_for,$open_to_other_courses,$notes)){
            echo "error";
        }
    }

    echo "Added";

    function addToTACohortInfo($term_year,$TA_name,$student_ID,$legal_name,$email,$grad_ugrad,$supervisor_name,$priority,$hours,$date_applied,$location,$phone,$degree,$courses_applied_for,$open_to_other_courses,$notes){
        $namearray = explode(" ",$TA_name);
        $firstname = $namearray[0];
        $lastname = $namearray[1];
        $taid= addUserTA($firstname, $lastname, $email);
        
        $pdo = new PDO("sqlite:" . "DB/Main.db");
    
        $query = $pdo->prepare("INSERT INTO TACohortInfo (taid,term_year,TA_name,student_ID,legal_name,email,grad_ugrad,supervisor_name,priority,hours,date_applied,location,phone,degree, courses_applied_for, open_to_other_courses, notes) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $err1 = $query->execute(array($taid,$term_year,$TA_name,$student_ID,$legal_name,$email,$grad_ugrad,$supervisor_name,$priority,$hours,$date_applied,$location,$phone,$degree,$courses_applied_for,$open_to_other_courses,$notes));
    
        return $err1 == 1;
    }
    
    function addUserTA($firstname, $lastname, $email){
        $pdo = new PDO("sqlite:" . "DB/Main.db");
        $maxuserid = $pdo->query("SELECT MAX(userid) FROM User");
        $newuserid = $maxuserid->fetchColumn() + 1;
    
        $maxUid = $pdo->query("SELECT MAX(taid) FROM TA");
        $newUid = $maxUid->fetchColumn() + 1;
        $query = $pdo->prepare("INSERT INTO TA (taid, userid, firstname, lastName, email) VALUES (?,?,?,?,?)");
    
        $userquery = $pdo->prepare("INSERT INTO User (userid, username) VALUES (?,?)");
        $err1 = $userquery->execute(array($newuserid, $email));
    
        
        $err2 = $query->execute(array($newUid, $newuserid, $firstname, $lastname, $email));
        
        $pdo = null;
        return $newUid;
    
    }
   
?>