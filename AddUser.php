<?php

    $usertype = $_GET['Usertype'];

    $FNAME = $_POST["fname"];
	$LNAME = $_POST["lname"];
    $USERNAME = $_POST["uname"];
    $EMAIL = $_POST["email"];
    if($usertype == "Student"){
        $STID = $_POST["stdid"];
        if(addStudent($STID,$FNAME,$LNAME,$USERNAME,$EMAIL)){
            header("Location: main.php?Page=Sysop&Alert=The ".$usertype." was added");
        }
        else{
            header("Location: main.php?Page=Sysop&Alert=Error the ".$usertype." was not added");
        }
    }
    else{
        if(addUser($usertype,$FNAME,$LNAME,$USERNAME,$EMAIL)){
            header("Location: main.php?Page=Sysop&Alert=The ".$usertype." was added");
        }
        else {
            header("Location: main.php?Page=Sysop&Alert=Error the ".$usertype." was not added");
        }
    }

    function addStudent($studentid,$firstname, $lastname, $username, $email){
        
        $pdo = new PDO("sqlite:" . "DB/Main.db");
        $maxuserid = $pdo->query("SELECT MAX(userid) FROM User");
        $newuserid = $maxuserid->fetchColumn() + 1;
    
        $userquery = $pdo->prepare("INSERT INTO User (userid, username) VALUES (?,?)");
        $err1 = $userquery->execute(array($newuserid, $username));
    
        $query = $pdo->prepare("INSERT INTO Student (studentid, userid, firstname, lastName, email) VALUES (?,?,?,?,?)");
        $err2 = $query->execute(array($studentid, $newuserid, $firstname, $lastname, $email));
        
        $pdo = null;
        return ($err1 == 1) and ($err1 == 1);
    
    }

    function addUser($usertype,$firstname, $lastname, $username, $email){
        
        $pdo = new PDO("sqlite:" . "DB/Main.db");
        $maxuserid = $pdo->query("SELECT MAX(userid) FROM User");
        $newuserid = $maxuserid->fetchColumn() + 1;

        $maxUid = $pdo->query("SELECT MAX(proffesorid) FROM Prof");
	    $newUid = $maxUid->fetchColumn() + 1;
        $query = $pdo->prepare("INSERT INTO Prof (proffesorid, userid, firstname, lastName, email) VALUES (?,?,?,?,?)");

        if($usertype == "Prof"){
            $maxUid = $pdo->query("SELECT MAX(proffesorid) FROM Prof");
	        $newUid = $maxUid->fetchColumn() + 1;
            $query = $pdo->prepare("INSERT INTO Prof (proffesorid, userid, firstname, lastName, email) VALUES (?,?,?,?,?)");
        }
        else if($usertype == "TA"){
            $maxUid = $pdo->query("SELECT MAX(taid) FROM TA");
	        $newUid = $maxUid->fetchColumn() + 1;
            $query = $pdo->prepare("INSERT INTO TA (taid, userid, firstname, lastName, email) VALUES (?,?,?,?,?)");
        }
        else if($usertype == "Admin"){
            $maxUid = $pdo->query("SELECT MAX(adminid) FROM Admin");
	        $newUid = $maxUid->fetchColumn() + 1;
            $query = $pdo->prepare("INSERT INTO Admin (adminid, userid, firstname, lastName, email) VALUES (?,?,?,?,?)");
        }
        else{
            $maxUid = $pdo->query("SELECT MAX(sysopid) FROM Sysop");
	        $newUid = $maxUid->fetchColumn() + 1;
            $query = $pdo->prepare("INSERT INTO Sysop (sysopid, userid, firstname, lastName, email) VALUES (?,?,?,?,?)");
        }

        $userquery = $pdo->prepare("INSERT INTO User (userid, username) VALUES (?,?)");
        $err1 = $userquery->execute(array($newuserid, $username));
    
        
        $err2 = $query->execute(array($newUid, $newuserid, $firstname, $lastname, $email));
        
        $pdo = null;
        return ($err1 == 1) and ($err1 == 1);
    
    }
?>