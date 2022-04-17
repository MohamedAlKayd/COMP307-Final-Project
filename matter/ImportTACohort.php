<?php  
 $connect = mysqli_connect("localhost", "root", "", "testing");  
 $query = "SELECT * FROM tbl_employee ORDER BY id desc";  
 $result = mysqli_query($connect, $query);  
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>Import TA Cohort</title>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>  
      </head>  
      <body>  
           <br /><br />  
           <div class="container" style="width:900px;">  
                <h2 align="center">Import TA Cohort</h2>  
                <h3 align="center">TA Information</h3><br />  
                <form id="upload_csv" method="post" enctype="multipart/form-data">  
                     <div class="col-md-3">  
                          <br />  
                          <label>Import Data</label>  
                     </div>  
                     <div class="col-md-4">  
                          <input type="file" name="employee_file" style="margin-top:15px;" />  
                     </div>  
                     <div class="col-md-5">  
                          <input type="submit" name="upload" id="upload" value="Upload" style="margin-top:10px;" class="btn btn-info" />  
                     </div>  
                     <div style="clear:both"></div>  
                </form>  
                <br /><br /><br />  
                <div class="table-responsive" id="employee_table">  
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
                          <?php  
                          while($row = mysqli_fetch_array($result))  
                          {  
                          ?>  
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
                          <?php  
                          }  
                          ?>  
                     </table>  
                </div>  
           </div>  
      </body>  
 </html>  
 <script>  
      $(document).ready(function(){  
           $('#upload_csv').on("submit", function(e){  
                e.preventDefault(); //form will not submitted  
                $.ajax({  
                     url:"export.php",  
                     method:"POST",  
                     data:new FormData(this),  
                     contentType:false,          // The content type used when sending data to the server.  
                     cache:false,                // To unable request pages to be cached  
                     processData:false,          // To send DOMDocument or non processed data file it is set to false  
                     success: function(data){  
                          if(data=='Error1')  
                          {  
                               alert("Invalid File");  
                          }  
                          else if(data == "Error2")  
                          {  
                               alert("Please Select File");  
                          }  
                          else  
                          {  
                               $('#employee_table').html(data);  
                          }  
                     }  
                })  
           });  
      });  
 </script>  