<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
  }
  if(isset($_POST['car_plate'])) {
    $car_plate = mysqli_real_escape_string($db_link,$_POST['car_plate']);
  }
  if(!empty($car_plate)) {
    
    $query = "INSERT INTO `vehicles_plates_to_clients`(`vptc_id`, `user_id`, `vehicle_plate`)
                                                VALUES ('','$user_id','$car_plate')";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) > 0) { 
      $vptc_id = mysqli_insert_id($db_link);
?>
    <div id="vehicle_plate<?php echo $vptc_id;?>" class="row_over">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditVehiclePlate('<?php echo $vptc_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="vehicle_plate" value="<?php echo $_POST['car_plate'];?>" /></td>
          <td width="5%" class="no_background"><button class="remove" onClick="DeleteVehiclePlate('<?php echo $vptc_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    }
  }
  
  DB_CloseI($db_link);