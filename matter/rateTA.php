<?php
/*Variables used to rate a TA*/
$course = '';
$term = '';
$TA = '';
$rating = '';
$comments = '';

/*If the submit button is clicked*/
if(isset($_POST["submit"])){
  /*Store the input into the variables*/
  $course = $_POST["course"];
  $term = $_POST["term"];
  $TA = $_POST["TA"];
  $phone = $_POST["rating"];
  $comments = $_POST["comments"];
  /*Open the CSV file*/
  $file_open = fopen("rateTA.csv", "a");
  $no_rows = count(file("rateTA.csv"));
  
  if($no_rows > 1)
  {
   $no_rows = ($no_rows - 1) + 1;
  }
  /*Append the new TA rate*/
    fwrite($file_open, $course);
    fwrite($file_open, ", ");
    fwrite($file_open, $term);
    fwrite($file_open, ", ");
    fwrite($file_open, $TA);
    fwrite($file_open, ", ");
    fwrite($file_open, $rating);
    fwrite($file_open, ", ");
    fwrite($file_open, $comments);
    fwrite($file_open, "\r\n");
  /*Reset the variables*/
    $course = '';
    $term = '';
    $TA = '';
    $rating = '';
    $comments = '';
  /*Close the file*/
  fclose($file_open);
}
?>