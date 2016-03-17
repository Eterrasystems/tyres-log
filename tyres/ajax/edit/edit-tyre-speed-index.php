<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_speed_index_id'])) {
    $tyre_speed_index_id = $_POST['tyre_speed_index_id'];
  }
  if(isset($_POST['tyre_speed_index'])) {
    $tyre_speed_index = mysqli_real_escape_string($db_link,$_POST['tyre_speed_index']);
  }
  if(isset($_POST['tyre_speed_index_order'])) {
    $tyre_speed_index_order = $_POST['tyre_speed_index_order'];
  }
  
  if(!empty($tyre_speed_index_id) && !empty($tyre_speed_index)) {
    
    $query = "UPDATE `tyres_speed_index` SET `tyre_speed_index` = '$tyre_speed_index', `tyre_speed_index_order` = '$tyre_speed_index_order' WHERE `tyre_speed_index_id` = '$tyre_speed_index_id'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo mysqli_error($db_link);
    }
    else { 
      
    }
  }
  
  DB_CloseI($db_link);