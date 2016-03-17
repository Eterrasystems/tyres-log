<table class="margin_bottom">
  <thead>
    <tr>
      <td width="7%"><?=$laguages[$default_lang]['btn_save'];?></td>
      <td width="30%"><?=$laguages[$default_lang]['tyre_ratio_thead'];?></td>
      <td width="7%"><?=$laguages[$default_lang]['order_thead']; ?></td>
      <td width="7%"><?=$laguages[$default_lang]['btn_delete']; ?></td>
      <td></td>
    </tr>
  </thead>
</table>
<?php
  $db_link = DB_OpenI();
  $query_tyre_ratio = "SELECT `tyre_ratio_id`,`tyre_ratio`,`tyre_ratio_order` FROM `tyres_ratio` ORDER BY `tyre_ratio_order` ASC";
  //echo $query_tyre_ratio;
  $result_tyre_ratio = mysqli_query($db_link, $query_tyre_ratio);
  if(mysqli_num_rows($result_tyre_ratio) > 0) {
    $key = 0;
    while($tyres_ratios = mysqli_fetch_assoc($result_tyre_ratio)) {
      
      $tyre_ratio_id = $tyres_ratios['tyre_ratio_id'];
      $tyre_ratio = $tyres_ratios['tyre_ratio'];
      $tyre_ratio_order = $tyres_ratios['tyre_ratio_order'];
      $class = ((($key % 2) == 1) ? " even" : " odd");
?>
    <div id="tyre_ratio<?php echo $tyre_ratio_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditTyreRatio('<?php echo $tyre_ratio_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="tyre_ratio" value="<?php echo $tyre_ratio;?>" /></td>
          <td width="7%"><input type="text" class="tyre_ratio_order" value="<?php echo $tyre_ratio_order;?>" /></td>
          <td width="7%" class="no_background"><button class="remove" onClick="DeleteTyreRatio('<?php echo $tyre_ratio_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    $key++;
    } // while($vehicles_makes)
  } // if(mysqli_num_rows($result_tyre_ratio) > 0)
?>
<div id="add_new_tyre_ratio">

</div>
<div id="add_tyre_ratio" class="add_new_form">
  <h3><?=$laguages[$default_lang]['form_add_new'];?></h3>
  <table>
    <tr class="row_over">
      <td width="7%"><button class="button btn_save" onClick="AddTyreRatio()"><?=$laguages[$default_lang]['btn_save'];?></button></td>
      <td width="30%"><input type="text"  id="add_tyre_ratio" /></td>
      <td width="7%"><input type="text"  id="add_tyre_ratio_order" /></td>
      <td></td>
    </tr>
  </table>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
    //$("#add_tyre_ratio #add_tyre_ratio").focus();
  });
</script>