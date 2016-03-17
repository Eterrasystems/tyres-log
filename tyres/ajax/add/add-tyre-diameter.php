<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['tyre_diameter'])) {
    $tyre_diameter = $_POST['tyre_diameter'];
  }
  if(isset($_POST['tyre_diameter_order'])) {
    $tyre_diameter_order = $_POST['tyre_diameter_order'];
  }
  
  if(!empty($tyre_diameter)) {
    
    $query = "INSERT INTO `tyres_diameter`(`tyre_diameter_id`, `tyre_diameter`, `tyre_diameter_order`) 
                                    VALUES ('','$tyre_diameter','$tyre_diameter_order')";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) > 0) {
      $tyre_diameter_id = mysqli_insert_id($db_link);
?>
    <div id="tyre_diameter<?php echo $tyre_diameter_id;?>" class="row_over">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditTyreDiameter('<?php echo $tyre_diameter_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="tyre_diameter" value="<?php echo $tyre_diameter;?>" /></td>
          <td width="7%"><input type="text" class="tyre_diameter_order" value="<?php echo $tyre_diameter_order;?>" /></td>
          <td width="7%" class="no_background"><button class="remove" onClick="DeleteTyreDiameter('<?php echo $tyre_diameter_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    }
  }
  
  DB_CloseI($db_link);