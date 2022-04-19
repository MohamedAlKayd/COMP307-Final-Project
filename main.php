<?php

	
if(sizeof($_GET)==0|| $_GET["Page"] == "Logout"){
	header("Location: index.html");
  	exit();
}
else{
	$page = $_GET["Page"];
	if(isset($_GET["Alert"])){
		echo "<script>alert(\"".$_GET["Alert"]."\")</script>";
	}
	displayPage("verifyUserAndRedirect.txt", $page);
}

function display($path) {
  $file = fopen($path,"r");
  while(!feof($file)) {
    $line = fgets($file);
    echo $line;
  }
  fclose($file);
}

function displayPage($path, $page){
  	$file = fopen($path,"r");
  	while(!feof($file)) {
    		$line = fgets($file);
    		if (strstr($line,"PATHSTANDIN")){
      			$line=str_replace("PATHSTANDIN",$page,$line);
    		}
    		echo $line;
 	 	}
  	fclose($file);
}


?>