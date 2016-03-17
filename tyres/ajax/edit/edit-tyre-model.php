<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_model_id'])) {
    $tyre_model_id = $_POST['tyre_model_id'];
  }
  if(isset($_POST['tyre_model'])) {
    $tyre_model = mysqli_real_escape_string($db_link,$_POST['tyre_model']);
  }
  
  if(!empty($tyre_model_id) && !empty($tyre_model)) {
    
    $query = "UPDATE `tyres_models` SET `tyre_model` = '$tyre_model' WHERE `tyre_model_id` = '$tyre_model_id'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo mysqli_error($db_link);
    }
    else { 
      
    }
  }
  
  DB_CloseI($db_link);