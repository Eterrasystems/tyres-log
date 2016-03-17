<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['warehouse_id'])) {
    $warehouse_id = $_POST['warehouse_id'];
  }
  if(isset($_POST['warehouse_name'])) {
    $warehouse_name = mysqli_real_escape_string($db_link,$_POST['warehouse_name']);
  }
  if(isset($_POST['warehouse_address'])) {
    $warehouse_address = mysqli_real_escape_string($db_link,$_POST['warehouse_address']);
  }
  if(isset($_POST['warehouse_phone'])) {
    $warehouse_phone = $_POST['warehouse_phone'];
  }
  if(isset($_POST['warehouse_info'])) {
    $warehouse_info = prepare_for_null_row(mysqli_real_escape_string($db_link,$_POST['warehouse_info']));
  }
  
  if(!empty($warehouse_id) && !empty($warehouse_name)) {
    
    $query = "UPDATE `warehouses` SET 
                          `warehouse_name` = '$warehouse_name',
                          `warehouse_address` = '$warehouse_address',
                          `warehouse_phone` = '$warehouse_phone',
                          `warehouse_info` = $warehouse_info
                        WHERE `warehouse_id` = '$warehouse_id'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo mysqli_error($db_link);
    }
    else { 
      
    }
  }
  DB_CloseI($db_link);