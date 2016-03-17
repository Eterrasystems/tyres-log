<table class="margin_bottom">
  <thead>
    <tr>
      <td width="7%"><?=$laguages[$default_lang]['btn_save'];?></td>
      <td width="30%"><?=$laguages[$default_lang]['tyre_makes_thead'];?></td>
      <td width="7%"><?=$laguages[$default_lang]['btn_delete']; ?></td>
      <td></td>
    </tr>
  </thead>
</table>
<?php
  $db_link = DB_OpenI();
  $query = "SELECT `tyre_make_id`,`tyre_make` FROM `tyres_makes` ORDER BY `tyre_make` ASC";
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    $key = 0;
    while($vehicles_makes = mysqli_fetch_assoc($result)) {
      
      $tyre_make_id = $vehicles_makes['tyre_make_id'];
      $tyre_make = $vehicles_makes['tyre_make'];
      $class = ((($key % 2) == 1) ? " even" : " odd");
?>
    <div id="tyre_make<?php echo $tyre_make_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditTyreMake('<?php echo $tyre_make_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="tyre_make" value="<?php echo $tyre_make;?>" /></td>
          <td width="7%" class="no_background"><button class="remove" onClick="DeleteTyreMake('<?php echo $tyre_make_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    $key++;
    } // while($vehicles_makes)
  } // if(mysqli_num_rows($result) > 0)
?>
<div id="add_new_tyre_make">

</div>
<div id="add_tyre_make" class="add_new_form">
  <h3><?=$laguages[$default_lang]['form_add_new'];?></h3>
  <table>
    <tr class="row_over">
      <td width="7%"><button class="button btn_save" onClick="AddTyreMake()"><?=$laguages[$default_lang]['btn_save'];?></button></td>
      <td width="30%"><input type="text"  id="add_tyre_make" /></td>
      <td></td>
    </tr>
  </table>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
    //$("#add_tyre_make #add_tyre_make").focus();
  });
</script>
