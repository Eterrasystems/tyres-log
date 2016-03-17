<table class="margin_bottom">
  <thead>
    <tr>
      <td width="7%"><?=$laguages[$default_lang]['btn_save'];?></td>
      <td width="30%"><?=$laguages[$default_lang]['tyre_speed_index_thead'];?></td>
      <td width="7%"><?=$laguages[$default_lang]['order_thead']; ?></td>
      <td width="7%"><?=$laguages[$default_lang]['btn_delete']; ?></td>
      <td></td>
    </tr>
  </thead>
</table>
<?php
  $db_link = DB_OpenI();
  $query_tyre_speed_index = "SELECT `tyre_speed_index_id`,`tyre_speed_index`,`tyre_speed_index_order` FROM `tyres_speed_index` ORDER BY `tyre_speed_index_order` ASC";
  //echo $query_tyre_speed_index;
  $result_tyre_speed_index = mysqli_query($db_link, $query_tyre_speed_index);
  if(mysqli_num_rows($result_tyre_speed_index) > 0) {
    $key = 0;
    while($tyres_speed_indexs = mysqli_fetch_assoc($result_tyre_speed_index)) {
      
      $tyre_speed_index_id = $tyres_speed_indexs['tyre_speed_index_id'];
      $tyre_speed_index = $tyres_speed_indexs['tyre_speed_index'];
      $tyre_speed_index_order = $tyres_speed_indexs['tyre_speed_index_order'];
      $class = ((($key % 2) == 1) ? " even" : " odd");
?>
    <div id="tyre_speed_index<?php echo $tyre_speed_index_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditTyresSpeedIndex('<?php echo $tyre_speed_index_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="tyre_speed_index" value="<?php echo $tyre_speed_index;?>" /></td>
          <td width="7%"><input type="text" class="tyre_speed_index_order" value="<?php echo $tyre_speed_index_order;?>" /></td>
          <td width="7%" class="no_background"><button class="remove" onClick="DeleteTyreSpeedIndex('<?php echo $tyre_speed_index_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    $key++;
    } // while($vehicles_makes)
  } // if(mysqli_num_rows($result_tyre_speed_index) > 0)
?>
<div id="add_new_tyre_speed_index">

</div>
<div id="add_tyre_speed_index" class="add_new_form">
  <h3><?=$laguages[$default_lang]['form_add_new'];?></h3>
  <table>
    <tr class="row_over">
      <td width="7%"><button class="button btn_save" onClick="AddTyreSpeedIndex()"><?=$laguages[$default_lang]['btn_save'];?></button></td>
      <td width="30%"><input type="text"  id="add_tyre_speed_index" /></td>
      <td width="7%"><input type="text"  id="add_tyre_speed_index_order" /></td>
      <td></td>
    </tr>
  </table>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
    //$("#add_tyre_speed_index #add_tyre_speed_index").focus();
  });
</script>