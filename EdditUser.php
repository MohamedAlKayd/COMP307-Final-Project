<?php
$userid = $_GET["Userid"];
$User = getUser($userid);

$usertype = $User['usertype'];
$fname = $User['firstname'];
$lname = $User['lastname'];
$username = $User['username'];
$email = $User['email'];

if(!empty($_POST['fname'])){
    $fname = $_POST['fname'];
}
if(!empty($_POST['lname'])){
    $lname = $_POST['lname'];
}
if(!empty($_POST['username'])){
    $username = $_POST['username'];
}
if(!empty($_POST['email'])){
    $email = $_POST['email'];
}

$worked = true;


if($usertype == "Student"){
    if(!empty($_POST['studentid'])){
        $studentid = $_POST['studentid'];
    }
    else{
        $studentid = getStudentId($userid);
    }
    $worked = $worked && EdditStudent($userid,$studentid,$fname,$lname,$username,$email);
}
else{
    $worked = $worked && EdditUser($userid,$fname,$lname,$username,$email, $usertype);
}

if($worked){
    header("Location: main.php?Page=Sysop&Alert=the ".$usertype." was Edited");
}
else{
    header("Location: main.php?Page=Sysop&Alert=EDIT USER FAILED");
}


function EdditUser($userid,$fname,$lname,$username,$email, $usertype){
    $pdo = new PDO("sqlite:" . "DB/Main.db");
	$query = $pdo->prepare(
        "Update ".$usertype." 
        SET firstname = ?,
            lastname = ?,
            email = ?
	    Where userid == ?"
	);
 
    $query2 = $pdo->prepare(
        "Update User 
        SET username = ?
	    Where userid == ?"
	);

	$err1 = $query->execute(array($fname,$lname,$email,$userid));

    $err2 = $query2->execute(array($username,$userid));

	$pdo = null;
    return ($err1 == 1) and ($err2 == 1);
}

function getStudentId($userid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT studentid 
	FROM Student
	Where userid == ?"
	);

	$query->execute(array($userid));

	$pdo = null;
	return $query->fetch()[0];
}


function EdditStudent($userid,$studentid,$fname,$lname,$username,$email){
    $pdo = new PDO("sqlite:" . "DB/Main.db");
	$query = $pdo->prepare(
        "Update Student 
        SET studentid = ?,
            firstname = ?,
            lastname = ?,
            email = ?
	    Where userid == ?"
	);
 
    $query2 = $pdo->prepare(
        "Update User 
        SET username = ?
	    Where userid == ?"
	);

	$err1 = $query->execute(array($studentid,$fname,$lname,$email,$userid));

    $err2 = $query2->execute(array($username,$userid));

	$pdo = null;
    return ($err1 == 1) and ($err2 == 1);
}



function getUser($userid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT userid,firstname,lastname,username,email,usertype 
	FROM UserInfo
	Where userid == ?"
	);

	$query->execute(array($userid));

	$pdo = null;
	return $query->fetch();
}
?>