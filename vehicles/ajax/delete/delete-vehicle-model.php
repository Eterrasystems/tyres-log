<?php
  
  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['vehicle_model_id'])) {
    $vehicle_model_id = $_POST['vehicle_model_id'];
  }
  
  $query = "DELETE FROM `vehicles_models` WHERE `vehicle_model_id` = '$vehicle_model_id'";
  $result = mysqli_query($db_link, $query);
  if(!$result) {
    echo mysqli_error($db_link);
  }
  
  DB_CloseI($db_link);
?>
