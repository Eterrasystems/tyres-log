<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['vehicle_type_id'])) {
    $selected_vehicle_type_id = $_POST['vehicle_type_id'];
  }

  $query = "SELECT `vehicles_makes`.* 
            FROM `vehicles_makes` 
            WHERE `vehicle_make_id` IN(SELECT `vehicle_make_id` FROM `vehicles_makes_to_type` WHERE `vehicle_type_id` = '$selected_vehicle_type_id')
            ORDER BY `vehicle_make` ASC";
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    
    echo "<option value='0' selected='selected'>".$laguages[$default_lang]['choose_make']."</option>";
    while($vehicles_makes = mysqli_fetch_assoc($result)) {
      
      $vehicle_make_id = $vehicles_makes['vehicle_make_id'];
      $vehicle_make = $vehicles_makes['vehicle_make'];
      
      echo "<option value='$vehicle_make_id'>$vehicle_make</option>";
    }
  }
  else {   
?>
    <option selected="selected"><?=$laguages[$default_lang]['no_makes_yet'];?></option>
<?php    
  }
  
  DB_CloseI($db_link);