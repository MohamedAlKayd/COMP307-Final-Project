<?php
    $content = $_POST['con'];
    
    $ar = explode("\n",$content); 

    foreach($ar as $line){
        $ar2 = explode(",",$line);
        $firstname = $ar2[0];
        $lastname = $ar2[1];
        $username = $ar2[2];
        $email = $ar2[3];
        addProf($firstname, $lastname, $username, $email);
    }

    echo "END";

    function addProf($firstname, $lastname, $username, $email){
    
        $pdo = new PDO("sqlite:" . "DB/Main.db");
        $maxprofid = $pdo->query("SELECT MAX(proffesorid) FROM Prof");
        $newprofid = $maxprofid->fetchColumn() + 1;
        $maxuserid = $pdo->query("SELECT MAX(userid) FROM User");
        $newuserid = $maxuserid->fetchColumn() + 1;
    
        $userquery = $pdo->prepare("INSERT INTO User (userid, username) VALUES (?,?)");
        $err1 = $userquery->execute(array($newuserid, $username));
        
        $query = $pdo->prepare("INSERT INTO Prof (proffesorid, userid, firstname, lastName, email) VALUES (?,?,?,?,?)");
        $err2 = $query->execute(array($newprofid, $newuserid, $firstname, $lastname, $email));
        
        $pdo = null;
        return ($err1 == 1) and ($err1 == 1);
    
    }
?>
