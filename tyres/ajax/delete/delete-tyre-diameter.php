<?php
  
  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_diameter_id'])) {
    $tyre_diameter_id = $_POST['tyre_diameter_id'];
  }
  
  $query = "DELETE FROM `tyres_diameter` WHERE `tyre_diameter_id` = '$tyre_diameter_id'";
  $result = mysqli_query($db_link, $query);
  if(!$result) {
    echo mysqli_error($db_link);
  }
  
  DB_CloseI($db_link);
?>
