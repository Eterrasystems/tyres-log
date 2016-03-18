<?php
  if(isset($_POST['create_tyre_storage'])) {
    //echo "<pre>";print_r($_POST);exit;
    
    if(isset($_POST['client_id'])) {
      $client_id = $_POST['client_id'];
    }
    if(isset($_POST['vehicle_type'])) {
      $vehicle_type_id = $_POST['vehicle_type'];
    }
    if(isset($_POST['vehicle_make'])) {
      $vehicle_make_id = $_POST['vehicle_make'];
    }
    if(isset($_POST['vehicle_model'])) {
      $vehicle_model_id = $_POST['vehicle_model'];
    }
    if(isset($_POST['vehicle_plate'])) {
      $vehicle_plate = $_POST['vehicle_plate'];
    }
    if(isset($_POST['warehouse_id'])) {
      $warehouse_id = $_POST['warehouse_id'];
    }
    if(isset($_POST['date_insert'])) {
      $tyre_storage_datein = $_POST['date_insert'];
    }
    if(isset($_POST['tyre_storage_note'])) {
      $tyre_storage_note = prepare_for_null_row(mysqli_real_escape_string($db_link,$_POST['tyre_storage_note']));
    }
    
    mysqli_query($db_link, "START TRANSACTION");
    
    $tyre_storage_state = 1; //first state take in the office
    $employer_took_tyres = $_SESSION['tyreslog']['user_id'];
    $tyre_storage_is_active = 1;
    $employer_returned_tyres = 0;
    
    $query_insert_ts = "INSERT INTO `tyres_storages`(
                                `tyre_storage_id`, 
                                `client_id`, 
                                `warehouse_id`, 
                                `vehicle_type_id`, 
                                `vehicle_make_id`, 
                                `vehicle_model_id`, 
                                `vehicle_plate`, 
                                `tyre_storage_state`, 
                                `tyre_storage_note`, 
                                `employer_took_tyres`, 
                                `tyre_storage_datein`, 
                                `tyre_storage_is_active`, 
                                `employer_returned_tyres`, 
                                `tyre_storage_dateout`)
                        VALUES ('',
                                '$client_id',
                                '$warehouse_id',
                                '$vehicle_type_id',
                                '$vehicle_make_id',
                                '$vehicle_model_id',
                                '$vehicle_plate',
                                '$tyre_storage_state',
                                $tyre_storage_note,
                                '$employer_took_tyres',
                                '$tyre_storage_datein',
                                '$tyre_storage_is_active',
                                '$employer_returned_tyres',
                                NULL)";
    $all_queries = $query_insert_ts."\n";
    //echo $query;exit;
    $result_insert_ts = mysqli_query($db_link, $query_insert_ts);
    if(!$result_insert_ts) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) <= 0) {
      echo $laguages[$default_lang]['sql_error_insert'];
      mysqli_query($db_link, "ROLLBACK");
      exit;
    }
    
    $tyre_storage_id = mysqli_insert_id($db_link);
    $tyre_storage_id_formatted = sprintf('%010d', $tyre_storage_id);
    
    //log
    //log_tyre_storage_action: 0 - create, 1 - edit, 2 - delete
    $query_log_action = "INSERT INTO `logs_tyres_storages_actions`(
                                              `ltsa_id`, 
                                              `user_id`, 
                                              `log_tyre_storage_date`, 
                                              `log_tyre_storage_action`) 
                                      VALUES ('',
                                              '$user_id',
                                              NOW(),
                                              '0')";
    $all_queries .= $query_log_action."\n";
    //echo $query;exit;
    $result_log_action = mysqli_query($db_link, $query_log_action);
    if(!$result_log_action) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) <= 0) {
      echo $laguages[$default_lang]['sql_error_insert'];
      mysqli_query($db_link, "ROLLBACK");
      exit;
    }
    
    $ltsa_id = mysqli_insert_id($db_link);
    
    $query_log_storage = "INSERT INTO `logs_tyres_storages`(
                                  `ltsa_id`, 
                                  `tyre_storage_id`, 
                                  `client_id`, 
                                  `warehouse_id`, 
                                  `vehicle_type_id`, 
                                  `vehicle_make_id`, 
                                  `vehicle_model_id`, 
                                  `vehicle_plate`, 
                                  `tyre_storage_state`, 
                                  `tyre_storage_note`, 
                                  `employer_took_tyres`, 
                                  `tyre_storage_datein`, 
                                  `tyre_storage_is_active`, 
                                  `employer_returned_tyres`, 
                                  `tyre_storage_dateout`)
                          VALUES ('$ltsa_id',
                                  '$tyre_storage_id',
                                  '$client_id',
                                  '$warehouse_id',
                                  '$vehicle_type_id',
                                  '$vehicle_make_id',
                                  '$vehicle_model_id',
                                  '$vehicle_plate',
                                  '$tyre_storage_state',
                                  $tyre_storage_note,
                                  '$employer_took_tyres',
                                  '$tyre_storage_datein',
                                  '$tyre_storage_is_active',
                                  '$employer_returned_tyres',
                                  NULL)";
    $all_queries .= $query_log_storage."\n";
    //echo $query;exit;
    $result_log_storage = mysqli_query($db_link, $query_log_storage);
    if(!$result_log_storage) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) <= 0) {
      echo $laguages[$default_lang]['sql_error_insert'];
      mysqli_query($db_link, "ROLLBACK");
      exit;
    }
    //log
    
    //echo $all_queries;mysqli_query($db_link, "ROLLBACK");exit;
    mysqli_query($db_link, "COMMIT");
    
  }
