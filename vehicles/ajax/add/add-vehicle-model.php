<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['vehicle_type_id'])) {
    $vehicle_type_id = $_POST['vehicle_type_id'];
  }
  if(isset($_POST['vehicle_make_id'])) {
    $vehicle_make_id = $_POST['vehicle_make_id'];
  }
  if(isset($_POST['vehicle_model'])) {
    $vehicle_model = mysqli_real_escape_string($db_link,$_POST['vehicle_model']);
  }
  if(!empty($vehicle_model)) {
    
    $query = "INSERT INTO `vehicles_models`(`vehicle_model_id`, `vehicle_type_id`, `vehicle_make_id`, `vehicle_model`)
                                    VALUES ('','$vehicle_type_id','$vehicle_make_id','$vehicle_model')";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) > 0) { 
      $vehicle_model_id = mysqli_insert_id($db_link);
?>
    <div id="vehicle_model<?php echo $vehicle_model_id;?>" class="row_over">
      <table>
        <tr>
          <td width="7%"><button class="btn_save" onClick="EditVehicleModel('<?php echo $vehicle_model_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="vehicle_model" value="<?php echo $_POST['vehicle_model'];?>" /></td>
          <td width="5%" class="no_background"><button class="remove" onClick="DeleteVehicleModel('<?php echo $vehicle_model_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    }
  }
  
  DB_CloseI($db_link);