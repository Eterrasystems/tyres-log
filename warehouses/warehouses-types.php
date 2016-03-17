<table class="margin_bottom">
  <thead>
    <tr>
      <td width="7%"><?=$laguages[$default_lang]['btn_save'];?></td>
      <td width="30%"><?=$laguages[$default_lang]['warehouse_types_thead'];?></td>
      <td width="7%"><?=$laguages[$default_lang]['btn_delete'];?></td>
      <td></td>
    </tr>
  </thead>
</table>
<?php
  $db_link = DB_OpenI();
  $query = "SELECT `warehouse_type_id`, `warehouse_type_name` FROM `warehouses_types` ORDER BY `warehouse_type_name` ASC";
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    $key = 0;
    while($warehouses_types = mysqli_fetch_assoc($result)) {
      
      $warehouse_type_id = $warehouses_types['warehouse_type_id'];
      $warehouse_type_name = stripslashes($warehouses_types['warehouse_type_name']);
      $class = ((($key % 2) == 1) ? " even" : " odd");
?>
    <div id="warehouse_type<?php echo $warehouse_type_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditWarehouseType('<?php echo $warehouse_type_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="warehouse_type_name" value="<?php echo $warehouse_type_name;?>" /></td>
          <td width="7%" class="no_background">
            <button class="remove" onclick="if(confirm('<?php echo $laguages[$default_lang]['warning_delete']." $warehouse_type_name?";?>')) DeleteWarehouseType('<?=$warehouse_type_id;?>');"><?=$laguages[$default_lang]['btn_delete'];?></button>
          </td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    $key++;
    } // while($warehouses_types)
  } // if(mysqli_num_rows($result) > 0)
?>
<div id="add_new_warehouse_type">

</div>
<div id="add_warehouse_type" class="add_new_form">
  <h3><?=$laguages[$default_lang]['form_add_new'];?></h3>
  <table>
    <tr class="row_over">
      <td width="7%"><button class="button btn_save" onClick="AddWarehouseType()"><?=$laguages[$default_lang]['btn_save'];?></button></td>
      <td width="30%"><input type="text"  id="add_warehouse_type_name" /></td>
      <td></td>
    </tr>
  </table>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
    $("#add_warehouse_type_name #add_warehouse_type_name").focus();
  });
</script>