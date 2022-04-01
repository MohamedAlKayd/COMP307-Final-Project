<?php

// --------- COMMON WEBPAGE TOP ---------
echo "<html>"
echo "<head>"
echo "</head>"
echo "<body>"

displayActive("header.txt",$_GET["Page"]);

// --------- ROUTING WEBPAGE BODY -----------
if (sizeof($_GET)==0 || $_GET["Page"]=="DashBoard") {
	// DashBoard
	display("dashboard.txt");
} else if ($_GET["Page"]=="Administration") {
	// TA Administration
	display("ta_administration.txt");
} else if ($_GET["Page"]=="Management") {
	// TA Management
	display("ta_management.txt");
} else if ($_GET["Page"]=="Rate") {
	// Rate a TA
	display("rate_a_ta.txt");
} else if ($_GET["Page"]=="Sysop") {
	// Sysop Tasks
	display("sysop_task.txt");
} else {
	// ERROR PAGE
	echo "404: Invalid Page!";
}

// --------- COMMON WEBPAGE BOTTOM ----------
display("footer.txt");

echo "<body>"
echo "</html>"

function display($path) {
  $file = fopen($path,"r");
  while(!feof($file)) {
    $line = fgets($file);
    echo $line;
  }
  fclose($file);
}

function displayActive($path,$target) {
  $file = fopen($path,"r");
  if (sizeof($target)==0) {
    $target="Page=DashBoard";
  }
  else $target="Page=".$target;

  while(!feof($file)) {
    $line = fgets($file);
    if (strstr($line,$target)){
      $line=str_replace("class = \"inactive\"","class = \"active\"",$line);
    }
    else{
      $line=str_replace("class = \"active\"","class = \"inactive\"",$line);
    }
    echo $line;
  }
  fclose($file);
}

?>