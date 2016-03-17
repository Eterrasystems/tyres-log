<table class="margin_bottom">
  <thead>
    <tr>
      <td width="5%"><?=$laguages[$default_lang]['btn_save'];?></td>
      <td width="20%"><?=$laguages[$default_lang]['vehicle_type_thead'];?></td>
      <td width="20%"><?=$laguages[$default_lang]['vehicle_image_id_thead'];?></td>
      <td width="5%"><?=$laguages[$default_lang]['btn_delete']; ?></td>
      <td></td>
    </tr>
  </thead>
</table>
<?php
  $db_link = DB_OpenI();
  $query = "SELECT `vehicles_types`.* FROM `vehicles_types` ORDER BY `vehicle_type` ASC";
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    $key = 0;
    while($vehicles_types = mysqli_fetch_assoc($result)) {
      
      $vehicle_type_id = $vehicles_types['vehicle_type_id'];
      $vehicle_type = $vehicles_types['vehicle_type'];
      $vehicle_type_name = $laguages[$default_lang][$vehicle_type];
      $vehicle_image_id = $vehicles_types['vehicle_image_id'];
      $class = ((($key % 2) == 1) ? " even" : " odd");
?>
    <div id="vehicle_type<?php echo $vehicle_type_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="5%"><button class="button btn_save" onClick="EditVehicleType('<?php echo $vehicle_type_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="20%"><input type="text" class="vehicle_type" value="<?php echo $vehicle_type_name;?>" /></td>
          <td width="20%"><input type="text" class="vehicle_image_id" value="<?php echo $vehicle_image_id;?>" /></td>
          <td width="5%" class="no_background"><button class="remove" onClick="DeleteVehicleType('<?php echo $vehicle_type_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    $key++;
    }
  }
?>
<div id="add_new_vehicle_type">

</div>
<div id="add_vehicle_type" class="add_new_form">
  <h3><?=$laguages[$default_lang]['form_add_new'];?></h3>
  <table>
    <tr class="row_over">
      <td width="5%"><button class="button btn_save" onClick="AddVehicleType()"><?=$laguages[$default_lang]['btn_save'];?></button></td>
      <td width="20%"><input type="text"  id="add_vehicle_type" /></td>
      <td width="20%"><input type="text"  id="add_vehicle_image_id" /></td>
      <td></td>
    </tr>
  </table>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
    $("#add_vehicle_type #add_vehicle_type").focus();
  });
</script>
