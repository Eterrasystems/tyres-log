<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_storage_id'])) {
    $tyre_storage_id = $_POST['tyre_storage_id'];
  }

  $query_warehouses_tyres = "SELECT `tyres_storages`.`client_id`, `tyres_storages`.`vehicle_model_id`, `tyres_storages`.`vehicle_plate`, 
                                    `tyres_storages`.`tyre_note`, `tyres_storages`.`tyre_storage_date`, `vehicles_makes`.`vehicle_make_id`, 
                                    `clients`.`user_firstname` as client_firstname, `clients`.`user_lastname` as client_lastname
                            FROM `tyres_storages`
                            INNER JOIN `vehicles_models` ON `vehicles_models`.`vehicle_model_id` = `tyres_storages`.`vehicle_model_id`
                            INNER JOIN `vehicles_makes` ON `vehicles_makes`.`vehicle_make_id` = `vehicles_models`.`vehicle_make_id`
                            INNER JOIN `users` as `clients` ON `clients`.`user_id` = `tyres_storages`.`client_id`
                            WHERE `tyres_storages`.`tyre_storage_id` = '$tyre_storage_id'";
  //echo $query_warehouses_tyres;exit;
  $result_warehouses_tyres = mysqli_query($db_link, $query_warehouses_tyres);
  if(mysqli_num_rows($result_warehouses_tyres) > 0) {
    $protocol_details = mysqli_fetch_assoc($result_warehouses_tyres);
    //echo"<pre>";print_r($protocol_details);exit;
    $protocol_client_id = $protocol_details['client_id'];
    $protocol_client_firstname = $protocol_details['client_firstname'];
    $protocol_client_lastname = $protocol_details['client_lastname'];
    $protocol_vehicle_make_id = $protocol_details['vehicle_make_id'];
    $protocol_vehicle_model_id = $protocol_details['vehicle_model_id'];
    $protocol_tyre_note = stripslashes($protocol_details['tyre_note']);
    $protocol_vehicle_plate = $protocol_details['vehicle_plate'];
    $tyre_storage_date = $protocol_details['tyre_storage_date'];
    
    $query_vehicle_type_id = "SELECT `vehicle_type_id` FROM `vehicles_models` WHERE `vehicle_model_id` = '$protocol_vehicle_model_id'";
    //echo $query_vehicle_type_id;
    $result_vehicle_type_id = mysqli_query($db_link, $query_vehicle_type_id);
    if(mysqli_num_rows($result_vehicle_type_id) > 0) {
      $row_result = mysqli_fetch_assoc($result_vehicle_type_id);
      $protocol_vehicle_type_id = $row_result['vehicle_type_id'];
    }
?>
<div id="warehouse_tyres_form">
<!--tyre_storage_id-->
  <div class="form_row">
    <label><?=$laguages[$default_lang]['reception_protocol_label'];?>:</label>
    <input type="text" name="tyre_storage_id" id="tyre_storage_id" class="input_text" value="<?php echo $tyre_storage_id; ?>" disabled="disabled">
  </div>
  <hr>
<!--client_name-->
  <div class="form_row">
    <label><?=$laguages[$default_lang]['client_name_label'];?>:</label>
    <input type="text" name="client_name" id="client_name" class="input_text" style="margin-right:10px;" value="<?php echo "$protocol_client_firstname $protocol_client_lastname"; ?>">
  </div>
  <input type="hidden" name="client_id" id="client_id" value="<?php echo $protocol_client_id; ?>" />
  <input type="hidden" name="client_error" id="client_error" value="<?=$laguages[$default_lang]['error_reception_protocol_client'];?>" />
<!--<hr>-->
  <hr>
<!--vehicle_type-->
    <div class="form_row" style="display:none">
      <label><?=$laguages[$default_lang]['vehicle_type_label'];?>:</label>
      <div id="vehicle_type">
<?php
    $query = "SELECT `vehicles_types`.* FROM `vehicles_types` ORDER BY `vehicle_type_id` ASC";
    $result = mysqli_query($db_link, $query);
    if(mysqli_num_rows($result) > 0) {
      while($vehicles_types = mysqli_fetch_assoc($result)) {

        $vehicle_type_id = $vehicles_types['vehicle_type_id'];
        $vehicle_type = $vehicles_types['vehicle_type'];
        $vehicle_type = $laguages[$default_lang][$vehicle_type];
        $vehicle_image_id = $vehicles_types['vehicle_image_id'];
        $class_active = ($protocol_vehicle_type_id == $vehicle_type_id) ? " active" : "";

        echo "<a data-id='$vehicle_type_id' id='$vehicle_image_id' class='vehicle_type$class_active' title='$vehicle_type'>$vehicle_type</a>";
      }
    }
?>
<!--        <a data-id="1" class="vehicle_type active"></a>-->
      </div>
    </div>
<!--vehicle_make-->
  <div class="form_row">
    <label><?=$laguages[$default_lang]['vehicle_make_label'];?>:</label>
    <select id="vehicle_make" onChange="LoadVehicleModelsForMakeInSelect()">
      <!--<option selected="selected"><?php echo FORM_CHOOSE_VEHICLE_TYPE_FIRST;?></option>-->
<?php
    // `vehicle_type_id` = '1' get only car makes
    $query = "SELECT `vehicles_makes`.* 
              FROM `vehicles_makes` 
              WHERE `vehicle_make_id` IN(SELECT `vehicle_make_id` FROM `vehicles_makes_to_type` WHERE `vehicle_type_id` = '$protocol_vehicle_type_id')
              ORDER BY `vehicle_make` ASC";
    echo $query;
    $result = mysqli_query($db_link, $query);
    if(mysqli_num_rows($result) > 0) {

      echo "<option value='0' selected='selected'>".$laguages[$default_lang]['choose_make']."</option>";
      while($vehicles_makes = mysqli_fetch_assoc($result)) {

        $vehicle_make_id = $vehicles_makes['vehicle_make_id'];
        $vehicle_make = $vehicles_makes['vehicle_make'];
        if ($protocol_vehicle_make_id == $vehicle_make_id)
            $selected = 'selected="selected"';
        else
            $selected = "";

        echo "<option value='$vehicle_make_id' $selected>$vehicle_make</option>";
      }
    }
    else {   
?>
      <option selected="selected"><?=$laguages[$default_lang]['no_makes_yet'];?></option>
<?php    
    }
?>
    </select>
  </div>
<!--vehicle_model-->
  <div class="form_row">
    <label><?=$laguages[$default_lang]['vehicle_model_label'];?>:</label>
    <select id="vehicle_model_default" style="display: none;">
      <option value="0" selected="selected"><?=$laguages[$default_lang]['choose_vehicle_make_first'];?></option>
    </select>
    <select id="vehicle_model">
<?php
  // `vehicle_type_id` = '1' cars
  $query = "SELECT `vehicles_models`.* 
            FROM `vehicles_models` 
            WHERE `vehicle_type_id` = '$protocol_vehicle_type_id' AND `vehicle_make_id` = '$protocol_vehicle_make_id'
            ORDER BY `vehicle_model` ASC";
  //echo $query;
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    
    echo "<option value='0'  selected='selected'>".$laguages[$default_lang]['choose_model']."</option>";
    while($vehicles_models = mysqli_fetch_assoc($result)) {
      
      $vehicle_model_id = $vehicles_models['vehicle_model_id'];
      $vehicle_model = $vehicles_models['vehicle_model'];
      if ($protocol_vehicle_model_id == $vehicle_model_id)
            $selected = 'selected="selected"';
        else
            $selected = "";
      
      echo "<option value='$vehicle_model_id' $selected>$vehicle_model</option>";
    }
  }
  else {   
?>
    <option selected="selected"><?=$laguages[$default_lang]['no_models_yet'];?></option>
<?php    
  }
