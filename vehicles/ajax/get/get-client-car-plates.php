<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
  }
  if(isset($_POST['user_name'])) {
    $user_name = $_POST['user_name'];
  }
?>

  <table class="no_margin">
    <thead>
      <tr>
        <td width="7%"><?=$laguages[$default_lang]['btn_save'];?></td>
        <td width="30%"><?php echo $laguages[$default_lang]['vehicle_client_car_plates']." $user_name"; ?></td>
        <td width="5%"><?=$laguages[$default_lang]['btn_delete']; ?></td>
        <td></td>
      </tr>
    </thead>
  </table>
  <div id="choose_vehicle_type">
<?php
  $query_vehicle_plates = "SELECT `vptc_id`,`vehicle_plate` FROM `vehicles_plates_to_clients` WHERE `user_id` = '$user_id'
                            ORDER BY `vehicle_plate` ASC";
  $result_vehicle_plates = mysqli_query($db_link, $query_vehicle_plates);
  if(mysqli_num_rows($result_vehicle_plates) > 0) {
    $key = 0;
    while($vehicle_plates = mysqli_fetch_assoc($result_vehicle_plates)) {

      $vptc_id = $vehicle_plates['vptc_id'];
      $vehicle_plate = $vehicle_plates['vehicle_plate'];
      $class = ((($key % 2) == 1) ? " even" : " odd");

?>
    <div id="vehicle_plate<?php echo $vptc_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditVehiclePlate('<?php echo $vptc_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="vehicle_plate" value="<?php echo $vehicle_plate;?>" /></td>
          <td width="5%" class="no_background"><button class="remove" onClick="DeleteVehiclePlate('<?php echo $vptc_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
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
    <div id="no_records"><?=$laguages[$default_lang]['no_vehicle_plates_for_client_yet'];?></div>
<?php    
  }
  
  DB_CloseI($db_link);
?>
  </div>