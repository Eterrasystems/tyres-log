<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['vehicle_type_id'])) {
    $selected_vehicle_type_id = $_POST['vehicle_type_id'];
  }
  
  $tyres_widths_to_type_ids = array();
  $query = "SELECT `tyre_width_id` FROM `tyres_width_to_vehicle_type` WHERE `vehicle_type_id` = '$selected_vehicle_type_id'";
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      $tyres_widths_to_type_ids[] = $row['tyre_width_id'];
    }
  }
?>
  <table class="no_margin">
    <thead>
      <tr>
        <td width="7%"><?=$laguages[$default_lang]['btn_save'];?></td>
        <td width="30%"><?=$laguages[$default_lang]['tyre_width_thead'];?></td>
        <td width="10%"><?=$laguages[$default_lang]['btn_inlude'];?></td>
        <td></td>
      </tr>
    </thead>
  </table>
  <div id="choose_vehicle_type">
<?php
  $query = "SELECT `tyres_width`.* FROM `tyres_width` ORDER BY `tyre_width_order` ASC";
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    $key = 0;
    while($tyres_widths = mysqli_fetch_assoc($result)) {
      
      $tyre_width_id = $tyres_widths['tyre_width_id'];
      $tyre_width = $tyres_widths['tyre_width'];
      $class = ((($key % 2) == 1) ? " even" : " odd");
?>
    <div id="tyre_width<?php echo $tyre_width_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="AddDeleteWidthToVehicleType('<?php echo $tyre_width_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="tyre_width" value="<?php echo $tyre_width;?>" /></td>
          <td width="10%">
            <input type="checkbox" class="tyre_width_id" value="<?php echo $tyre_width_id;?>" <?php if(in_array($tyre_width_id, $tyres_widths_to_type_ids)) echo 'checked="checked"';?> />
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
    <div id="no_records"><?=$laguages[$default_lang]['no_tyre_widths_yet'];?></div>
<?php    
  }
  
  DB_CloseI($db_link);
?>
  </div>