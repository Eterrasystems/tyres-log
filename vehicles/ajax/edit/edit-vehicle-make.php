<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['vehicle_make_id'])) {
    $vehicle_make_id = $_POST['vehicle_make_id'];
  }
  if(isset($_POST['vehicle_make'])) {
    $vehicle_make = mysqli_real_escape_string($db_link,$_POST['vehicle_make']);
  }
  
  if(!empty($vehicle_make_id) && !empty($vehicle_make)) {
    
    $query = "UPDATE `vehicles_makes` SET `vehicle_make` = '$vehicle_make' WHERE `vehicle_make_id` = '$vehicle_make_id'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo mysqli_error($db_link);
    }
    else { 
      
    }
  }
  
  DB_CloseI($db_link);