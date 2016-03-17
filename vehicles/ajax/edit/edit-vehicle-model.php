<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['vehicle_model_id'])) {
    $vehicle_model_id = $_POST['vehicle_model_id'];
  }
  if(isset($_POST['vehicle_model'])) {
    $vehicle_model = mysqli_real_escape_string($db_link,$_POST['vehicle_model']);
  }
  
  if(!empty($vehicle_model_id) && !empty($vehicle_model)) {
    
    $query = "UPDATE `vehicles_models` SET `vehicle_model` = '$vehicle_model' WHERE `vehicle_model_id` = '$vehicle_model_id'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo mysqli_error($db_link);
    }
    else { 
      
    }
  }
  
  DB_CloseI($db_link);