?>
    </select>
  </div>
<!--vehicle_plate-->
  <div class="form_row">
    <label><?=$laguages[$default_lang]['vehicle_plate_label'];?>:</label>
    <input type="text" name="vehicle_plate" id="vehicle_plate" class="input_text" value='<?php echo $protocol_vehicle_plate; ?>'>
    <input type="hidden" name="vehicle_plate_error" id="vehicle_plate_error" value="<?=$laguages[$default_lang]['error_reception_protocol_vehicle_plate'];?>">
    <select id="vehicle_plate_select" style="display: none;">

    </select>
  </div>
<!--tyres_form-->
  <div id="tyres_form">
<?php
  $query_tyres_positions = "SELECT `tyre_position_id`, `tyre_position_code`, `tyre_position_css_class` FROM `tyres_position`";
  $result_tyres_positions = mysqli_query($db_link, $query_tyres_positions);
  if(mysqli_num_rows($result_tyres_positions) > 0) {
    while($tyres_positions_row = mysqli_fetch_assoc($result_tyres_positions)) {

      $tyre_position_id = $tyres_positions_row['tyre_position_id'];
      $tyre_position_code = $tyres_positions_row['tyre_position_code'];
      $tyre_position_css_class = $tyres_positions_row['tyre_position_css_class'];
      $style_width = ($tyre_position_css_class == "float_left") ? "41%" : "42%";
      
      $query_storage_details = "SELECT  `tyres_storages_details`.*, `tyres_makes`.`tyre_make_id`
                                FROM `tyres_storages_details`
                                INNER JOIN `tyres_models` ON `tyres_models`.`tyre_model_id` = `tyres_storages_details`.`tyre_model_id`
                                INNER JOIN `tyres_makes` ON `tyres_makes`.`tyre_make_id` = `tyres_models`.`tyre_make_id`
                                WHERE `tyres_storages_details`.`tyre_storage_id` = '$tyre_storage_id' AND `tyres_storages_details`.`tyre_position_id` = '$tyre_position_id'";
      //echo $query_storage_details."<br>";
      $result_storage_details = mysqli_query($db_link, $query_storage_details);
      if(mysqli_num_rows($result_storage_details) > 0) {
        $tyre_storage_details = mysqli_fetch_assoc($result_storage_details);
        //echo"<pre>";print_r($tyre_storage_details);echo"</pre><br>";
        $tyre_storage_details_id = $tyre_storage_details['tyre_storage_details_id'];
        $tyre_position_ids_in_db[] = $tyre_position_id;
        $tyre_storage_make_id = $tyre_storage_details['tyre_make_id'];
        $tyre_storage_model_id = $tyre_storage_details['tyre_model_id'];
        $tyre_storage_season_id = $tyre_storage_details['tyre_season_id'];
        $tyre_storage_width_id = $tyre_storage_details['tyre_width_id'];
        $tyre_storage_ratio_id = $tyre_storage_details['tyre_ratio_id'];
        $tyre_storage_diameter_id = $tyre_storage_details['tyre_diameter_id'];
        $tyre_storage_load_index_id = $tyre_storage_details['tyre_load_index_id'];
        $tyre_storage_speed_index_id = $tyre_storage_details['tyre_speed_index_id'];
        $tyre_storage_has_rim = $tyre_storage_details['tyre_has_rim'];
        $tyre_storage_rim_note = stripslashes($tyre_storage_details['tyre_rim_note']);
        $tyre_storage_dot = $tyre_storage_details['tyre_dot'];
        $tyre_storage_grapple_depth = $tyre_storage_details['tyre_grapple_depth'];
        $tyre_storage_defects = $tyre_storage_details['tyre_defects'];
?>
    <fieldset id="tyres_form_<?php echo $tyre_position_code;?>" tyre-storage-details-id="<?php echo $tyre_storage_details_id;?>" tyre-position-id="<?php echo $tyre_position_id;?>" class="<?php echo $tyre_position_css_class;?> tyre_form active" style="width: <?php echo $style_width;?>;">
      <div class="lock_tyres_form lock_tyres_form_<?php echo $tyre_position_code;?>"></div>
      <legend><?=$laguages[$default_lang][$tyre_position_code];?></legend>
<!--tyre_make-->
      <div class="form_row">
        <label><?=$laguages[$default_lang]['tyre_make_label'];?></label>
        <select class="tyre_make" onChange="LoadTyresModelsForMakeInSelect('<?php echo $tyre_position_code;?>')">
          <option value="0" selected="selected"><?php echo FORM_CHOOSE_TYRE_MAKE;?></option>
<?php
          $query = "SELECT `tyre_make_id`,`tyre_make` FROM `tyres_makes` ORDER BY `tyre_make` ASC";
          $result = mysqli_query($db_link, $query);
          if(mysqli_num_rows($result) > 0) {
            while($vehicles_makes = mysqli_fetch_assoc($result)) {

              $tyre_make_id = $vehicles_makes['tyre_make_id'];
              $tyre_make = $vehicles_makes['tyre_make'];
              if ($tyre_storage_make_id == $tyre_make_id)
                  $selected = 'selected="selected"';
              else
                  $selected = "";

              echo "<option value='$tyre_make_id' $selected>$tyre_make</option>";
            }
          }
?>
        </select>
      </div>
<!--tyre_model-->
      <div class="form_row">
        <label><?=$laguages[$default_lang]['tyre_model_label'];?>:</label>
        <select class="tyre_model_default" style="display: none;">
          <option value="0" selected="selected"><?=$laguages[$default_lang]['choose_model'];?></option>
        </select>
        <select class="tyre_model">
<?php
          $query = "SELECT `tyres_models`.* 
                    FROM `tyres_models` 
                    WHERE `tyre_make_id` = '$tyre_storage_make_id'
                    ORDER BY `tyre_model` ASC";
          //echo $query;
          $result = mysqli_query($db_link, $query);
          if(mysqli_num_rows($result) > 0) {

            echo "<option value='0'  selected='selected'>".$laguages[$default_lang]['choose_model']."</option>";
            while($tyres_models = mysqli_fetch_assoc($result)) {

              $tyre_model_id = $tyres_models['tyre_model_id'];
              $tyre_model = $tyres_models['tyre_model'];
              if ($tyre_storage_model_id == $tyre_model_id)
                  $selected = 'selected="selected"';
              else
                  $selected = "";

              echo "<option value='$tyre_model_id' $selected>$tyre_model</option>";
            }
?>
        <script type="text/javascript">
        $(document).ready(function() {
          $( ".tyre_model" ).change(function() {
            if($("#copy_to_next_forms").is(":checked") == "1") {
              var tyre_model_id = $(this).val();
              $(".tyre_form.active .tyre_model").val(tyre_model_id);
            }
          });
        });
        </script>
<?php
          }
          else {   
?>
            <option selected="selected"><?=$laguages[$default_lang]['no_models_yet'];?></option>
<?php    
          }
?>
        </select>
      </div>
<!--tyre_season-->
      <div id="tyre_seasons_<?php echo $tyre_position_code;?>" class="form_row">
        <label><?=$laguages[$default_lang]['tyre_season_label'];?></label>
<?php
        $query = "SELECT `tyre_season_id`, `tyre_season`, `tyre_season_code` FROM `tyres_seasons`";
        //echo $query;
        $result = mysqli_query($db_link, $query);
        if(mysqli_num_rows($result) > 0) {

          while($row = mysqli_fetch_assoc($result)) {
            $tyre_season_id = $row['tyre_season_id'];
            $tyre_season = $row['tyre_season'];
            $tyre_season_code = $row['tyre_season_code'];
            $class_active = ($tyre_storage_season_id == $tyre_season_id) ? " active" : "";

            echo "<a class='tyre_seasons $tyre_season_code$class_active' data-id='$tyre_season_id' code='$tyre_season_code' tyre-position='$tyre_position_code' title='$tyre_season'>$tyre_season</a>";
          }
        }  
?>
      </div>
<!--tyre_width-->
      <div class="form_row">
        <label><?=$laguages[$default_lang]['tyre_width_label'];?>:</label>
        <select class="tyre_width_default" style="display: none;">
          <option value="0" selected="selected"><?=$laguages[$default_lang]['choose_vehicle_type_first'];?></option>
        </select>
        <select class="tyre_width" onChange="LoadTyreRatiosForWidthInSelect('<?php echo $tyre_position_code;?>')">
<?php
        $query = "SELECT `tyres_width`.`tyre_width_id`, `tyres_width`.`tyre_width` 
                  FROM `tyres_width_to_vehicle_type`
                  INNER JOIN `tyres_width` ON `tyres_width`.`tyre_width_id` = `tyres_width_to_vehicle_type`.`tyre_width_id`
                  WHERE `tyres_width_to_vehicle_type`.`vehicle_type_id` = '1'
                  ORDER BY `tyres_width`.`tyre_width_order` ASC";
        //echo $query;
        $result = mysqli_query($db_link, $query);
        if(mysqli_num_rows($result) > 0) {

          echo "<option value='0' selected='selected'>".$laguages[$default_lang]['choose_tyre_width']."</option>";

          while($row = mysqli_fetch_assoc($result)) {
            $tyre_width_id = $row['tyre_width_id'];
            $tyre_width = $row['tyre_width'];
            if ($tyre_storage_width_id == $tyre_width_id)
                  $selected = 'selected="selected"';
              else
                  $selected = "";

            echo "<option value='$tyre_width_id' $selected>$tyre_width</option>";
          }
        }
        else {   
?>
          <option selected="selected"><?=$laguages[$default_lang]['form_no_tyre_widths_yet'];?></option>
<?php    
        }
?>
        </select>
      </div>
<!--tyre_ratio-->
      <div class="form_row">
        <label><?=$laguages[$default_lang]['tyre_ratio_label'];?>:</label>
        <select class="tyre_ratio_default" style="display: none;">
          <option value="0" selected="selected"><?=$laguages[$default_lang]['choose_tyre_width_first'];?></option>
        </select>
        <select class="tyre_ratio" onChange="LoadTyreDiametersForRatioInSelect('<?php echo $tyre_position_code;?>')">
<?php
        $query = "SELECT `tyres_ratio`.`tyre_ratio_id`, `tyres_ratio`.`tyre_ratio` 
                  FROM `tyres_ratio_to_width`
                  INNER JOIN `tyres_ratio` ON `tyres_ratio`.`tyre_ratio_id` = `tyres_ratio_to_width`.`tyre_ratio_id`
                  WHERE `tyres_ratio_to_width`.`tyre_width_id` = '$tyre_storage_width_id'
                  ORDER BY `tyres_ratio`.`tyre_ratio_order` ASC";
        //echo $query;
        $result = mysqli_query($db_link, $query);
        if(mysqli_num_rows($result) > 0) {

          echo "<option value='0' selected='selected'>".$laguages[$default_lang]['choose_tyre_ratio']."</option>";

          while($row = mysqli_fetch_assoc($result)) {
            $tyre_ratio_id = $row['tyre_ratio_id'];
            $tyre_ratio = $row['tyre_ratio'];
            if ($tyre_storage_ratio_id == $tyre_ratio_id)
                  $selected = 'selected="selected"';
              else
                  $selected = "";

            echo "<option value='$tyre_ratio_id' $selected>$tyre_ratio</option>";
          }
        }
        else {   
?>
          <option selected="selected"><?=$laguages[$default_lang]['no_tyre_ratios_yet'];?></option>
<?php    
        }
?>
        </select>
      </div>
<!--tyre_diameter-->
      <div class="form_row">
        <label><?=$laguages[$default_lang]['tyre_diameter_label'];?>:</label>
        <select class="tyre_diameter_default" style="display: none;">
          <option value="0" selected="selected"><?=$laguages[$default_lang]['choose_tyre_ratio_first'];?></option>
        </select>
        <select class="tyre_diameter">
<?php
        $query = "SELECT `tyres_diameter`.`tyre_diameter_id`, `tyres_diameter`.`tyre_diameter` 
                  FROM `tyres_diameter_to_ratio`
                  INNER JOIN `tyres_diameter` ON `tyres_diameter`.`tyre_diameter_id` = `tyres_diameter_to_ratio`.`tyre_diameter_id`
                  WHERE `tyres_diameter_to_ratio`.`tyre_ratio_id` = '$tyre_storage_ratio_id'
                  ORDER BY `tyres_diameter`.`tyre_diameter_order` ASC";
        //echo $query;
        $result = mysqli_query($db_link, $query);
        if(mysqli_num_rows($result) > 0) {

          echo "<option value='0' selected='selected'>".$laguages[$default_lang]['choose_tyre_diameter']."</option>";

          while($row = mysqli_fetch_assoc($result)) {
            $tyre_diameter_id = $row['tyre_diameter_id'];
            $tyre_diameter = $row['tyre_diameter'];
            if ($tyre_storage_diameter_id == $tyre_diameter_id)
                  $selected = 'selected="selected"';
              else
                  $selected = "";

            echo "<option value='$tyre_diameter_id' $selected>$tyre_diameter</option>";
          }
        }
        else {   
?>
          <option selected="selected"><?=$laguages[$default_lang]['no_tyre_diameters_yet'];?></option>
<?php    
        }
?>
        </select>
      </div>
<!--tyre_load_index-->
      <div class="form_row">
        <label><?=$laguages[$default_lang]['tyre_load_index_label'];?>:</label>
<!--            <select id="tyre_load_index_default">
            <option value="0" selected="selected"><?=$laguages[$default_lang]['choose_tyre_ratio_first'];?></option>
          </select>-->
        <select class="tyre_load_index">
          <option value="0" selected="selected"><?=$laguages[$default_lang]['choose_tyre_load_index'];?></option>
<?php
        $query_tyre_load_index = "SELECT `tyre_load_index_id`,`tyre_load_index` FROM `tyres_load_index` ORDER BY `tyre_load_index_order` ASC";
        //echo $query_tyre_load_index;
        $result_tyre_load_index = mysqli_query($db_link, $query_tyre_load_index);
        if(mysqli_num_rows($result_tyre_load_index) > 0) {
          while($tyres_load_indexs = mysqli_fetch_assoc($result_tyre_load_index)) {

            $tyre_load_index_id = $tyres_load_indexs['tyre_load_index_id'];
            $tyre_load_index = $tyres_load_indexs['tyre_load_index'];
            if ($tyre_storage_load_index_id == $tyre_load_index_id)
                  $selected = 'selected="selected"';
              else
                  $selected = "";

            echo "<option value='$tyre_load_index_id' $selected>$tyre_load_index</option>";
          }
        }
?>
        </select>
      </div>
<!--tyre_speed_index-->
      <div class="form_row">
        <label><?=$laguages[$default_lang]['tyre_speed_index_label'];?>:</label>
<!--            <select id="tyre_speed_index_default">
            <option value="0" selected="selected"><?=$laguages[$default_lang]['choose_tyre_ratio_first'];?></option>
          </select>-->
        <select class="tyre_speed_index">
          <option value="0" selected="selected"><?=$laguages[$default_lang]['choose_tyre_speed_index'];?></option>
<?php
        $query_tyre_speed_index = "SELECT `tyre_speed_index_id`,`tyre_speed_index` FROM `tyres_speed_index` ORDER BY `tyre_speed_index_order` ASC";
        //echo $query_tyre_speed_index;
        $result_tyre_speed_index = mysqli_query($db_link, $query_tyre_speed_index);
        if(mysqli_num_rows($result_tyre_speed_index) > 0) {
          while($tyres_speed_indexs = mysqli_fetch_assoc($result_tyre_speed_index)) {

            $tyre_speed_index_id = $tyres_speed_indexs['tyre_speed_index_id'];
            $tyre_speed_index = $tyres_speed_indexs['tyre_speed_index'];
            if ($tyre_storage_speed_index_id == $tyre_speed_index_id)
                  $selected = 'selected="selected"';
              else
                  $selected = "";

            echo "<option value='$tyre_speed_index_id' $selected>$tyre_speed_index</option>";
          }
        }
?>
        </select>
      </div>
<!--tyre_dot-->
      <div class="form_row">
        <label><?=$laguages[$default_lang]['tyre_dot_label'];?>:</label>
        <input type="text" name="tyre_dot" class="tyre_dot input_text" value='<?php echo $tyre_storage_dot;?>'>
      </div>
<!--tyre_grapple_depth-->
      <div class="form_row">
        <label><?=$laguages[$default_lang]['tyre_grapple_depth_label'];?>:</label>
        <input type="text" name="tyre_grapple_depth" class="tyre_grapple_depth input_text" value='<?php echo $tyre_storage_grapple_depth;?>'>
      </div>
<!--tyre_defects-->
      <div class="form_row">
        <label><?=$laguages[$default_lang]['tyre_defects_label'];?>:</label>
        <input type="text" name="tyre_defects" class="tyre_defects input_text" value='<?php echo $tyre_storage_defects;?>'>
      </div>
<!--tyre_defects-->
      <div class="form_row">
        <label><?=$laguages[$default_lang]['tyre_rim_label'];?>:</label>
        <input type="checkbox" class="tyre_has_rim float_left <?php if($tyre_storage_has_rim == 1) echo 'active';?>" <?php if($tyre_storage_has_rim == 1) echo 'checked="checked"';?>>
        <input type="text" class="tyre_rim_note input_text" style="width: 263px;" <?php if($tyre_storage_has_rim == 0) echo 'disabled="disabled"'; else echo "value='$tyre_storage_rim_note'";?>>
      </div>
    </fieldset>
<?php
      } // while($tyres_positions_row)
    } // if(mysqli_num_rows($result_tyres_positions) > 0)
  } // if(mysqli_num_rows($result_storage_details) > 0)
