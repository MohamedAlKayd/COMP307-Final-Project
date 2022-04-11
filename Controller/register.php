<?php
$FNAME = " ";
$LNAME = " ";
$USERNAME = " ";
$EMAIL = " ";
$PWD = " ";
$USERTYPE = "Student";
$clickedOnRegister = "False";


// REGISTER
if(isset($_POST["signup"])){
	$FNAME = $_POST["fname"];
	$LNAME = $_POST["lname"];
    $USERNAME = $_POST["uname"];
    $EMAIL = $_POST["email"];
    $PWD = $_POST["passwd"];
    $STID = $_POST["stdid"];
	$clickedOnRegister="True";
}

if($clickedOnRegister){
    if(registerStudent($USERNAME, $PWD, $FNAME, $LNAME, $STID)){
        echo "Registration works" ;   
    }
    echo "Resigtration Succesful!";
}
else{
    echo "There's Something wrong! ";
    echo "Please check your student ID. ";
    echo "Your record does not exist in our database. ";
}

function userExists($username,$password,$USERTYPE) {
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT COUNT(*) FROM User u, " . $USERTYPE . " t
		WHERE u.username == ? and u.password == ? and u.userid == t.userid");

	$query->execute(array($username,$password));	

	$row = $query->fetch();
	$count = $row[0];

	$pdo = null;
	return $count == 1;
	
}
//return true if a student with that studentid exists
function studentExists($studentid){
	$pdo = new PDO("sqlite:" . "DB/Main.db");
	
	$pre = $pdo->prepare("SELECT Count(*) FROM Student Where studentid == ?");
	$pre->execute(array($studentid));
	
	$row = $pre->fetch();
	$count = $row[0];
	
	$pdo = null;
	return $count == 1;
}
//used to register new student user
//returns true if the student was succesfully registered
function registerStudent($username,$password,$firstname,$lastname,$email,$studentid){
	if(userExists($username,$password,"Student") or studentExists($studentid)){
		return False;
	}

	$pdo = new PDO("sqlite:" . "DB/Main.db");
	
	$maxuserid = $pdo->query("SELECT MAX(userid) FROM User");
	$newuserid = $maxuserid->fetchColumn() + 1;
	
	$userquery = $pdo->prepare("INSERT INTO User (userid, username, password) VALUES (?,?,?)");
	$err1 = $userquery->execute(array($newuserid, $username, $password));
	$studentquery = $pdo->prepare("INSERT INTO Student (studentid, userid, firstname, lastName, email) VALUES (?,?,?,?,?)");
	$err2 = $studentquery->execute(array($studentid, $newuserid, $firstname, $lastname, $email));
	
	$pdo = null;
	return ($err1 == 1) and ($err1 == 1);
}

?>