<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_width_id'])) {
    $tyre_width_id = $_POST['tyre_width_id'];
  }
  if(isset($_POST['tyre_width'])) {
    $tyre_width = mysqli_real_escape_string($db_link,$_POST['tyre_width']);
  }
  if(isset($_POST['tyre_width_order'])) {
    $tyre_width_order = $_POST['tyre_width_order'];
  }
  
  if(!empty($tyre_width_id) && !empty($tyre_width)) {
    
    $query = "UPDATE `tyres_width` SET `tyre_width` = '$tyre_width', `tyre_width_order` = '$tyre_width_order' WHERE `tyre_width_id` = '$tyre_width_id'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo mysqli_error($db_link);
    }
    else { 
      
    }
  }
  
  DB_CloseI($db_link);