?>
    <div id="car_prototype">
      <div class="form_row">
        <input type="checkbox" id="copy_to_next_forms" checked="checked"> <?=$laguages[$default_lang]['copy_to_next_fields'];?>
      </div>
<?php
    $query = "SELECT `tyre_position_id`, `tyre_position_code` FROM `tyres_position`";
    $result = mysqli_query($db_link, $query);
    if(mysqli_num_rows($result) > 0) {
      while($tyres_positions = mysqli_fetch_assoc($result)) {

        $tyre_position_id = $tyres_positions['tyre_position_id'];
        $tyre_position_code = $tyres_positions['tyre_position_code'];
?>
      <a id="<?php echo $tyre_position_code;?>" class="tyres_position<?php if(in_array($tyre_position_id, $tyre_position_ids_in_db)) echo " active";?>">
          <img src="images/car-prototype-active-tyre.png" title="<?=$laguages[$default_lang][$tyre_position_code];?>" width="23" height="41" alt="<?=$laguages[$default_lang]['car_prototype_tyre'];?>"/>
        </a>
<?php
      }
    }
?>
      <img src="images/car-prototype_85x200.jpg" title="<?=$laguages[$default_lang]['choose_car_tyre'];?>" width="85" height="200" alt="<?=$laguages[$default_lang]['car_prototype'];?>"/>
    </div>
    <div class="clearfix"></div>
  </div>
