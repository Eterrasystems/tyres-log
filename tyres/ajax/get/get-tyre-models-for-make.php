<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_make_id'])) {
    $selected_tyre_make_id = $_POST['tyre_make_id'];
  }
?>
  <table class="no_margin">
    <thead>
      <tr>
        <td width="7%"><?=$laguages[$default_lang]['btn_save'];?></td>
        <td width="30%"><?=$laguages[$default_lang]['tyre_models_thead'];?></td>
        <td width="10%"><?=$laguages[$default_lang]['btn_inlude'];?></td>
        <td></td>
      </tr>
    </thead>
  </table>
  <div id="choose_tyre_make">
<?php
  $query = "SELECT `tyre_model_id`, `tyre_model` FROM `tyres_models` WHERE `tyre_make_id` = '$selected_tyre_make_id' ORDER BY `tyre_model` ASC";
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    $key = 0;
    while($tyres_models = mysqli_fetch_assoc($result)) {
      
      $tyre_model_id = $tyres_models['tyre_model_id'];
      $tyre_model = $tyres_models['tyre_model'];
      $class = ((($key % 2) == 1) ? " even" : " odd");
?>
    <div id="tyre_model<?php echo $tyre_model_id;?>" class="row_over<?php echo $class;?>">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditTyreModel('<?php echo $tyre_model_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="tyre_model" value="<?php echo $tyre_model;?>" /></td>
          <td width="7%" class="no_background"><button class="remove" onClick="DeleteTyreModel('<?php echo $tyre_model_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    $key++;
    }
  }
  else {   
?>
    <div id="no_records"><?=$laguages[$default_lang]['no_tyre_models_yet'];?></div>
<?php    
  }
  
  DB_CloseI($db_link);
?>
  </div>
  <div id="add_new_tyre_model">

  </div>
  <div id="add_tyre_model" class="add_new_form">
    <h3><?=$laguages[$default_lang]['form_add_new'];?></h3>
    <table>
      <tr class="row_over">
        <td width="7%"><button class="button btn_save" onClick="AddTyreModel()"><?=$laguages[$default_lang]['btn_save'];?></button></td>
        <td width="30%"><input type="text"  id="add_tyre_model" /></td>
        <td></td>
      </tr>
    </table>
  </div>