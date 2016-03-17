<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['warehouse_type_id'])) {
    $warehouse_type_id = $_POST['warehouse_type_id'];
  }
?>

  <table class="no_margin">
    <thead>
      <tr>
        <td width="7%"><?=$laguages[$default_lang]['btn_save'];?></td>
        <td width="23%"><?=$laguages[$default_lang]['warehouse_name_thead'];?></td>
        <td width="25%"><?=$laguages[$default_lang]['warehouse_address_thead'];?></td>
        <td width="15%"><?=$laguages[$default_lang]['warehouse_phone_thead'];?></td>
        <td width="25%"><?=$laguages[$default_lang]['warehouse_info_thead'];?></td>
        <td width="5%"><?=$laguages[$default_lang]['btn_delete']; ?></td>
      </tr>
    </thead>
  </table>
  <div id="choose_vehicle_type">
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
  
  $query = "SELECT `warehouses`.* 
            FROM `warehouses` 
            WHERE `warehouse_type_id` = '$warehouse_type_id'
            ORDER BY `warehouses`.`warehouse_name` ASC";
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    $key = 0;
    while($warehouses = mysqli_fetch_assoc($result)) {

      $warehouse_id = $warehouses['warehouse_id'];
      $warehouse_name = stripcslashes($warehouses['warehouse_name']);
      $warehouse_address = stripcslashes($warehouses['warehouse_address']);
      $warehouse_info = stripcslashes($warehouses['warehouse_info']);
      $warehouse_phone = $warehouses['warehouse_phone'];
      $class = ((($key % 2) == 1) ? " even" : " odd");

?>
    <div id="warehouse<?php echo $warehouse_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditWarehouse('<?php echo $warehouse_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="23%"><input type="text" class="warehouse_name" value="<?php echo $warehouse_name;?>" /></td>
          <td width="25%"><input type="text" class="warehouse_address" value="<?php echo $warehouse_address;?>" /></td>
          <td width="15%"><input type="text" class="warehouse_phone" value="<?php echo $warehouse_phone;?>" /></td>
          <td width="25%"><input type="text" class="warehouse_info" value="<?php echo $warehouse_info;?>" /></td>
          <td width="5%"><button class="remove" onClick="DeleteWarehouse('<?php echo $warehouse_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
        </tr>
      </table>
    </div>
<?php
      $key++;
    }
  }
  else {   
?>
    <div id="no_records"><?=$laguages[$default_lang]['no_warehouses_types_yet'];?></div>
<?php    
  }
  
  DB_CloseI($db_link);
?>
  </div>