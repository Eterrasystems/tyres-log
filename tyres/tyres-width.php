<table class="margin_bottom">
  <thead>
    <tr>
      <td width="7%"><?=$laguages[$default_lang]['btn_save'];?></td>
      <td width="30%"><?=$laguages[$default_lang]['tyre_width_thead'];?></td>
      <td width="7%"><?=$laguages[$default_lang]['order_thead']; ?></td>
      <td width="7%"><?=$laguages[$default_lang]['btn_delete']; ?></td>
      <td></td>
    </tr>
  </thead>
</table>
<?php
  $db_link = DB_OpenI();
  $query_tyre_width = "SELECT `tyre_width_id`,`tyre_width`,`tyre_width_order` FROM `tyres_width` ORDER BY `tyre_width_order` ASC";
  $result_tyre_width = mysqli_query($db_link, $query_tyre_width);
  if(mysqli_num_rows($result_tyre_width) > 0) {
    $key = 0;
    while($tyres_widths = mysqli_fetch_assoc($result_tyre_width)) {
      
      $tyre_width_id = $tyres_widths['tyre_width_id'];
      $tyre_width = $tyres_widths['tyre_width'];
      $tyre_width_order = $tyres_widths['tyre_width_order'];
      $class = ((($key % 2) == 1) ? " even" : " odd");
?>
    <div id="tyre_width<?php echo $tyre_width_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditTyreWidth('<?php echo $tyre_width_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="tyre_width" value="<?php echo $tyre_width;?>" /></td>
          <td width="7%"><input type="text" class="tyre_width_order" value="<?php echo $tyre_width_order;?>" /></td>
          <td width="7%" class="no_background"><button class="remove" onClick="DeleteTyreWidth('<?php echo $tyre_width_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    $key++;
    } // while($vehicles_makes)
  } // if(mysqli_num_rows($result_tyre_width) > 0)
?>
<div id="add_new_tyre_width">

</div>
<div id="add_tyre_width" class="add_new_form">
  <h3><?=$laguages[$default_lang]['form_add_new'];?></h3>
  <table>
    <tr class="row_over">
      <td width="7%"><button class="button btn_save" onClick="AddTyreWidth()"><?=$laguages[$default_lang]['btn_save'];?></button></td>
      <td width="30%"><input type="text"  id="add_tyre_width" /></td>
      <td width="7%"><input type="text"  id="add_tyre_width_order" /></td>
      <td></td>
    </tr>
  </table>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
    //$("#add_tyre_width #add_tyre_width").focus();
  });
</script>