<?php
$servername = "localhost";//name of the server
$rootusername = "";
$password = "";//password of the root user
$database_name = "";
$table_name_company = "companyinfo";
$table_name_student = "studentinfo";
$table_name_project = "projects";
$table_name_studentProject = "student_project";
$table_name_feedstud="company_feedback";
$table_name_feedcomp="student_feedback";
// Create connection
$connect = mysqli_connect($servername,$rootusername,$password,$database_name);
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL :(";// . mysqli_connect_error();
  }
?>