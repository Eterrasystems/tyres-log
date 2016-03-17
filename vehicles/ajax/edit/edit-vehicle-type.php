<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['vehicle_type_id'])) {
    $vehicle_type_id = $_POST['vehicle_type_id'];
  }
  if(isset($_POST['vehicle_type'])) {
    $vehicle_type = mysqli_real_escape_string($db_link,$_POST['vehicle_type']);
  }
  if(isset($_POST['vehicle_image_id'])) {
    $vehicle_image_id = mysqli_real_escape_string($db_link,$_POST['vehicle_image_id']);
  }
  
  if(!empty($vehicle_type_id) && !empty($vehicle_type)) {
    
    $query = "UPDATE `vehicles_types` SET `vehicle_type` = '$vehicle_type', `vehicle_image_id` = '$vehicle_image_id' WHERE `vehicle_type_id` = '$vehicle_type_id'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo mysqli_error($db_link);
    }
    else { 
      
    }
  }
  
  DB_CloseI($db_link);