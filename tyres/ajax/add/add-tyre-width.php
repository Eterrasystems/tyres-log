<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['tyre_width'])) {
    $tyre_width = $_POST['tyre_width'];
  }
  if(isset($_POST['tyre_width_order'])) {
    $tyre_width_order = $_POST['tyre_width_order'];
  }
  
  if(!empty($tyre_width)) {
    
    $query = "INSERT INTO `tyres_width`(`tyre_width_id`, `tyre_width`, `tyre_width_order`) 
                                    VALUES ('','$tyre_width','$tyre_width_order')";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) > 0) {
      $tyre_width_id = mysqli_insert_id($db_link);
?>
    <div id="tyre_width<?php echo $tyre_width_id;?>" class="row_over">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditTyreWidth('<?php echo $tyre_width_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="tyre_width" value="<?php echo $_POST['tyre_width'];?>" /></td>
          <td width="7%"><input type="text" class="tyre_width_order" value="<?php echo $tyre_width_order;?>" /></td>
          <td width="7%" class="no_background"><button class="remove" onClick="DeleteTyreWidth('<?php echo $tyre_width_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    }
  }
  
  DB_CloseI($db_link);