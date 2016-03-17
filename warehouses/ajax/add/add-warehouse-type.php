<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['warehouse_type_name'])) {
    $warehouse_type_name = mysqli_real_escape_string($db_link,$_POST['warehouse_type_name']);
  }
  
  if(!empty($warehouse_type_name)) {
    
    $query = "INSERT INTO `warehouses_types`(`warehouse_type_id`, `warehouse_type_name`) 
                                      VALUES ('','$warehouse_type_name')";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) > 0) {
      $warehouse_type_id = mysqli_insert_id($db_link);
?>
    <div id="warehouse_type<?php echo $warehouse_type_id;?>" class="row_over">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditWarehouseType('<?php echo $warehouse_type_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="warehouse_type_name" value="<?php echo $_POST['warehouse_type_name'];?>" /></td>
          <td width="7%" class="no_background"><button class="remove" onClick="DeleteWarehouseType('<?php echo $warehouse_type_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    }
  }
  DB_CloseI($db_link);