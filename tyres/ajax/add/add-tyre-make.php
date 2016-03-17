<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);exit;
  if(isset($_POST['tyre_make'])) {
    $tyre_make_capitalized = ucwords(strtolower($_POST['tyre_make']));
    $tyre_make = mysqli_real_escape_string($db_link,$tyre_make_capitalized);
  }
  
  if(!empty($tyre_make)) {
    
    $query = "INSERT INTO `tyres_makes`(`tyre_make_id`, `tyre_make`) 
                                    VALUES ('','$tyre_make')";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) > 0) {
      $tyre_make_id = mysqli_insert_id($db_link);
?>
    <div id="tyre_make<?php echo $tyre_make_id;?>" class="row_over">
      <table>
        <tr>
          <td width="7%"><button class="button btn_save" onClick="EditTyreMake('<?php echo $tyre_make_id;?>')"><?=$laguages[$default_lang]['btn_save'];?></button></td>
          <td width="30%"><input type="text" class="tyre_make" value="<?php echo $tyre_make_capitalized;?>" /></td>
          <td width="7%" class="no_background"><button class="remove" onClick="DeleteTyreMake('<?php echo $tyre_make_id;?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          <td class="no_background"></td>
        </tr>
      </table>
    </div>
<?php
    }
  }
  
  DB_CloseI($db_link);