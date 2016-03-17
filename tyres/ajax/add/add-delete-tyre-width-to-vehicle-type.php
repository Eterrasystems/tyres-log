<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['vehicle_type_id'])) {
    $vehicle_type_id = $_POST['vehicle_type_id'];
  }
  if(isset($_POST['tyre_width_id'])) {
    $tyre_width_id = $_POST['tyre_width_id'];
  }
  if(isset($_POST['tyre_width_checkbox'])) {
    $tyre_width_checkbox = $_POST['tyre_width_checkbox'];
  }
  if(!empty($vehicle_type_id) && !empty($tyre_width_id)) {
    
    if($tyre_width_checkbox == 0) {
      $query_select = "SELECT `twvt_id` FROM `tyres_width_to_vehicle_type` WHERE `vehicle_type_id` = '$vehicle_type_id' AND `tyre_width_id` = '$tyre_width_id'";
      $result_select = mysqli_query($db_link, $query_select);
      if(!$result_select) echo mysqli_error($db_link);
      if(mysqli_num_rows($result_select) > 0) {
        $row_id = mysqli_fetch_assoc($result_select);
        $twvt_id = $row_id['twvt_id'];
        
        $query_delete = "DELETE FROM `tyres_width_to_vehicle_type` WHERE `twvt_id` = '$twvt_id'";
        $result_delete = mysqli_query($db_link, $query_delete);
        if(!$result_delete) echo mysqli_error($db_link);
      }
    }
    else {
      $query_insert = "INSERT INTO `tyres_width_to_vehicle_type`(`twvt_id`, `vehicle_type_id`, `tyre_width_id`) 
                                              VALUES ('','$vehicle_type_id','$tyre_width_id')";
      //echo $query;exit;
      $result_insert = mysqli_query($db_link, $query_insert);
      if(!$result_insert) echo mysqli_error($db_link);
    }
      
  }
  
  DB_CloseI($db_link);