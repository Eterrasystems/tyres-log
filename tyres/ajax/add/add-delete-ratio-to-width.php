<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['tyre_width_id'])) {
    $tyre_width_id = $_POST['tyre_width_id'];
  }
  if(isset($_POST['tyre_ratio_id'])) {
    $tyre_ratio_id = $_POST['tyre_ratio_id'];
  }
  if(isset($_POST['tyre_ratio_checkbox'])) {
    $tyre_ratio_checkbox = $_POST['tyre_ratio_checkbox'];
  }
  if(!empty($tyre_width_id) && !empty($tyre_ratio_id)) {
    
    if($tyre_ratio_checkbox == 0) {
      $query_select = "SELECT `thtw_id` FROM `tyres_ratio_to_width` WHERE `tyre_width_id` = '$tyre_width_id' AND `tyre_ratio_id` = '$tyre_ratio_id'";
      $result_select = mysqli_query($db_link, $query_select);
      if(!$result_select) echo mysqli_error($db_link);
      if(mysqli_num_rows($result_select) > 0) {
        $row_id = mysqli_fetch_assoc($result_select);
        $thtw_id = $row_id['thtw_id'];
        
        $query_delete = "DELETE FROM `tyres_ratio_to_width` WHERE `thtw_id` = '$thtw_id'";
        $result_delete = mysqli_query($db_link, $query_delete);
        if(!$result_delete) echo mysqli_error($db_link);
      }
    }
    else {
      $query_insert = "INSERT INTO `tyres_ratio_to_width`(`thtw_id`, `tyre_width_id`, `tyre_ratio_id`) 
                                              VALUES ('','$tyre_width_id','$tyre_ratio_id')";
      //echo $query;exit;
      $result_insert = mysqli_query($db_link, $query_insert);
      if(!$result_insert) echo mysqli_error($db_link);
    }
      
  }
  
  DB_CloseI($db_link);