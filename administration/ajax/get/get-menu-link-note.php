<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  if(isset($_POST['menu_id'])) {
    $menu_id = $_POST['menu_id'];
  }
  if(isset($_POST['language_id'])) {
    $language_id = $_POST['language_id'];
  }
  
  if(!empty($menu_id) && !empty($language_id)) {
    
    $query_menus_notes = "SELECT `menu_note` FROM `menus_notes` WHERE `menu_id` = '$menu_id' AND `language_id` = '$language_id'";
    //echo $query;
    $result_menus_notes = mysqli_query($db_link, $query_menus_notes);
    if (!$result_menus_notes) echo mysqli_error($db_link);
    if(mysqli_num_rows($result_menus_notes) > 0) {

      $row = mysqli_fetch_assoc($result_menus_notes);
      $menu_note = stripslashes($row['menu_note']);
   
?>
    <div id="menu_link_note<?php echo "$menu_id$language_id";?>" class="row_over">
      <table>
        <tr>
          <td width="5%"><button class="btn_save" onClick="EditMenuLinkNote('<?php echo "$menu_id";?>','<?php echo "$language_id";?>')">Save</button></td>
          <td width="90%">
            <textarea class="menu_link_note" style="width:96%;"><?php if (!empty($menu_note)) echo $menu_note; ?></textarea>
          </td>
          <td width="5%" class="no_backgound"><button class="remove" onClick="DeleteMenuLinkNote('<?php echo "$menu_id";?>','<?php echo "$language_id";?>')">Delete</button></td>
        </tr>
      </table>
    </div>
<?php
    }
    else {
?>
  <div class="add_new_form">
    <h3><?=$laguages[$default_lang]['form_add_new'];?></h3> 
    <table>
      <tr class="row_over">
        <td width="5%"><button class="btn_save" onClick="AddMenuLinkNote()"><?=$laguages[$default_lang]['btn_save'];?></button></td>
        <td width="90%">
          <textarea id="add_menu_link_note" style="width:96%;"></textarea>
        </td>
        <td></td>
      </tr>
    </table>
  </div>
<?php
    }
  }