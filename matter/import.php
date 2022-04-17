<?php  
 if(!empty($_FILES["employee_file"]["name"]))  
 {  
      $connect = mysqli_connect("localhost", "root", "", "testing");  
      $output = '';  
      $allowed_ext = array("csv");  
      $extension = end(explode(".", $_FILES["employee_file"]["name"]));  
      if(in_array($extension, $allowed_ext))  
      {  
           $file_data = fopen($_FILES["employee_file"]["tmp_name"], 'r');  
           fgetcsv($file_data);  
           while($row = fgetcsv($file_data))  
           {  
                $name = mysqli_real_escape_string($connect, $row[0]);  
                $address = mysqli_real_escape_string($connect, $row[1]);  
                $gender = mysqli_real_escape_string($connect, $row[2]);  
                $designation = mysqli_real_escape_string($connect, $row[3]);  
                $age = mysqli_real_escape_string($connect, $row[4]);  
                $query = "  
                INSERT INTO tbl_employee  
                     (name, address, gender, designation, age)  
                     VALUES ('$name', '$address', '$gender', '$designation', '$age')  
                ";  
                mysqli_query($connect, $query);  
           }  
           $select = "SELECT * FROM tbl_employee ORDER BY id DESC";  
           $result = mysqli_query($connect, $select);  
           $output .= '  
                <table class="table table-bordered">  
                     <tr>  
                          <th width="5%">ID</th>  
                          <th width="25%">Name</th>  
                          <th width="35%">Address</th>  
                          <th width="10%">Gender</th>  
                          <th width="20%">Designation</th>  
                          <th width="5%">Age</th>  
                     </tr>  
           ';  
           while($row = mysqli_fetch_array($result))  
           {  
                $output .= '  
                     <tr>  
                          <td>'.$row["id"].'</td>  
                          <td>'.$row["name"].'</td>  
                          <td>'.$row["address"].'</td>  
                          <td>'.$row["gender"].'</td>  
                          <td>'.$row["designation"].'</td>  
                          <td>'.$row["age"].'</td>  
                     </tr>  
                ';  
           }  
           $output .= '</table>';  
           echo $output;  
      }  
      else  
      {  
           echo 'Error1';  
      }  
 }  
 else  
 {  
      echo "Error2";  
 }  
 ?>  