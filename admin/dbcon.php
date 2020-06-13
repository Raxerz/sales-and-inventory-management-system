<?php
$con = mysqli_connect("<database-host-path:<port>","<username>","<password>","<database-name>");

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>
