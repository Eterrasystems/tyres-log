<?php
  
  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_speed_index_id'])) {
    $tyre_speed_index_id = $_POST['tyre_speed_index_id'];
  }
  
  $query = "DELETE FROM `tyres_speed_index` WHERE `tyre_speed_index_id` = '$tyre_speed_index_id'";
  $result = mysqli_query($db_link, $query);
  if(!$result) {
    echo mysqli_error($db_link);
  }
  
  DB_CloseI($db_link);
?>
