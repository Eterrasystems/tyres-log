<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['warehouse_id'])) {
    $selected_warehouse_id = $_POST['warehouse_id'];
  }
?>

  <table class="no_margin">
    <thead>
      <tr>
        <td width="7%"><?=$laguages[$default_lang]['btn_save'];?></td>
        <td width="30%"><?=$laguages[$default_lang]['reception_protocol_thead'];?></td>
        <td width="30%"><?=$laguages[$default_lang]['warehouse_move_to_thead'];?></td>
        <td></td>
      </tr>
    </thead>
  </table>
  <div id="choose_">
<?php
  
  $all_warehouses = array();
  $query_warehouses = "SELECT `warehouses_types`.`warehouse_type_name`,`warehouses`.`warehouse_id`, `warehouses`.`warehouse_name`
                      FROM `warehouses`
                      INNER JOIN `warehouses_types` ON `warehouses_types`.`warehouse_type_id` = `warehouses`.`warehouse_type_id`
                      ORDER BY `warehouses_types`.`warehouse_type_name` ASC, `warehouses`.`warehouse_name` ASC";
  $result_warehouses = mysqli_query($db_link, $query_warehouses);
  if (!$result_warehouses) echo mysqli_error($db_link);
  if(mysqli_num_rows($result_warehouses) > 0) {
    while($row_warehouses = mysqli_fetch_assoc($result_warehouses)) {
      $all_warehouses[] = $row_warehouses;
    }
  }
  
  $current_date = date("Y-m-d");
  $expired_date = new DateTime($current_date);
  $expired_date->sub(new DateInterval('P8M'));
  $expired_date = $expired_date->format('Y-m-d');
  $query_tyre_storages = "SELECT `tyres_storages`.`tyre_storage_id`
                            FROM `tyres_storages`
                            INNER JOIN `warehouses` ON `warehouses`.`warehouse_id` = `tyres_storages`.`warehouse_id`
                            WHERE `warehouses`.`warehouse_id` = '$selected_warehouse_id' AND `tyres_storages`.`tyre_storage_date` >= '$expired_date'
                            ORDER BY `warehouses`.`warehouse_name` ASC";
  //echo $query_expired_tyres;
  $result_tyre_storages = mysqli_query($db_link, $query_tyre_storages);
  if(mysqli_num_rows($result_tyre_storages) > 0) {
    $key = 0;
    while($tyre_storages = mysqli_fetch_assoc($result_tyre_storages)) {

      $tyre_storage_id = $tyre_storages['tyre_storage_id'];
      //$warehouse_id = $tyre_storages['warehouse_id'];
      $class = ((($key % 2) == 1) ? " even" : " odd");

?>
    <div id="warehouse<?php echo $tyre_storage_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="MoveTyresFromWarehouseToWarehouse('<?=$tyre_storage_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><?=$tyre_storage_id;?></td>
          <td width="30%">
            <select class="warehouse_id">
              <option value="0"></option>
<?php
              foreach($all_warehouses as $warehouse) {

                $warehouse_type_name = $warehouse['warehouse_type_name'];
                $warehouse_id = $warehouse['warehouse_id'];
                $warehouse_name = $warehouse['warehouse_name'];

                if($selected_warehouse_id != $warehouse_id) {
                  echo "<option value='$warehouse_id'>$warehouse_type_name - $warehouse_name</option>";
                }
              }
?>
            </select>
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
    <div id="no_records"><?=$laguages[$default_lang]['no_warehouse_tyres_storage_yet'];?></div>
<?php    
  }
  
  DB_CloseI($db_link);
?>
  </div>