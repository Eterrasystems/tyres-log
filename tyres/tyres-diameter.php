<table class="margin_bottom">
  <thead>
    <tr>
      <td width="7%"><?=$laguages[$default_lang]['btn_save'];?></td>
      <td width="30%"><?=$laguages[$default_lang]['tyre_diameter_thead'];?></td>
      <td width="7%"><?=$laguages[$default_lang]['order_thead']; ?></td>
      <td width="7%"><?=$laguages[$default_lang]['btn_delete']; ?></td>
      <td></td>
    </tr>
  </thead>
</table>
<?php
  $db_link = DB_OpenI();
  $query_tyre_diameter = "SELECT `tyre_diameter_id`,`tyre_diameter`,`tyre_diameter_order` FROM `tyres_diameter` ORDER BY `tyre_diameter_order` ASC";
  //echo $query_tyre_diameter;
  $result_tyre_diameter = mysqli_query($db_link, $query_tyre_diameter);
  if(mysqli_num_rows($result_tyre_diameter) > 0) {
    $key = 0;
    while($tyres_diameters = mysqli_fetch_assoc($result_tyre_diameter)) {
      
      $tyre_diameter_id = $tyres_diameters['tyre_diameter_id'];
      $tyre_diameter = $tyres_diameters['tyre_diameter'];
      $tyre_diameter_order = $tyres_diameters['tyre_diameter_order'];
      $class = ((($key % 2) == 1) ? " even" : " odd");
?>
    <div id="tyre_diameter<?php echo $tyre_diameter_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditTyreDiameter('<?php echo $tyre_diameter_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="tyre_diameter" value="<?php echo $tyre_diameter;?>" /></td>
          <td width="7%"><input type="text" class="tyre_diameter_order" value="<?php echo $tyre_diameter_order;?>" /></td>
          <td width="7%" class="no_background"><button class="remove" onClick="DeleteTyreDiameter('<?php echo $tyre_diameter_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    $key++;
    } // while($vehicles_makes)
  } // if(mysqli_num_rows($result_tyre_diameter) > 0)
?>
<div id="add_new_tyre_diameter">

</div>
<div id="add_tyre_diameter" class="add_new_form">
  <h3><?=$laguages[$default_lang]['form_add_new'];?></h3>
  <table>
    <tr class="row_over">
      <td width="7%"><button class="button btn_save" onClick="AddTyreDiameter()"><?=$laguages[$default_lang]['btn_save'];?></button></td>
      <td width="30%"><input type="text"  id="add_tyre_diameter" /></td>
      <td width="7%"><input type="text"  id="add_tyre_diameter_order" /></td>
      <td></td>
    </tr>
  </table>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
    //$("#add_tyre_diameter #add_tyre_diameter").focus();
  });
</script>