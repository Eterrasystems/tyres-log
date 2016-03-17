<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['tyre_make_id'])) {
    $selected_tyre_make_id = $_POST['tyre_make_id'];
  }
  if(isset($_POST['tyre_model'])) {
    $tyre_model_capitalized = ucwords(strtolower($_POST['tyre_model']));
    $tyre_model = mysqli_real_escape_string($db_link,$tyre_model_capitalized);
  }
  
  if(!empty($tyre_model)) {
    
    $query = "INSERT INTO `tyres_models`(`tyre_model_id`, `tyre_make_id`, `tyre_model`) 
                                    VALUES ('','$selected_tyre_make_id','$tyre_model')";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) > 0) {
      $tyre_model_id = mysqli_insert_id($db_link);
?>
    <div id="tyre_model<?php echo $tyre_model_id;?>" class="row_over">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditTyreModel('<?php echo $tyre_model_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="tyre_model" value="<?php echo $tyre_model_capitalized;?>" /></td>
          <td width="7%" class="no_background"><button class="remove" onClick="DeleteTyreModel('<?php echo $tyre_model_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    }
  }
  
  DB_CloseI($db_link);