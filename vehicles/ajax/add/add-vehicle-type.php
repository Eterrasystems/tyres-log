<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['vehicle_type'])) {
    $vehicle_type = mysqli_real_escape_string($db_link,$_POST['vehicle_type']);
  }
  if(isset($_POST['vehicle_image_id'])) {
    $vehicle_image_id = mysqli_real_escape_string($db_link,$_POST['vehicle_image_id']);
  }
  if(!empty($vehicle_type)) {
    
    $query = "INSERT INTO `vehicles_types`(`vehicle_type_id`, `vehicle_type`, `vehicle_image_id`) 
                                    VALUES ('','$vehicle_type','$vehicle_image_id')";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) > 0) {
      $vehicle_type_id = mysqli_insert_id($db_link);
?>
    <div id="vehicle_type<?php echo $vehicle_type_id;?>" class="row_over">
      <table>
        <tr>
          <td width="5%"><button class="btn_save" onClick="EditVehicleType('<?php echo $vehicle_type_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="20%"><input type="text" class="vehicle_type" value="<?php echo $_POST['vehicle_type'];?>" /></td>
          <td width="20%"><input type="text" class="vehicle_image_id" value="<?php echo $_POST['vehicle_image_id'];?>" /></td>
          <td width="5%" class="no_background"><button class="remove" onClick="DeleteVehicleType('<?php echo $vehicle_type_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    }
  }
  
  DB_CloseI($db_link);