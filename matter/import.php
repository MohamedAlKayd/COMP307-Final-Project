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
                    <th width="6.25%">term_month_year</th>  
                    <th width="6.25%">TA_name</th>  
                    <th width="6.25%">student_ID</th>  
                    <th width="6.25%">legal_name</th>  
                    <th width="6.25%">email</th>  
                    <th width="6.25%">grad_ugrad</th>  
                    <th width="6.25%">supervisor_name</th>  
                    <th width="6.25%">priority(yes/no)</th>  
                    <th width="6.25%">hours(90/180)</th>  
                    <th width="6.25%">date_applied</th>  
                    <th width="6.25%">location</th>  
                    <th width="6.25%">phone</th>  
                    <th width="6.25%">degree</th>  
                    <th width="6.25%">courses_applied_for</th>  
                    <th width="6.25%">open_to_other_courses(yes/no)</th>  
                    <th width="6.25%">notes</th> 
            </tr>   
           ';  
           while($row = mysqli_fetch_array($result))  
           {  
                $output .= '  
                     <tr>  
                        <td><?php echo $row["term_month_year"]; ?></td>  
                        <td><?php echo $row["TA_name"]; ?></td>  
                        <td><?php echo $row["student_ID"]; ?></td>  
                        <td><?php echo $row["legal_name"]; ?></td>  
                        <td><?php echo $row["email"]; ?></td>  
                        <td><?php echo $row["grad_ugrad"]; ?></td>  
                        <td><?php echo $row["supervisor_name"]; ?></td>  
                        <td><?php echo $row["priority(yes/no)"]; ?></td>  
                        <td><?php echo $row["hours(90/180)"]; ?></td>  
                        <td><?php echo $row["date_applied"]; ?></td>  
                        <td><?php echo $row["location"]; ?></td>  
                        <td><?php echo $row["phone"]; ?></td>  
                        <td><?php echo $row["degree"]; ?></td>  
                        <td><?php echo $row["courses_applied_for"]; ?></td>  
                        <td><?php echo $row["open_to_other_courses(yes/no)"]; ?></td>  
                        <td><?php echo $row["notes"]; ?></td>  
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