<menu id="menu">
  <ul>
<?php
  $active_parent = NULL;
  $active_parent_id = NULL;
  $user_id = $_SESSION['tyreslog']['user_id'];
  $query_menu = "SELECT `menus`.*, `users_rights`.*
                  FROM `menus`
                  INNER JOIN `users_rights` ON `users_rights`.`menu_id` = `menus`.`menu_id`
                  WHERE `users_rights`.`user_id` = '$user_id' AND `menu_level` = '0' AND `menu_active` = '1' 
                  ORDER BY `menu_sort` ASC";
  //echo $query;
  $result_menu = mysqli_query($db_link, $query_menu);
  if(!$result_menu) echo mysqli_error($db_link);
  $menu_count = mysqli_num_rows($result_menu);
  if($menu_count > 0) {
    for($i = 0; $i < $menu_count; $i++) {
      $menu_row = mysqli_fetch_assoc($result_menu);
      $menu_friendly_url = rawurlencode($menu_row['menu_friendly_url']);
      //$cyr_url = str_replace(array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ь', 'ъ', 'ы', 'э', 'ю', 'я'), array('a', 'b', 'v', 'g', 'd', 'e', 'jo', 'zh', 'z', 'i', 'i', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sch', 'j', 'j', 'y', 'e', 'y', 'ya'), $menu_friendly_url);
      $menu_path_name = $menu_row['menu_path_name'];
      $menu_name = $menu_row['menu_name'];
      $menu_name = $laguages[$default_lang][$menu_name];
//      $menu_name = mb_strtoupper($menu_row['menu_name'], 'UTF-8');
      $user_access_sha1 = sha1($menu_row['menu_id']);
      $users_rights_edit = $menu_row['users_rights_edit'];
      $users_rights_delete = $menu_row['users_rights_delete'];
      $hidden_input = "";
      if(!empty($menu_row['menu_id'])) {
        $item_img = $menu_row['menu_image_url'];
        
        if($current_page == "welcome") {
          if(!empty($item_img)) $item_img .= "<img src='_index.png' alt='$menu_name' >";
          else $item_img = "";
        }
        else {
          if(!empty($item_img)) $item_img .= "<img src='_pages.png' alt='$menu_name' >";
          else $item_img = "";
        }
        //echo "$current_page - $menu_path_name<br>";
        echo '<li';
        if (strstr($current_page, $menu_path_name)) {
          $active_parent = $menu_path_name;
          $active_parent_id = $menu_row['menu_id'];
          echo " class=\"selected\"";
          $item_img .= (empty($item_img)) ? "" : "_active";
          $hidden_input = '<input type="hidden" id="active_first_level" value="'.$menu_name.'">';
          $class = 'class="active_first_level"';
        }
        else $class = "";
        echo '>';
        echo "<a $class users-rights-access='$user_access_sha1' user-access-edit='$users_rights_edit' user-access-delete='$users_rights_delete' href='$menu_friendly_url'>$item_img$menu_name</a>";
        echo $hidden_input.'</li>';
      }
    }
    mysqli_free_result($result_menu);
  }
?>
    <section class="clearfix"></section>
  </ul>
</menu>
<!-- sub menus -->
<?php if (!empty($active_parent)) { ?>
    <ul id="<?php echo $active_parent; ?>" class="second_menu" data="<?php echo $active_parent; ?>">
<?php list_menu_in_uls($active_parent_id, $_SESSION['tyreslog']['user_id'],$current_page);  ?>
    </ul>
<?php } ?>