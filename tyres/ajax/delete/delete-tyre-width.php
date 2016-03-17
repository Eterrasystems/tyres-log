<?php
  
  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_width_id'])) {
    $tyre_width_id = $_POST['tyre_width_id'];
  }
  
  $query = "DELETE FROM `tyres_width` WHERE `tyre_width_id` = '$tyre_width_id'";
  $result = mysqli_query($db_link, $query);
  if(!$result) {
    echo mysqli_error($db_link);
  }
  
  DB_CloseI($db_link);
?>
