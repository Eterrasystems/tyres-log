<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['vehicle_type_id'])) {
    $vehicle_type_id = $_POST['vehicle_type_id'];
  }
  if(isset($_POST['vehicle_make_id'])) {
    $vehicle_make_id = $_POST['vehicle_make_id'];
  }
  if(isset($_POST['vehicle_make_checkbox'])) {
    $vehicle_make_checkbox = $_POST['vehicle_make_checkbox'];
  }
  if(!empty($vehicle_type_id) && !empty($vehicle_make_id)) {
    
    if($vehicle_make_checkbox == 0) {
      $query_select = "SELECT `vmtt_id` FROM `vehicles_makes_to_type` WHERE `vehicle_type_id` = '$vehicle_type_id' AND `vehicle_make_id` = '$vehicle_make_id'";
      $result_select = mysqli_query($db_link, $query_select);
      if(!$result_select) echo mysqli_error($db_link);
      if(mysqli_num_rows($result_select) > 0) {
        $row_id = mysqli_fetch_assoc($result_select);
        $vmtt_id = $row_id['vmtt_id'];
        
        $query_delete = "DELETE FROM `vehicles_makes_to_type` WHERE `vmtt_id` = '$vmtt_id'";
        $result_delete = mysqli_query($db_link, $query_delete);
        if(!$result_delete) echo mysqli_error($db_link);
      }
    }
    else {
      $query_insert = "INSERT INTO `vehicles_makes_to_type`(`vmtt_id`, `vehicle_type_id`, `vehicle_make_id`) 
                                              VALUES ('','$vehicle_type_id','$vehicle_make_id')";
      //echo $query;exit;
      $result_insert = mysqli_query($db_link, $query_insert);
      if(!$result_insert) echo mysqli_error($db_link);
    }
      
  }
  
  DB_CloseI($db_link);