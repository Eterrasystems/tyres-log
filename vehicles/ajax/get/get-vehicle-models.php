<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['vehicle_type_id'])) {
    $vehicle_type_id = $_POST['vehicle_type_id'];
  }
  if(isset($_POST['vehicle_make_id'])) {
    $vehicle_make_id = $_POST['vehicle_make_id'];
  }
  
?>

  <table class="no_margin">
    <thead>
      <tr>
        <td width="7%"><?=$laguages[$default_lang]['btn_save'];?></td>
        <td width="30%"><?=$laguages[$default_lang]['vehicle_models_thead'];?></td>
        <td width="5%"><?=$laguages[$default_lang]['btn_delete']; ?></td>
        <td></td>
      </tr>
    </thead>
  </table>
  <div id="choose_vehicle_type">
<?php
  $query = "SELECT `vehicle_model_id`, `vehicle_model` 
            FROM `vehicles_models` 
            WHERE `vehicle_type_id` = '$vehicle_type_id' AND `vehicle_make_id` = '$vehicle_make_id'
            ORDER BY `vehicle_model` ASC";
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    $key = 0;
    while($vehicles_models = mysqli_fetch_assoc($result)) {

      $vehicle_model_id = $vehicles_models['vehicle_model_id'];
      $vehicle_model = $vehicles_models['vehicle_model'];
      $class = ((($key % 2) == 1) ? " even" : " odd");

?>
    <div id="vehicle_model<?php echo $vehicle_model_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditVehicleMоdel('<?php echo $vehicle_model_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="vehicle_model" value="<?php echo $vehicle_model;?>" /></td>
          <td width="5%" class="no_background"><button class="remove" onClick="DeleteVehicleMоdel('<?php echo $vehicle_model_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
      $key++;
    }
  }
  else {   
?>
    <div id="no_records"><?=$laguages[$default_lang]['no_vehicle_models_yet'];?></div>
<?php    
  }
  
  DB_CloseI($db_link);
?>
  </div>