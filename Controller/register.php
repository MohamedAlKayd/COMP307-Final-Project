<?php
$FNAME = " ";
$LNAME = " ";
$USERNAME = " ";
$EMAIL = " ";
$PWD = " ";
$USERTYPE = $_GET['UserType'];



// REGISTER
if(isset($_POST["signup"])){
	$FNAME = $_POST["fname"];
	$LNAME = $_POST["lname"];
    $USERNAME = $_POST["uname"];
    $EMAIL = $_POST["email"];
    $PWD = $_POST["passwd"];
    $STID = $_POST["stdid"];
}

if(isset($_POST["signup"])){
	$USERNAME = $_POST["uname"];
	$PWD = $_POST["passwd"];
	if($USERTYPE == "Student"){
		$FNAME = $_POST["fname"];
		$LNAME = $_POST["lname"];
    	$EMAIL = $_POST["email"];
    	$STID = $_POST["stdid"];
		if(registerStudent($USERNAME, $PWD, $FNAME, $LNAME,$EMAIL, $STID)){
			echo "Registration works" ;   
		}
		sendEmail();
	}
	else{
		if(EdditUserpsw($USERNAME,$USERTYPE,$PWD)){
			echo "User Registration works" ;
			echo "<input type='button' onclick=\"location.href='../index.html'\" value='Go To Login'></input>";
		}
	}
}
else{
    echo "There's Something wrong! ";
}

function EdditUserpsw($username,$usertype,$password){
    $pdo = new PDO("sqlite:" . "../DB/Main.db");
	$query = $pdo->prepare(
        "Select userid
		From UserInfo
		Where username == ? and usertype == ? and password IS NULL"
	);
 
	$err1 = $query->execute(array($username,$usertype));
	if($err1 != 1){
		return FALSE;
	}
	
	$userid = $query->fetch()[0];
	if(empty($userid)){
		
		return FALSE;
	}
	
    $query2 = $pdo->prepare(
        "Update User 
        SET password = ?
	    Where userid == ?"
	);

    $err2 = $query2->execute(array($password,$userid));
	
	$pdo = null;
    return ($err2 == 1);
}

function getStudentId($userid){
	$pdo = new PDO("sqlite:" . "../DB/Main.db");

	$query = $pdo->prepare("SELECT studentid 
	FROM Student
	Where userid == ?"
	);

	$query->execute(array($userid));

	$pdo = null;
	return $query->fetch()[0];
}

function sendEmail(){
	$to = $EMAIL; // this is your Email address
    $from = "atia.islam@mail.mcgill.ca"; // this is the sender's Email address
    $subject = "Welcome to TA Management Website";
    $subject2 = "Copy of your form submission";
    $message = $FNAME . " " . $LNAME . " wrote the following:" . "\n\n" . "Welcome to the website";
    $message2 = "Here is a copy of your message " . $FNAME . "\n\n" . "You will be provided with a link to dashboard shortly";

    $headers = "From:" . $from;
    $headers2 = "From:" . $to;
    mail($to,$subject,$message,$headers);
    mail($from,$subject2,$message2,$headers2); // sends a copy of the message to the sender
    echo "Mail Sent. Thank you " . $FNAME . ", we will contact you shortly.<br>"; 
    echo "<input type='button' onclick=\"location.href='../index.html'\" value='Go To Login'></input>";
}

function userExists($username,$password,$USERTYPE) {
	$pdo = new PDO("sqlite:" . "../DB/Main.db");

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
	$pdo = new PDO("sqlite:" . "../DB/Main.db");
	
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

	$pdo = new PDO("sqlite:" . "../DB/Main.db");
	
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