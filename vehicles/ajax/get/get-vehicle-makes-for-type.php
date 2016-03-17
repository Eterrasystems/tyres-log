<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['vehicle_type_id'])) {
    $selected_vehicle_type_id = $_POST['vehicle_type_id'];
  }
  
  $vehicles_makes_to_type_ids = array();
  $query = "SELECT `vehicle_make_id` FROM `vehicles_makes_to_type` WHERE `vehicle_type_id` = '$selected_vehicle_type_id'";
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      $vehicles_makes_to_type_ids[] = $row['vehicle_make_id'];
    }
  }
?>

  <table class="no_margin">
    <thead>
      <tr>
        <td width="7%"><?=$laguages[$default_lang]['btn_save'];?></td>
        <td width="30%"><?=$laguages[$default_lang]['vehicle_makes_thead'];?></td>
        <td width="10%"><?=$laguages[$default_lang]['btn_inlude'];?></td>
        <td></td>
      </tr>
    </thead>
  </table>
  <div id="choose_vehicle_type">
<?php
  $query = "SELECT `vehicles_makes`.* FROM `vehicles_makes` ORDER BY `vehicle_make` ASC";
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    $key = 0;
    while($vehicles_makes = mysqli_fetch_assoc($result)) {
      
      $vehicle_make_id = $vehicles_makes['vehicle_make_id'];
      $vehicle_make = $vehicles_makes['vehicle_make'];
      $class = ((($key % 2) == 1) ? " even" : " odd");
?>
    <div id="vehicle_make<?php echo $vehicle_make_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="AddDeleteMakeToType('<?php echo $vehicle_make_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="vehicle_make" value="<?php echo $vehicle_make;?>" /></td>
          <td width="10%">
            <input type="checkbox" class="vehicle_make_id" value="<?php echo $vehicle_make_id;?>" <?php if(in_array($vehicle_make_id, $vehicles_makes_to_type_ids)) echo 'checked="checked"';?> />
          </td>
          <td></td>
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