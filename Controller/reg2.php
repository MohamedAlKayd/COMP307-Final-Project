<?php
//export SMTP_USERNAME=socsusername
//export SMTP_PASSWORD=socspassword

require '/usr/share/php/libphp-phpmailer/PHPMailerAutoload.php';
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
    if(registerStudent($USERNAME, $PWD, $FNAME, $LNAME,$EMAIL, $STID)){
        echo "Registration works" ;   
    }
	
	
    $mail = new PHPMailer(false); // Passing `true` enables exceptions

    //Server settings
    $mail->SMTPDebug = 1;//Enable verbose debug output
    $mail->isSMTP();//Set mailer to use SMTP
    $mail->Host = 'mail.cs.mcgill.ca';//Specify main and backup SMTP servers
    $mail->SMTPAuth = true;//Enable SMTP authentication
    $mail->Username = getenv('SMTP_USERNAME');//SMTP username
    $mail->Password = getenv('SMTP_PASSWORD');//SMTP password
    $mail->SMTPSecure = 'tls';//Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;//TCP port to connect to


    //Recipients
    $mail->setFrom('atia.islam@mail.mcgill.ca');
	$mail->addAddress('atia.islam@mail.mcgill.ca');//Add a recipient
	$mail->addAddress('mohamed.mahmoud@mail.mcgill.ca');//Add a recipient
	$mail->addAddress('eldrickdoo@gmail.com');//Add a recipient
	$mail->addAddress($EMAIL);


    //Content
    $mail->isHTML(true);//Set email format to HTML
    $mail->Subject = 'Confirmation email';

    $mail->Body = 'Thank you for your registration to TA Management Website. You can login with your credentials';


    $mail->send();
    echo "Mail Sent. Thank you " . $FNAME . ", we will contact you shortly."; 
}
else{
    echo "There's Something wrong! ";
    echo "Please check your student ID. ";
    echo "Your record does not exist in our database. ";
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
