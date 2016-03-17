<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['tyre_speed_index'])) {
    $tyre_speed_index = $_POST['tyre_speed_index'];
  }
  if(isset($_POST['tyre_speed_index_order'])) {
    $tyre_speed_index_order = $_POST['tyre_speed_index_order'];
  }
  
  if(!empty($tyre_speed_index)) {
    
    $query = "INSERT INTO `tyres_speed_index`(`tyre_speed_index_id`, `tyre_speed_index`, `tyre_speed_index_order`) 
                                    VALUES ('','$tyre_speed_index','$tyre_speed_index_order')";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) > 0) {
      $tyre_speed_index_id = mysqli_insert_id($db_link);
?>
    <div id="tyre_speed_index<?php echo $tyre_speed_index_id;?>" class="row_over">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditTyresSpeedIndex('<?php echo $tyre_speed_index_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="tyre_speed_index" value="<?php echo $tyre_speed_index;?>" /></td>
          <td width="7%"><input type="text" class="tyre_speed_index_order" value="<?php echo $tyre_speed_index_order;?>" /></td>
          <td width="7%" class="no_background"><button class="remove" onClick="DeleteTyreSpeedIndex('<?php echo $tyre_speed_index_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    }
  }
  
  DB_CloseI($db_link);