<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['vptc_id'])) {
    $vptc_id = $_POST['vptc_id'];
  }
  if(isset($_POST['vehicle_plate'])) {
    $vehicle_plate = mysqli_real_escape_string($db_link,$_POST['vehicle_plate']);
  }
  
  if(!empty($vptc_id) && !empty($vehicle_plate)) {
    
    $query = "UPDATE `vehicles_plates_to_clients` SET `vehicle_plate` = '$vehicle_plate' WHERE `vptc_id` = '$vptc_id'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo mysqli_error($db_link);
    }
    else { 
      
    }
  }
  
  DB_CloseI($db_link);