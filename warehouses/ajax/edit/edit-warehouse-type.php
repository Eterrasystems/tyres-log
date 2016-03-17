<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['warehouse_type_id'])) {
    $warehouse_type_id = $_POST['warehouse_type_id'];
  }
  if(isset($_POST['warehouse_type_name'])) {
    $warehouse_type_name = mysqli_real_escape_string($db_link,$_POST['warehouse_type_name']);
  }
  
  if(!empty($warehouse_type_id) && !empty($warehouse_type_name)) {
    
    $query = "UPDATE `warehouses_types` SET 
                          `warehouse_type_name` = '$warehouse_type_name'
                        WHERE `warehouse_type_id` = '$warehouse_type_id'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo mysqli_error($db_link);
    }
    else { 
      
    }
  }
  DB_CloseI($db_link);