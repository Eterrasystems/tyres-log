<?php
  
  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['vehicle_make_id'])) {
    $vehicle_make_id = $_POST['vehicle_make_id'];
  }
  
  $query = "DELETE FROM `vehicles_makes` WHERE `vehicle_make_id` = '$vehicle_make_id'";
  $result = mysqli_query($db_link, $query);
  if(!$result) {
    echo mysqli_error($db_link);
  }
  
  DB_CloseI($db_link);
?>
