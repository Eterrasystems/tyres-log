<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['vehicle_type_id'])) {
    $selected_vehicle_type_id = $_POST['vehicle_type_id'];
  }
  else $selected_vehicle_type_id = 0;
  if(isset($_POST['vehicle_make_id'])) {
    $selected_vehicle_make_id = $_POST['vehicle_make_id'];
  }

  $where_vehicle_type = ($selected_vehicle_type_id == 0) ? "" : "`vehicle_type_id` = '$selected_vehicle_type_id' AND";
  $query = "SELECT `vehicles_models`.* 
            FROM `vehicles_models` 
            WHERE $where_vehicle_type `vehicle_make_id` = '$selected_vehicle_make_id'
            ORDER BY `vehicle_model` ASC";
  //echo $query;
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    
    echo "<option value='0'  selected='selected'>".$laguages[$default_lang]['choose_model']."</option>";
    while($vehicles_models = mysqli_fetch_assoc($result)) {
      
      $vehicle_model_id = $vehicles_models['vehicle_model_id'];
      $vehicle_model = $vehicles_models['vehicle_model'];
      
      echo "<option value='$vehicle_model_id'>$vehicle_model</option>";
    }
  }
  else {   
?>
    <option selected="selected" value="0"><?=$laguages[$default_lang]['no_vehicle_models_yet'];?></option>
<?php    
  }
  
  DB_CloseI($db_link);