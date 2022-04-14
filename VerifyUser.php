<?php
$userid = $_GET["Userid"];
$USERTYPE = getUserType($userid);
echo $USERTYPE;

function getUserType($userid){
  $pdo = new PDO("sqlite:" . "DB/Main.db");

  $query = $pdo->prepare("SELECT usertype FROM UserInfo WHERE userid == ?");

  $query->execute(array($userid));

	$pdo = null;
  return $query->fetch()[0];
}

?>