<!--form_row-->
  <div class="form_row">
    <label><?=$laguages[$default_lang]['reception_date_label'];?>:</label>
    <input type="text" name="date_insert" id="date_insert" class="input_text datepicker" value="<?=$tyre_storage_date;?>">
  </div>
  <div class="form_row">
    <label><?=$laguages[$default_lang]['reception_site_label'];?>:</label>
    <input type="text" name="warehouse_name" id="warehouse_name" class="input_text" disabled value="<?php echo $_SESSION['tyreslog']['warehouse_name'];?>">
    <input type="hidden" name="warehouse_id" id="warehouse_id" class="input_text" value="<?php echo $_SESSION['tyreslog']['warehouse_id'];?>">
  </div>
  <div class="form_row">
    <label><?=$laguages[$default_lang]['reception_employer_label'];?>:</label>
    <input type="text" name="user_name" id="user_name" class="input_text" disabled value="<?php echo $_SESSION['tyreslog']['user_fullname'];?>">
  </div>
  <div class="form_row">
    <label><?=$laguages[$default_lang]['reception_note_label'];?>:</label>
    <textarea name="tyre_note" id="tyre_note"><?php echo $protocol_tyre_note; ?></textarea>
  </div>
  <div class="form_row">
    <button name="save_take" id="save_take" class="button" onClick="EditProtocol('<?php echo $tyre_storage_id; ?>')"><?=$laguages[$default_lang]['btn_save'];?></button>
  </div>