?>
<h1><?=$laguages[$default_lang]['tyre_reception'];?>:</h1>
<div id="wrapper">
  <!--<form name="warehouse_tyres_form" id="warehouse_tyres_form" method="post" action="index.php?current=take_conform" enctype="multipart/form-data">-->
  <form action="/protocols-takein-office" method="post" name="office_tyres_form" id="office_tyres_form">
<!--client_name-->
    <div class="form_row">
      <label><?=$laguages[$default_lang]['client_name_label'];?>:</label>
      <input type="text" name="client_name" id="client_name" class="input_text" style="margin-right:10px;" placeholder="напишете името на клиента">
      <select name="select_client" id="select_client" onchange="ChooseClientFromSelect(this.value)" style="margin-right:10px;">
        <option value="0"><?=$laguages[$default_lang]['choose_client_from_select'];?></option>
      <?php
        $query_clients = "SELECT `user_id` as client_id, CONCAT(`user_firstname`,' ',`user_lastname`) as client_name
                          FROM `users` 
                          WHERE `users`.`user_type_id` = '3'
                          ORDER BY client_name ASC";
        //echo $query_clients;
        $result_clients = mysqli_query($db_link, $query_clients);
        if(mysqli_num_rows($result_clients) > 0) {
          while($clients = mysqli_fetch_array($result_clients)) {

            $client_id = $clients['client_id'];
            $client_name = $clients['client_name'];
            
            echo "<option value='$client_id'>$client_name</option>";
          }
        }
      ?>
      </select>
      <a href="administration-manage-user" class="button add" target="_blank">
        <i class="icon_plus_sign"></i>
        <?=$laguages[$default_lang]['btn_add_new_client'];?>
      </a>
    </div>
    <input type="hidden" name="client_id" id="client_id" />
    <input type="hidden" name="client_error" id="client_error" value="<?=$laguages[$default_lang]['error_reception_protocol_client'];?>" />
<!--<hr>-->
    <hr>
<!--vehicle_type-->
    <!--<div class="form_row" style="display:none">-->
    <div class="form_row">
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
        $class_active = "";
        if($vehicle_type_id == 1) {
          $class_active = " active";
          $current_type = $vehicle_type_id;
        }

        echo "<a data-id='$vehicle_type_id' id='$vehicle_image_id' class='vehicle_type$class_active' title='$vehicle_type'>$vehicle_type</a>";
      }
    }
?>
        <input type="hidden" name="vehicle_type" id="vehicle_type_input" value="<?=$current_type;?>" />
      </div>
    </div>
<!--vehicle_make-->
    <div class="form_row">
      <label><?=$laguages[$default_lang]['vehicle_make_label'];?>:</label>
      <select id="vehicle_make" name="vehicle_make" onChange="LoadVehicleModelsForMakeInSelect()">
        <!--<option selected="selected"><?=$laguages[$default_lang]['choose_vehicle_type_first'];?></option>-->
<?php
      // get only car makes
      $query = "SELECT `vehicles_makes`.* 
                FROM `vehicles_makes` 
                WHERE `vehicle_make_id` IN(SELECT `vehicle_make_id` FROM `vehicles_makes_to_type` WHERE `vehicle_type_id` = '1')
                ORDER BY `vehicle_make` ASC";
      $result = mysqli_query($db_link, $query);
      if(mysqli_num_rows($result) > 0) {

        echo "<option value='0' selected='selected'>".$laguages[$default_lang]['choose_make']."</option>";
        while($vehicles_makes = mysqli_fetch_assoc($result)) {

          $vehicle_make_id = $vehicles_makes['vehicle_make_id'];
          $vehicle_make = $vehicles_makes['vehicle_make'];

          echo "<option value='$vehicle_make_id'>$vehicle_make</option>";
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
      <select id="vehicle_model_default">
        <option value="0" selected="selected"><?=$laguages[$default_lang]['choose_vehicle_make_first'];?></option>
      </select>
      <select id="vehicle_model" name="vehicle_model" style="display: none;">

      </select>
    </div>
<!--vehicle_plate-->
    <div class="form_row">
      <label><?=$laguages[$default_lang]['vehicle_plate_label'];?>:</label>
      <input type="hidden" name="vehicle_plate_error" id="vehicle_plate_error" value="<?=$laguages[$default_lang]['error_reception_protocol_vehicle_plate'];?>">
      <select id="vehicle_plate_select" name="vehicle_plate" style="display: none;">

      </select>
    </div>
<!--tyres_form-->
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
      <textarea name="tyre_storage_note" id="tyre_storage_note"></textarea>
    </div>
    <div class="form_row">
      <input type="submit" name="create_tyre_storage" id="create_tyre_storage" class="button" value="<?=$laguages[$default_lang]['btn_insert_protocol'];?>">
    </div>
  </form>
  <!--<div id="warehouse_tyres_form">-->
</div>
<script type="text/javascript">
$(document).ready(function() {
  $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
  
  $(".vehicle_type").click(function() {
    $(".vehicle_type").removeClass("active");
    $(this).addClass("active");
    $("#vehicle_type_input").val($(this).attr("data-id"));
    LoadVehicleMakesForTypeInSelect();
  });
    
  //autocomplete Client
  $( "#client_name" ).autocomplete({
    source: "protocols/ajax/get/get-clients-for-autocomplete.php",
    minLength: 2,
    select: function( event, ui ) {
      //alert(ui.item.client_id);
      $('#client_name').val(ui.item.client_name);
      $('#client_id').val(ui.item.client_id);
      $('#select_client').val("0");
      LoadVehiclePlatesForClient(ui.item.client_id);
    }
  });
});
</script>