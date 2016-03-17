<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_diameter_id'])) {
    $tyre_diameter_id = $_POST['tyre_diameter_id'];
  }
  if(isset($_POST['tyre_diameter'])) {
    $tyre_diameter = mysqli_real_escape_string($db_link,$_POST['tyre_diameter']);
  }
  if(isset($_POST['tyre_diameter_order'])) {
    $tyre_diameter_order = $_POST['tyre_diameter_order'];
  }
  
  if(!empty($tyre_diameter_id) && !empty($tyre_diameter)) {
    
    $query = "UPDATE `tyres_diameter` SET `tyre_diameter` = '$tyre_diameter', `tyre_diameter_order` = '$tyre_diameter_order' WHERE `tyre_diameter_id` = '$tyre_diameter_id'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo mysqli_error($db_link);
    }
    else { 
      
    }
  }
  
  DB_CloseI($db_link);