</div>
<!--<div id="warehouse_tyres_form">-->
<script type="text/javascript">
$(document).ready(function() {
  $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
  
  $(".vehicle_type").click(function() {
    $(".vehicle_type").removeClass("active");
    $(this).addClass("active");
    LoadVehicleMakesForTypeInSelect();
    LoadTyreWidthsForVehicleType();
  });
  
  $(".tyre_seasons").click(function() {
    var tyre_position = $(this).attr("tyre-position");
    var code = $(this).attr("code");
    if($("#copy_to_next_forms").is(":checked") == "1") {
      $(".tyre_seasons").removeClass("active");
      $(".tyre_form.active ."+code).addClass("active");
    }
    else {
      $("#tyre_seasons_"+tyre_position+" .tyre_seasons").removeClass("active");
      $(this).addClass("active");
    }
  });
  
  $( ".tyre_load_index" ).change(function() {
    if($("#copy_to_next_forms").is(":checked") == "1") {
      var tyre_load_index_id = $(this).val();
      $(".tyre_form.active .tyre_load_index").val(tyre_load_index_id);
    }
  });
  
  $( ".tyre_speed_index" ).change(function() {
    if($("#copy_to_next_forms").is(":checked") == "1") {
      var tyre_speed_index_id = $(this).val();
      $(".tyre_form.active .tyre_speed_index").val(tyre_speed_index_id); 
    }
  });
  
  $( ".tyre_grapple_depth" ).blur(function() {
    if($("#copy_to_next_forms").is(":checked") == "1") {
      var tyre_grapple_depth = $(this).val();
      if(tyre_grapple_depth != "") {
        $(".tyre_form.active .tyre_grapple_depth").val(tyre_grapple_depth);
      }
    }
  });
  
  $( ".tyre_dot" ).blur(function() {
    if($("#copy_to_next_forms").is(":checked") == "1") {
      var tyre_dot_value = $(this).val();
      if(tyre_dot_value != "") {
        $(".tyre_form.active .tyre_dot").val(tyre_dot_value);
      }
    } 
  });
  
  $( ".tyre_grapple_depth" ).blur(function() {
    if($("#copy_to_next_forms").is(":checked") == "1") {
      var tyre_grapple_depth_value = $(this).val();
      if(tyre_grapple_depth_value != "") {
        $(".tyre_form.active .tyre_grapple_depth").val(tyre_grapple_depth_value);
      }
    }
  });
  
  $( ".tyre_defects" ).blur(function() {
    if($("#copy_to_next_forms").is(":checked") == "1") {
      var tyre_defects_value = $(this).val();
      if(tyre_defects_value != "") {
        $(".tyre_form.active .tyre_defects").val(tyre_defects_value);
      }
    }
  });
  
  $(".tyre_has_rim").click(function() {
    if($(this).hasClass("active")) {
      if($("#copy_to_next_forms").is(":checked") == "1") {
        $(".tyre_form.active .tyre_has_rim").removeClass("active");
        $(".tyre_form.active .tyre_has_rim").prop('checked', false);
        $(".tyre_form.active .tyre_has_rim").parent().children(".tyre_rim_note").prop("disabled", true);
      }
      else {
        $(this).removeClass("active");
        $(this).parent().children(".tyre_rim_note").prop("disabled", true);
      }
    }
    else {
      if($("#copy_to_next_forms").is(":checked") == "1") {
        $(".tyre_form.active .tyre_has_rim").addClass("active");
        $(".tyre_form.active .tyre_has_rim").prop('checked', true);
        $(".tyre_form.active .tyre_has_rim").parent().children(".tyre_rim_note").prop("disabled", false);
      }
      else {
        $(this).addClass("active");
        $(this).parent().children(".tyre_rim_note").prop("disabled", false);
      }
    }
  });
  
  $( ".tyre_rim_note" ).blur(function() {
    if($("#copy_to_next_forms").is(":checked") == "1") {
      var tyre_rim_note_value = $(this).val();
      if(tyre_rim_note_value != "") {
        $(".tyre_form.active .tyre_rim_note").val(tyre_rim_note_value);
      }
    }
  });
  
  $(".tyres_position").click(function() {
    var tyres_position_code = $(this).attr("id");
    if($(this).hasClass("active")) {
      $("#tyres_form_"+tyres_position_code).removeClass("active");
      $(".lock_tyres_form_"+tyres_position_code).addClass("active");
      $(this).removeClass("active");
    }
    else {
      $("#tyres_form_"+tyres_position_code).addClass("active");
      $(".lock_tyres_form_"+tyres_position_code).removeClass("active");
      $(this).addClass("active");
    }
  });
    
  //autocomplete Client
  $( "#client_name" ).autocomplete({
    source: "protocols/ajax/get/get-clients-for-autocomplete.php",
    minLength: 2,
    select: function( event, ui ) {
      //alert(ui.item.client_id);
      $('#client_name').val(ui.item.client_name);
      $('#client_id').val(ui.item.client_id);
      LoadVehiclePlatesForClient(ui.item.client_id);
    }
  });
});
</script>
<?php
  } // if(mysqli_num_rows($result_warehouses_tyres) > 0)