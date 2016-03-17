<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['vehicle_make'])) {
    $vehicle_make = mysqli_real_escape_string($db_link,$_POST['vehicle_make']);
  }
  if(!empty($vehicle_make)) {
    
    $query = "INSERT INTO `vehicles_makes`(`vehicle_make_id`, `vehicle_make`) 
                                    VALUES ('','$vehicle_make')";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) > 0) {
      $vehicle_make_id = mysqli_insert_id($db_link);
?>
    <div id="vehicle_make<?php echo $vehicle_make_id;?>" class="row_over">
      <table>
        <tr>
          <td width="5%"><button class="btn_save" onClick="EditVehicleMake('<?php echo $vehicle_make_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="20%"><input type="text" class="vehicle_make" value="<?php echo $_POST['vehicle_make'];?>" /></td>
          <td width="5%" class="no_background"><button class="remove" onClick="DeleteVehicleMake('<?php echo $vehicle_make_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    }
  }
  
  DB_CloseI($db_link);