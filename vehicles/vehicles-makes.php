<table class="margin_bottom">
  <thead>
    <tr>
      <td width="5%"><?=$laguages[$default_lang]['btn_save'];?></td>
      <td width="20%"><?=$laguages[$default_lang]['vehicle_makes_thead'];?></td>
      <td width="5%"><?=$laguages[$default_lang]['btn_delete']; ?></td>
      <td></td>
    </tr>
  </thead>
</table>
<?php
  $db_link = DB_OpenI();
  $query = "SELECT `vehicles_makes`.* FROM `vehicles_makes` ORDER BY `vehicle_make` ASC";
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    $key = 0;
    while($vehicles_makes = mysqli_fetch_assoc($result)) {
      
      $vehicle_make_id = $vehicles_makes['vehicle_make_id'];
      $vehicle_make = $vehicles_makes['vehicle_make'];
      $class = ((($key % 2) == 1) ? " even" : " odd");
?>
    <div id="vehicle_make<?php echo $vehicle_make_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="5%"><button class="button btn_save" onClick="EditVehicleMake('<?php echo $vehicle_make_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="20%"><input type="text" class="vehicle_make" value="<?php echo $vehicle_make;?>" /></td>
          <td width="5%" class="no_background"><button class="remove" onClick="DeleteVehicleMake('<?php echo $vehicle_make_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    $key++;
    } // while($vehicles_makes)
  } // if(mysqli_num_rows($result) > 0)
?>
<div id="add_new_vehicle_make">

</div>
<div id="add_vehicle_make" class="add_new_form">
  <h3><?=$laguages[$default_lang]['form_add_new'];?></h3>
  <table>
    <tr class="row_over">
      <td width="5%"><button class="button btn_save" onClick="AddVehicleMake()"><?=$laguages[$default_lang]['btn_save'];?></button></td>
      <td width="20%"><input type="text"  id="add_vehicle_make" /></td>
      <td></td>
    </tr>
  </table>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
    //$("#add_vehicle_make #add_vehicle_make").focus();
  });
</script>
