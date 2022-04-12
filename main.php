<?php

	
if(sizeof($_GET)==0){
	header("Location: index.html");
  	exit();
}
else{
	$page = $_GET["Page"];
	displayPage("verifyUserAndRedirect.txt", $page);
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