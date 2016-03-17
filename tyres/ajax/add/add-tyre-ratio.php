<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['tyre_ratio'])) {
    $tyre_ratio = $_POST['tyre_ratio'];
  }
  if(isset($_POST['tyre_ratio_order'])) {
    $tyre_ratio_order = $_POST['tyre_ratio_order'];
  }
  
  if(!empty($tyre_ratio)) {
    
    $query = "INSERT INTO `tyres_ratio`(`tyre_ratio_id`, `tyre_ratio`, `tyre_ratio_order`) 
                                    VALUES ('','$tyre_ratio','$tyre_ratio_order')";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) > 0) {
      $tyre_ratio_id = mysqli_insert_id($db_link);
?>
    <div id="tyre_ratio<?php echo $tyre_ratio_id;?>" class="row_over">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditTyreRatio('<?php echo $tyre_ratio_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="tyre_ratio" value="<?php echo $tyre_ratio;?>" /></td>
          <td width="7%"><input type="text" class="tyre_ratio_order" value="<?php echo $tyre_ratio_order;?>" /></td>
          <td width="7%" class="no_background"><button class="remove" onClick="DeleteTyreRatio('<?php echo $tyre_ratio_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    }
  }
  
  DB_CloseI($db_link);