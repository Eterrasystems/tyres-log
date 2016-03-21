<h1><?=$laguages[$default_lang]['tyre_reception'];?>:</h1>
<?php
  if(!isset($_GET['tyre_storage_id'])) {
    $query_tyre_storages = "SELECT `tyres_storages`.`tyre_storage_id`,`tyres_storages`.`tyre_storage_datein`,
                                `warehouse_workers`.`user_firstname`,`warehouse_workers`.`user_lastname`,`clients`.`user_firstname` as client_firstname,
                                `clients`.`user_lastname` as client_lastname
                            FROM `tyres_storages`
                            INNER JOIN `users` as `warehouse_workers` ON `warehouse_workers`.`user_id` = `tyres_storages`.`employer_took_tyres`
                            INNER JOIN `users` as `clients` ON `clients`.`user_id` = `tyres_storages`.`client_id`
                            WHERE `tyre_storage_state` = '1'
                            ORDER BY `tyre_storage_id`";
    //echo $query_tyre_storages;
    $result_tyre_storages = mysqli_query($db_link, $query_tyre_storages);
    if(!$result_tyre_storages) echo mysqli_error($db_link);
    if(mysqli_num_rows($result_tyre_storages) > 0) {
      while($row_tyre_storages = mysqli_fetch_assoc($result_tyre_storages)) {

        $tyre_storage_id = $row_tyre_storages['tyre_storage_id'];
        $tyre_storage_id_formatted = sprintf('%010d', $tyre_storage_id);
        $tyre_storage_datein = date("H:m",  strtotime($row_tyre_storages['tyre_storage_datein']));
        $offince_employer_took_tyres = $row_tyre_storages['user_firstname']." ".$row_tyre_storages['user_lastname'];
        $client_name = $row_tyre_storages['client_firstname']." ".$row_tyre_storages['client_lastname'];

        echo "<p>- <a href='/protocols-takein-warehouse?tyre_storage_id=$tyre_storage_id'>$tyre_storage_id_formatted / $client_name / $tyre_storage_datein</a></p>";
      }
    }
    else {
      echo $laguages[$default_lang]['no_tyres_for_takein_warehouse'];
    }
  }
  else {
    
    $tyre_storage_id = $_GET['tyre_storage_id'];
    $tyre_storage_id_formatted = sprintf('%010d', $_GET['tyre_storage_id']);
    
    $query_tyre_storages = "SELECT `tyres_storages`.`vehicle_type_id`,`tyres_storages`.`client_id`,`tyres_storages`.`vehicle_make_id`,`tyres_storages`.`vehicle_model_id`,
                                  `tyres_storages`.`vehicle_plate`,`tyres_storages`.`tyre_storage_datein`,`warehouses`.`warehouse_name`,`warehouse_workers`.`user_firstname`,
                                  `warehouse_workers`.`user_lastname`,`clients`.`user_firstname` as client_firstname,`clients`.`user_lastname` as client_lastname
                            FROM `tyres_storages`
                            INNER JOIN `warehouses` ON `warehouses`.`warehouse_id` = `tyres_storages`.`warehouse_id`
                            INNER JOIN `users` as `warehouse_workers` ON `warehouse_workers`.`user_id` = `tyres_storages`.`employer_took_tyres`
                            INNER JOIN `users` as `clients` ON `clients`.`user_id` = `tyres_storages`.`client_id`
                            WHERE `tyres_storages`.`tyre_storage_id` = '$tyre_storage_id'";
    //echo $query_tyre_storages;
    $result_tyre_storages = mysqli_query($db_link, $query_tyre_storages);
    if(!$result_tyre_storages) echo mysqli_error($db_link);
    if(mysqli_num_rows($result_tyre_storages) > 0) {
        
      $row_tyre_storages = mysqli_fetch_assoc($result_tyre_storages);

      $client_id = $row_tyre_storages['client_id'];
      $current_vehicle_type_id = $row_tyre_storages['vehicle_type_id'];
      $current_vehicle_make_id = $row_tyre_storages['vehicle_make_id'];
      $current_vehicle_model_id = $row_tyre_storages['vehicle_model_id'];
      $vehicle_plate = $row_tyre_storages['vehicle_plate'];
      $tyre_storage_datein = date("H:m",  strtotime($row_tyre_storages['tyre_storage_datein']));
      $warehouse_name = $row_tyre_storages['warehouse_name'];
      $offince_employer_took_tyres = $row_tyre_storages['user_firstname']." ".$row_tyre_storages['user_lastname'];
      $client_name = $row_tyre_storages['client_firstname']." ".$row_tyre_storages['client_lastname'];

    }
?>
<div id="wrapper">
  <div id="warehouse_tyres_form">
<!--tyre_storage_id-->
    <div class="form_row">
      <label><?=$laguages[$default_lang]['reception_protocol_label'];?>:</label>
      <input type="hidden" name="tyre_storage_id" id="tyre_storage_id" value="<?=$tyre_storage_id;?>">
      <span><?=$tyre_storage_id_formatted;?></span>
    </div>
    <hr>
    
<!--client_name-->
    <div class="form_row">
      <label><?=$laguages[$default_lang]['client_name_label'];?>:</label>
      <span><?=$client_name;?></span>
      <input type="hidden" name="client_id" id="client_id"  value="<?=$client_id;?>"/>
      <input type="hidden" name="client_name" id="client_name"  value="<?=$client_name;?>"/>
    </div>

<!--vehicle_type-->
    <!--<div class="form_row" style="display:none">-->
    <div class="form_row">
      <div id="vehicle_type">
        <label><?=$laguages[$default_lang]['vehicle_type_label'];?>:</label>
        <div class="hidden">
<?php
    $query = "SELECT `vehicles_types`.* FROM `vehicles_types` ORDER BY `vehicle_type_id` ASC";
    $result = mysqli_query($db_link, $query);
    if(mysqli_num_rows($result) > 0) {
      while($vehicles_types = mysqli_fetch_assoc($result)) {

        $vehicle_type_id = $vehicles_types['vehicle_type_id'];
        $vehicle_type = $vehicles_types['vehicle_type'];
        $vehicle_type = $laguages[$default_lang][$vehicle_type];
        $vehicle_image_id = $vehicles_types['vehicle_image_id'];
        $class_active = "";
        if($vehicle_type_id == $current_vehicle_type_id) {
          $class_active = " active";
          $current_vehicle_type = $vehicle_type;
        }

        echo "<a data-id='$vehicle_type_id' id='$vehicle_image_id' class='vehicle_type$class_active' title='$vehicle_type'>$vehicle_type</a>";
      }
    }
?>
          </div>
        <span><?=$current_vehicle_type;?></span>
      </div>
    </div>
<!--vehicle_make-->
    <div class="form_row">
      <label><?=$laguages[$default_lang]['vehicle_make_label'];?>:</label>
      <select id="vehicle_make" class="hidden" onChange="LoadVehicleModelsForMakeInSelect()">
<?php
      // get only car makes
      $query = "SELECT `vehicles_makes`.* 
                FROM `vehicles_makes` 
                WHERE `vehicle_make_id` IN(SELECT `vehicle_make_id` FROM `vehicles_makes_to_type` WHERE `vehicle_type_id` = '1')
                ORDER BY `vehicle_make` ASC";
      $result = mysqli_query($db_link, $query);
      if(mysqli_num_rows($result) > 0) {

        while($vehicles_makes = mysqli_fetch_assoc($result)) {

          $vehicle_make_id = $vehicles_makes['vehicle_make_id'];
          $vehicle_make = $vehicles_makes['vehicle_make'];
          $selected = "";
          if($vehicle_make_id == $current_vehicle_make_id) {
            $selected = "selected='selected'";
            $current_vehicle_make = $vehicle_make;
          }

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
      <span><?=$current_vehicle_make;?></span>
    </div>
<!--vehicle_model-->
    <div class="form_row">
      <label><?=$laguages[$default_lang]['vehicle_model_label'];?>:</label>
      <select id="vehicle_model" class="hidden">
<?php
      $query = "SELECT `vehicles_models`.* 
                FROM `vehicles_models` 
                WHERE `vehicle_type_id` = '$current_vehicle_type_id' AND `vehicle_make_id` = '$current_vehicle_make_id'
                ORDER BY `vehicle_model` ASC";
      echo $query;
      $result = mysqli_query($db_link, $query);
      if(mysqli_num_rows($result) > 0) {

        while($vehicles_models = mysqli_fetch_assoc($result)) {

          $vehicle_model_id = $vehicles_models['vehicle_model_id'];
          $vehicle_model = $vehicles_models['vehicle_model'];
          $selected = "";
          if($vehicle_model_id = $current_vehicle_model_id) {
            $selected = "selected='selected'";
            $current_vehicle_model = $vehicle_model;
          }

          echo "<option value='$vehicle_model_id' $selected>$vehicle_model</option>";
        }
      }
?>
      </select>
      <span><?=$current_vehicle_model;?></span>
    </div>
<!--vehicle_plate-->
    <div class="form_row">
      <label><?=$laguages[$default_lang]['vehicle_plate_label'];?>:</label>
      <input type="text" name="vehicle_plate" id="vehicle_plate" class="hidden" value="<?=$vehicle_plate;?>">
      <span><?=$vehicle_plate;?></span>
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
?>
      <fieldset id="tyres_form_<?php echo $tyre_position_code;?>" tyre-position-id="<?php echo $tyre_position_id;?>" class="<?php echo $tyre_position_css_class;?> tyre_form" style="width: <?php echo $style_width;?>;">
        <div class="lock_tyres_form lock_tyres_form_<?php echo $tyre_position_code;?> active"></div>
        <legend><?=$laguages[$default_lang][$tyre_position_code];?></legend>
<!--tyre_make-->
        <div class="form_row">
          <label><?=$laguages[$default_lang]['tyre_make_label'];?></label>
          <select class="tyre_make" onChange="LoadTyresModelsForMakeInSelect('<?php echo $tyre_position_code;?>')">
            <option value="0" selected="selected"><?=$laguages[$default_lang]['choose_make'];?></option>
<?php
            $query = "SELECT `tyre_make_id`,`tyre_make` FROM `tyres_makes` ORDER BY `tyre_make` ASC";
            $result = mysqli_query($db_link, $query);
            if(mysqli_num_rows($result) > 0) {
              while($vehicles_makes = mysqli_fetch_assoc($result)) {

                $tyre_make_id = $vehicles_makes['tyre_make_id'];
                $tyre_make = $vehicles_makes['tyre_make'];

                echo "<option value='$tyre_make_id'>$tyre_make</option>";
              }
            }
?>
          </select>
        </div>
<!--tyre_model-->
        <div class="form_row">
          <label><?=$laguages[$default_lang]['tyre_model_label'];?>:</label>
          <select class="tyre_model_default">
            <option value="0" selected="selected"><?=$laguages[$default_lang]['choose_model'];?></option>
          </select>
          <select class="tyre_model" style="display: none;">

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
//              $tyre_season = $row['tyre_season'];
              $tyre_season_code = $row['tyre_season_code'];
              $tyre_season = $laguages[$default_lang][$tyre_season_code];

              echo "<a class='tyre_seasons $tyre_season_code' data-id='$tyre_season_id' code='$tyre_season_code' tyre-position='$tyre_position_code' title='$tyre_season'>$tyre_season</a>";
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
                    WHERE `tyres_width_to_vehicle_type`.`vehicle_type_id` = '$current_vehicle_type_id'
                    ORDER BY `tyres_width`.`tyre_width_order` ASC";
          //echo $query;
          $result = mysqli_query($db_link, $query);
          if(mysqli_num_rows($result) > 0) {

            echo "<option value='0' selected='selected'>".$laguages[$default_lang]['choose_tyre_width']."</option>";

            while($row = mysqli_fetch_assoc($result)) {
              $tyre_width_id = $row['tyre_width_id'];
              $tyre_width = $row['tyre_width'];

              echo "<option value='$tyre_width_id'>$tyre_width</option>";
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
          <select class="tyre_ratio_default">
            <option value="0" selected="selected"><?=$laguages[$default_lang]['choose_tyre_width_first'];?></option>
          </select>
          <select class="tyre_ratio" onChange="LoadTyreDiametersForRatioInSelect('<?php echo $tyre_position_code;?>')" style="display: none;">

          </select>
        </div>
<!--tyre_diameter-->
        <div class="form_row">
          <label><?=$laguages[$default_lang]['tyre_diameter_label'];?>:</label>
          <select class="tyre_diameter_default">
            <option value="0" selected="selected"><?=$laguages[$default_lang]['choose_tyre_ratio_first'];?></option>
          </select>
          <select class="tyre_diameter" style="display: none;">

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

              echo "<option value='$tyre_load_index_id'>$tyre_load_index</option>";
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

              echo "<option value='$tyre_speed_index_id'>$tyre_speed_index</option>";
            }
          }
?>
          </select>
        </div>
<!--tyre_dot-->
        <div class="form_row">
          <label><?=$laguages[$default_lang]['tyre_dot_label'];?>:</label>
          <input type="text" name="tyre_dot" class="tyre_dot input_text">
        </div>
<!--tyre_grapple_depth-->
        <div class="form_row">
          <label><?=$laguages[$default_lang]['tyre_grapple_depth_label'];?>:</label>
          <input type="text" name="tyre_grapple_depth" class="tyre_grapple_depth input_text">
        </div>
<!--tyre_defects-->
        <div class="form_row">
          <label><?=$laguages[$default_lang]['tyre_defects_label'];?>:</label>
          <input type="text" name="tyre_defects" class="tyre_defects input_text">
        </div>
<!--tyre_defects-->
        <div class="form_row">
          <label><?=$laguages[$default_lang]['tyre_rim_label'];?>:</label>
          <input type="checkbox" class="tyre_has_rim float_left">
          <input type="text" name="tyre_rim_note" class="tyre_rim_note input_text" style="width: 263px;" disabled="disabled">
        </div>
      </fieldset>
<?php
        } // while($tyres_positions_row)
      } // if(mysqli_num_rows($result_tyres_positions) > 0)
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
          <a id="<?php echo $tyre_position_code;?>" class="tyres_position">
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
      <input type="text" name="date_insert" id="date_insert" class="input_text datepicker" value="<?php echo date("Y-m-d");?>">
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
      <textarea name="tyre_note" id="tyre_note"></textarea>
    </div>
    <div class="form_row">
      <button name="save_take" id="save_take" class="button" onClick="ShowProtocolForComfirmationBeforeSaving()"><?=$laguages[$default_lang]['btn_insert_protocol'];?></button>
    </div>
  </div>
  <!--<div id="warehouse_tyres_form">-->
</div>
<script type="text/javascript">
$(document).ready(function() {
  $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
  
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
});
</script>
<?php
  }
?>