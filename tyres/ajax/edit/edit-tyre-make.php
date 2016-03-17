<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_make_id'])) {
    $tyre_make_id = $_POST['tyre_make_id'];
  }
  if(isset($_POST['tyre_make'])) {
    $tyre_make = mysqli_real_escape_string($db_link,$_POST['tyre_make']);
  }
  
  if(!empty($tyre_make_id) && !empty($tyre_make)) {
    
    $query = "UPDATE `tyres_makes` SET `tyre_make` = '$tyre_make' WHERE `tyre_make_id` = '$tyre_make_id'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo mysqli_error($db_link);
    }
    else { 
      
    }
  }
  
  DB_CloseI($db_link);