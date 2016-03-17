<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['warehouse_type_id'])) {
    $warehouse_type_id = $_POST['warehouse_type_id'];
  }
  if(isset($_POST['warehouse_name'])) {
    $warehouse_name = mysqli_real_escape_string($db_link,$_POST['warehouse_name']);
  }
  if(isset($_POST['warehouse_address'])) {
    $warehouse_address = mysqli_real_escape_string($db_link,$_POST['warehouse_address']);
  }
  if(isset($_POST['warehouse_phone'])) {
    $warehouse_phone = $_POST['warehouse_phone'];
  }
  if(isset($_POST['warehouse_info'])) {
    $warehouse_info = prepare_for_null_row(mysqli_real_escape_string($db_link,$_POST['warehouse_info']));
  }
  
  if(!empty($warehouse_name)) {
    
    $query = "INSERT INTO `warehouses`(`warehouse_id`, `warehouse_type_id`, `warehouse_name`, `warehouse_address`, `warehouse_phone`, `warehouse_info`) 
                                VALUES ('','$warehouse_type_id','$warehouse_name','$warehouse_address','$warehouse_phone',$warehouse_info)";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) > 0) {
      $warehouse_id = mysqli_insert_id($db_link);
?>
    <div id="warehouse<?php echo $warehouse_id;?>" class="row_over">
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
    }
  }
  DB_CloseI($db_link);