<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['tyre_ratio_id'])) {
    $tyre_ratio_id = $_POST['tyre_ratio_id'];
  }
  if(isset($_POST['tyre_diameter_id'])) {
    $tyre_diameter_id = $_POST['tyre_diameter_id'];
  }
  if(isset($_POST['tyre_diameter_checkbox'])) {
    $tyre_diameter_checkbox = $_POST['tyre_diameter_checkbox'];
  }
  if(!empty($tyre_ratio_id) && !empty($tyre_diameter_id)) {
    
    if($tyre_diameter_checkbox == 0) {
      $query_select = "SELECT `tdth_id` FROM `tyres_diameter_to_ratio` WHERE `tyre_ratio_id` = '$tyre_ratio_id' AND `tyre_diameter_id` = '$tyre_diameter_id'";
      $result_select = mysqli_query($db_link, $query_select);
      if(!$result_select) echo mysqli_error($db_link);
      if(mysqli_num_rows($result_select) > 0) {
        $row_id = mysqli_fetch_assoc($result_select);
        $tdth_id = $row_id['tdth_id'];
        
        $query_delete = "DELETE FROM `tyres_diameter_to_ratio` WHERE `tdth_id` = '$tdth_id'";
        $result_delete = mysqli_query($db_link, $query_delete);
        if(!$result_delete) echo mysqli_error($db_link);
      }
    }
    else {
      $query_insert = "INSERT INTO `tyres_diameter_to_ratio`(`tdth_id`, `tyre_diameter_id`, `tyre_ratio_id`) 
                                              VALUES ('','$tyre_diameter_id','$tyre_ratio_id')";
      //echo $query;exit;
      $result_insert = mysqli_query($db_link, $query_insert);
      if(!$result_insert) echo mysqli_error($db_link);
    }
      
  }
  
  DB_CloseI($db_link);