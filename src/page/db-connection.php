<?php
  $con = mysqli_connect("localhost", "root", "", "ncd_management_system");
  if($con == false){
    die("Connection Error". mysqli_connect_error());
  }
?>