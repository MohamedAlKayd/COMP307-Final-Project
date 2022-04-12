<?php

// LOGIN

$USERNAME = $_POST["uname"];
$PWD = $_POST["passwd"];
$USERTYPE = $_POST["utype"];

if(userExists($USERNAME,$PWD,$USERTYPE)){
  $userid = getUserid($USERNAME, $PWD);
  displayMod("storeUserid.txt",$userid);
}
else{
  header("Location: index.html");
  exit();
}

echo "DONE";

function display($path) {
  $file = fopen($path,"r");
  while(!feof($file)) {
    $line = fgets($file);
    echo $line;
  }
  fclose($file);
}

function displayMod($path,$userid) {
  $file = fopen($path,"r");
  while(!feof($file)) {
    $line = fgets($file);
    if (strstr($line,"STANDINID")){
      $line=str_replace("STANDINID",$userid,$line);
    }
    echo $line;
  }
  fclose($file);
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

function getUserid($Username, $PWD){
	$pdo = new PDO("sqlite:" . "DB/Main.db");

	$query = $pdo->prepare("SELECT userid FROM User WHERE username == ? and password == ?");
	$query->execute(array($Username, $PWD));

	return $query->fetch()['userid'];
}

?>