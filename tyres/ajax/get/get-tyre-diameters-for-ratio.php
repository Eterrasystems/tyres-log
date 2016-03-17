<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_ratio_id'])) {
    $selected_tyre_ratio_id = $_POST['tyre_ratio_id'];
  }

  $tyres_diameter_to_ratio_ids = array();
  $query = "SELECT `tyre_diameter_id` FROM `tyres_diameter_to_ratio` WHERE `tyre_ratio_id` = '$selected_tyre_ratio_id'";
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      $tyres_diameter_to_ratio_ids[] = $row['tyre_diameter_id'];
    }
  }
?>

  <table class="no_margin">
    <thead>
      <tr>
        <td width="7%"><?=$laguages[$default_lang]['btn_save'];?></td>
        <td width="30%"><?=$laguages[$default_lang]['tyre_diameters_thead'];?></td>
        <td width="10%"><?=$laguages[$default_lang]['btn_inlude'];?></td>
        <td></td>
      </tr>
    </thead>
  </table>
  <div class=tyre_diameters_checkboxes">
<?php
  $query = "SELECT `tyres_diameter`.* FROM `tyres_diameter` ORDER BY `tyre_diameter_order` ASC";
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    $key = 0;
    while($tyres_diameters = mysqli_fetch_assoc($result)) {

      $tyre_diameter_id = $tyres_diameters['tyre_diameter_id'];
      $tyre_diameter = $tyres_diameters['tyre_diameter'];
      $class = ((($key % 2) == 1) ? " even" : " odd");
?>
    <div id="tyre_diameter<?php echo $tyre_diameter_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="AddDeleteDiameterToRatio('<?php echo $tyre_diameter_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="tyre_diameter" value="<?php echo $tyre_diameter;?>" /></td>
          <td width="10%">
            <input type="checkbox" class="tyre_diameter_id" value="<?php echo $tyre_diameter_id;?>" <?php if(in_array($tyre_diameter_id, $tyres_diameter_to_ratio_ids)) echo 'checked="checked"';?> />
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
    <div id="no_records"><?=$laguages[$default_lang]['no_tyre_diameters_yet'];?></div>
<?php    
  }
  
  DB_CloseI($db_link);
?>
  </div>