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
        $firstname = $line[0];
        $lastname = $line[1];
        $username = $line[2];
        $email = $line[3];
        addProf($firstname, $lastname, $username, $email);
    }
    fclose($file);
    //unlink($target_file);
    echo "END";

    function addProf($firstname, $lastname, $username, $email){
    
        $pdo = new PDO("sqlite:" . "DB/Main.db");
        $maxprofid = $pdo->query("SELECT MAX(proffesorid) FROM Prof");
        $newprofid = $maxprofid->fetchColumn() + 1;
        $maxuserid = $pdo->query("SELECT MAX(userid) FROM User");
        $newuserid = $maxuserid->fetchColumn() + 1;
    
        $userquery = $pdo->prepare("INSERT INTO User (userid, username) VALUES (?,?)");
        $err1 = $userquery->execute(array($newuserid, $username));
        echo "ERR1".$err1;
        $query = $pdo->prepare("INSERT INTO Prof (proffesorid, userid, firstname, lastName, email) VALUES (?,?,?,?,?)");
        $err2 = $query->execute(array($newprofid, $newuserid, $firstname, $lastname, $email));
        echo "ERR2".$err2;
        $pdo = null;
        return ($err1 == 1) and ($err1 == 1);
    
    }
?>
