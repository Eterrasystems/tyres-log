<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_storage_id'])) {
    $tyre_storage_id = $_POST['tyre_storage_id'];
  }
  if(isset($_POST['warehouse_id'])) {
    $warehouse_id = $_POST['warehouse_id'];
  }
  if(isset($_POST['warehouse_type_name_from'])) {
    $warehouse_type_name_from = $_POST['warehouse_type_name_from'];
  }
  if(isset($_POST['warehouse_name_from'])) {
    $warehouse_name_from = $_POST['warehouse_name_from'];
  }
  if(isset($_POST['warehouse_name_to'])) {
    $warehouse_name_to = $_POST['warehouse_name_to'];
  }
  
  if(!empty($warehouse_id) && !empty($tyre_storage_id)) {
    
    $query = "UPDATE `tyres_storages` SET `warehouse_id` = '$warehouse_id' WHERE `tyre_storage_id` = '$tyre_storage_id'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo $laguages[$default_lang]['sql_error_update'];
      echo mysqli_error($db_link);
    }
    else echo $laguages[$default_lang]['reception_protocol_thead'].": $tyre_storage_id ".$laguages[$default_lang]['protocol_was_transfered_from']." \"$warehouse_type_name_from $warehouse_name_from\" ".$laguages[$default_lang]['protocol_was_transfered_to']." \"$warehouse_name_to\"";
  }
  DB_CloseI($db_link);