<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_width_id'])) {
    $selected_tyre_width_id = $_POST['tyre_width_id'];
  }
  if(isset($_POST['result_type'])) {
    $result_type = $_POST['result_type'];
  }
  else $result_type = "";
  
  if($result_type == "list") {
    $tyres_ratio_to_width_ids = array();
    $query = "SELECT `tyres_ratio`.`tyre_ratio_id`, `tyres_ratio`.`tyre_ratio` 
              FROM `tyres_ratio_to_width`
              INNER JOIN `tyres_ratio` ON `tyres_ratio`.`tyre_ratio_id` = `tyres_ratio_to_width`.`tyre_ratio_id`
              WHERE `tyres_ratio_to_width`.`tyre_width_id` = '$selected_tyre_width_id'
              ORDER BY `tyres_ratio`.`tyre_ratio_order` ASC";
    //echo $query;
    $result = mysqli_query($db_link, $query);
?>
    <table class="no_margin">
      <thead>
        <tr><td><?=$laguages[$default_lang]['choose_tyre_ratio_thead'];?></td></tr>
      </thead>
    </table>
    <div id="choose_tyre_ratio">
      <table>
        <tbody>
<?php
    if(mysqli_num_rows($result) > 0) {

      while($row = mysqli_fetch_assoc($result)) {
        $tyre_ratio_id = $row['tyre_ratio_id'];
        $tyre_ratio = $row['tyre_ratio'];
        
        echo "<tr><td><a data-id='$tyre_ratio_id'>$tyre_ratio</a></td></tr>";
      }
    }
    else {
?>
        <tr><td><?=$laguages[$default_lang]['no_tyres_ratios_for_choosen_width_yet'];?></td></tr>
<?php
    }
?>
        </tbody>
      </table>
    </div>
<?php
  }
  else {
    $tyres_ratio_to_width_ids = array();
    $query = "SELECT `tyre_ratio_id` FROM `tyres_ratio_to_width` WHERE `tyre_width_id` = '$selected_tyre_width_id'";
    $result = mysqli_query($db_link, $query);
    if(mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $tyres_ratio_to_width_ids[] = $row['tyre_ratio_id'];
      }
    }
  ?>

    <table class="no_margin">
      <thead>
        <tr>
          <td width="7%"><?=$laguages[$default_lang]['btn_save'];?></td>
          <td width="30%"><?=$laguages[$default_lang]['tyre_ratio_thead'];?></td>
          <td width="10%"><?=$laguages[$default_lang]['btn_inlude'];?></td>
          <td></td>
        </tr>
      </thead>
    </table>
    <div id="tyre_ratios_checkboxes">
  <?php
    $query = "SELECT `tyres_ratio`.* FROM `tyres_ratio` ORDER BY `tyre_ratio_order` ASC";
    $result = mysqli_query($db_link, $query);
    if(mysqli_num_rows($result) > 0) {
      $key = 0;
      while($tyres_ratios = mysqli_fetch_assoc($result)) {

        $tyre_ratio_id = $tyres_ratios['tyre_ratio_id'];
        $tyre_ratio = $tyres_ratios['tyre_ratio'];
        $class = ((($key % 2) == 1) ? " even" : " odd");
  ?>
      <div id="tyre_ratio<?php echo $tyre_ratio_id;?>" class="row_over<?php echo $class;?>">
        <table>
          <tr>
            <td width="7%"><button class="button btn_save" onClick="AddDeleteRatioToWidth('<?php echo $tyre_ratio_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
            <td width="30%"><input type="text" class="tyre_ratio" value="<?php echo $tyre_ratio;?>" /></td>
            <td width="10%">
              <input type="checkbox" class="tyre_ratio_id" value="<?php echo $tyre_ratio_id;?>" <?php if(in_array($tyre_ratio_id, $tyres_ratio_to_width_ids)) echo 'checked="checked"';?> />
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
      <div id="no_records"><?=$laguages[$default_lang]['no_tyre_ratios_yet'];?></div>
  <?php    
    }
  }
  
  DB_CloseI($db_link);

  if($result_type == "list") {
?>
<script type="text/javascript">
  $(document).ready(function() {
    $("#choose_tyre_ratio a").click(function() {
      $("#choose_tyre_ratio td").removeClass("selected_tyre_ratio")
      $(this).parent().addClass("selected_tyre_ratio");
      GetTyreDiametersForRatio();
    });
  });
</script>
<?php
  }