<?php
    echo "<text> CoHORT </text>";
    $content = $_POST['con'];

    $ar = explode("\n",$content); 

    foreach($ar as $line){
        $ar2 = explode(",",$line);
        $TA_name = $ar2[0];
        $student_ID = $ar2[1];
        $legal_name = $ar2[2];
        $email = $ar2[3];
        $grad_ugrad = $ar2[4];
        $supervisor_name = $ar2[5];
        $priority = $ar2[6];
        $hours = $ar2[7];
        $date_applied = $ar2[8];
        $location = $ar2[9];
        $phone = $ar2[10];
        $degree = $ar2[11];
        $courses_applied_for = $ar2[12];
        $open_to_other_courses = $ar2[13];
        $notes = $ar2[14];
        addToTACohortInfo($TA_name,$student_ID,$legal_name,$email,$grad_ugrad,$supervisor_name,$priority,$hours,$date_applied,$location,$phone,$degree,$courses_applied_for,$open_to_other_courses,$notes);
    }

    echo "<text> END </text>";

    function addToTACohortInfo($TA_name,$student_ID,$legal_name,$email,$grad_ugrad,$supervisor_name,$priority,$hours,$date_applied,$location,$phone,$degree,$courses_applied_for,$open_to_other_courses,$notes){
        $namearray = explode(" ",$TA_name);
        $firstname = $namearray[0];
        $lastname = $namearray[1];
        $username = $email;
        $taid= addUserTA($firstname, $lastname, $username, $email);
        
        $pdo = new PDO("sqlite:" . "DB/Main.db");
    
        $query = $pdo->prepare("INSERT INTO TACohortInfo (taid,TA_name,student_ID,legal_name,email,grad_ugrad,supervisor_name,priority,hours,date_applied,location,phone,degree, courses_applied_for, open_to_other_courses, notes) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $err1 = $query->execute(array($taid,$TA_name,$student_ID,$legal_name,$email,$grad_ugrad,$supervisor_name,$priority,$hours,$date_applied,$location,$phone,$degree,$courses_applied_for,$open_to_other_courses,$notes));
    
        return $err1 == 1;
    }
    
    function addUserTA($firstname, $lastname, $username, $email){
            
        $pdo = new PDO("sqlite:" . "DB/Main.db");
        $maxuserid = $pdo->query("SELECT MAX(userid) FROM User");
        $newuserid = $maxuserid->fetchColumn() + 1;
    
        $maxUid = $pdo->query("SELECT MAX(taid) FROM TA");
        $newUid = $maxUid->fetchColumn() + 1;
        $query = $pdo->prepare("INSERT INTO TA (taid, userid, firstname, lastName, email) VALUES (?,?,?,?,?)");
    
        $userquery = $pdo->prepare("INSERT INTO User (userid, username) VALUES (?,?)");
        $err1 = $userquery->execute(array($newuserid, $username));
    
        
        $err2 = $query->execute(array($newUid, $newuserid, $firstname, $lastname, $email));
        
        $pdo = null;
        return $newUid;
    
    }
   
?>