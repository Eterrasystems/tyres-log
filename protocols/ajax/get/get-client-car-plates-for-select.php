<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
  }
  
  $query_vehicle_plates = "SELECT `vptc_id`,`vehicle_plate` FROM `vehicles_plates_to_clients` WHERE `user_id` = '$user_id'
                            ORDER BY `vehicle_plate` ASC";
  $result_vehicle_plates = mysqli_query($db_link, $query_vehicle_plates);
  if(mysqli_num_rows($result_vehicle_plates) > 0) {
    
    echo "<option value='0'>".$laguages[$default_lang]['choose_vehicle_plate']."</option>";
    
    while($vehicle_plates = mysqli_fetch_assoc($result_vehicle_plates)) {

      $vptc_id = $vehicle_plates['vptc_id'];
      $vehicle_plate = $vehicle_plates['vehicle_plate'];

      echo "<option value='$vehicle_plate'>$vehicle_plate</option>";
    }
  }
  else {   
    echo "no_plates";
  }
  
  DB_CloseI($db_link);
?>