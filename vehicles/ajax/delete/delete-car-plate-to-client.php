<?php
  
  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['vptc_id'])) {
    $vptc_id = $_POST['vptc_id'];
  }
  
  $query = "DELETE FROM `vehicles_plates_to_clients` WHERE `vptc_id` = '$vptc_id'";
  $result = mysqli_query($db_link, $query);
  if(!$result) {
    echo mysqli_error($db_link);
  }
  
  DB_CloseI($db_link);
?>
