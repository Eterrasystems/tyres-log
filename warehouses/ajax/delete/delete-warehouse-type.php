<?php
  
  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['warehouse_type_id'])) {
    $warehouse_type_id = $_POST['warehouse_type_id'];
  }
  
  $query = "DELETE FROM `warehouses_types` WHERE `warehouse_type_id` = '$warehouse_type_id'";
  $result = mysqli_query($db_link, $query);
  if(!$result) {
    echo mysqli_error($db_link);
  }
  
  DB_CloseI($db_link);
?>
