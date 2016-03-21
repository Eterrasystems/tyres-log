<h1><?=$laguages[$default_lang]['warehouses_scheme'];?></h1>
<?php
  if(!isset($_GET['tyre_storage_id'])) {
?>
<div id="wrapper">
  <label>Складове</label>
  <select id="warehouse_id<?php echo $user_id; ?>">
    <option value="0"><?=$laguages[$default_lang]['choose_warehouse'];?></option>
<?php
  $query_warehouses = "SELECT `warehouses_types`.`warehouse_type_name`,`warehouses`.`warehouse_id`, `warehouses`.`warehouse_name`
                      FROM `warehouses`
                      INNER JOIN `warehouses_types` ON `warehouses_types`.`warehouse_type_id` = `warehouses`.`warehouse_type_id`
                      ORDER BY `warehouses_types`.`warehouse_type_name` ASC, `warehouses`.`warehouse_name` ASC";
  $result_warehouses = mysqli_query($db_link, $query_warehouses);
  if (!$result_warehouses) echo mysqli_error($db_link);
  if(mysqli_num_rows($result_warehouses) > 0) {
    while($warehouse = mysqli_fetch_assoc($result_warehouses)) {
      
      $warehouse_type_name = $warehouse['warehouse_type_name'];
      $warehouse_id = $warehouse['warehouse_id'];
      $warehouse_name = $warehouse['warehouse_name'];

      echo "<option value='$warehouse_id'>$warehouse_type_name - $warehouse_name</option>";
    }
  }
?>
  </select>
  <div class="clearfix"></div>
</div>
<?php
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
});
</script>
<?php
  }
?>