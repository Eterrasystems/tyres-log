<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_ratio_id'])) {
    $tyre_ratio_id = $_POST['tyre_ratio_id'];
  }
  if(isset($_POST['tyre_ratio'])) {
    $tyre_ratio = mysqli_real_escape_string($db_link,$_POST['tyre_ratio']);
  }
  if(isset($_POST['tyre_ratio_order'])) {
    $tyre_ratio_order = $_POST['tyre_ratio_order'];
  }
  
  if(!empty($tyre_ratio_id) && !empty($tyre_ratio)) {
    
    $query = "UPDATE `tyres_ratio` SET `tyre_ratio` = '$tyre_ratio', `tyre_ratio_order` = '$tyre_ratio_order' WHERE `tyre_ratio_id` = '$tyre_ratio_id'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo mysqli_error($db_link);
    }
    else { 
      
    }
  }
  
  DB_CloseI($db_link);