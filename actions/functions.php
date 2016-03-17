<?php

function prepare_for_null_row($value) {

    if (empty($value) || is_null($value))
        $value = "NULL";
    else
        $value = "'$value'";

    return $value;
}

function generateRandomString($number_letters) {
    $rand_string = "";
    $charecters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_+()[]*&^";
    for ($i = 0; $i < $number_letters; $i++) {
        $randInt = mt_rand(0, 71);
        $rand_char = $charecters[$randInt];
        $rand_string .= $rand_char;
    }
    return $rand_string;
}

function generate_bcrypt_salt() {
    $rand_string = "";
    $charecters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789./";
    for ($i = 0; $i < 22; $i++) {
        $randInt = mt_rand(0, 63);
        $rand_char = $charecters[$randInt];
        $rand_string .= $rand_char;
    }
    return $rand_string;
}

function generate_captcha() {
  
  global $db_link;
    
  unset($_SESSION['tyreslog']['captcha123']);
  $_SESSION['tyreslog']['captcha123'] = array();
  $rnd = rand(1,99);
  $query = "SELECT * FROM `captchas` LIMIT $rnd,1";
  //echo $query;
  $result = mysqli_query($db_link, $query);
  if (!$result) echo mysqli_error($db_link);
  if(mysqli_num_rows($result)>0){

    $captcha = mysqli_fetch_assoc($result);
    $_SESSION['tyreslog']['captcha123']['img'] = $captcha['captcha_image'];
    $_SESSION['tyreslog']['captcha123']['code'] = $captcha['captcha_number'];

  }
}

function list_user_menu_rights($menu_id, $user_id) {
    global $db_link;
    global $laguages;
    global $default_lang;
    $query_menus = "SELECT `menus`.*, `users_rights`.`users_rights_id`, `users_rights`.`user_id`, `users_rights`.`menu_id` as `user_access_to_menu`, 
                          `users_rights`.`users_rights_edit`, `users_rights`.`users_rights_delete`
                    FROM `menus`
                    LEFT JOIN (`users_rights`) ON (`users_rights`.`user_id` = '$user_id' AND `users_rights`.`menu_id` = `menus`.`menu_id`)
                    WHERE `menus`.`menu_parent_id` = '$menu_id' AND `menu_active` = 1 
                    ORDER BY `menu_sort`";
    //echo $query_menus."<br>";
    $result_menus = mysqli_query($db_link, $query_menus);
    if(!$result_menus) echo mysqli_error($db_link);
    if (mysqli_num_rows($result_menus) > 0) {
      $i = -1;
      while ($menus = mysqli_fetch_assoc($result_menus)) {
          $i++;
          $class = ((($i % 2) == 1) ? " even" : " odd");
          $details_btn = "";
          $menu_id = $menus['menu_id'];
          $menu_name = $menus['menu_name'];
          $menu_name = $laguages[$default_lang][$menu_name];
          $menu_level = $menus['menu_level'];
          $menu_has_children = $menus['menu_has_children'];
          $user_access_to_menu = $menus['user_access_to_menu'];
          $users_rights_edit = $menus['users_rights_edit'];
          $users_rights_delete = $menus['users_rights_delete'];
          if($menu_level == 0 && $menu_has_children == 1) {
            $_SESSION['tyreslog']['first_level_id'] = $menu_id;
            $details_btn = '<button class="menu_header button" button-id="'.$_SESSION['tyreslog']['first_level_id'].'">+</button>';
          }
          $class_menu_level = ($menu_level == 0) ? "" : " children children".$_SESSION['tyreslog']['first_level_id'];
?>
          <tr class="page<?php echo "$menu_id $class$class_menu_level";?>">
            <td>&nbsp;</td>
            <td style="text-align: left;">
<?php
          if ($menu_level == 1) echo "&nbsp; - $menu_name";
          if ($menu_level == 2) echo "&nbsp;&nbsp; - - $menu_name";
          if ($menu_level == 3) echo "&nbsp;&nbsp;&nbsp; - - - $menu_name";
?>
            </td>
            <td>
              <div class="checkbox<?php echo (!empty($user_access_to_menu) ? " checkbox_checked" : NULL);?>">
                <input type="checkbox" name="access" onclick="Checkbox(this);" <?php echo (!empty($user_access_to_menu) ? " checked = 'checked'" : NULL);?> value="<?=$menu_id;?>" />
              </div>
            </td>
            <td>
              <div class="checkbox<?php echo (!empty($users_rights_edit) ? " checkbox_checked" : NULL);?>">
                <input type="checkbox" name="rights" onclick="Checkbox(this);" <?php echo (!empty($users_rights_edit) ? " checked = 'checked'" : NULL);?> value="edit" />
              </div>
            </td>
            <td>
              <div class="checkbox<?php echo (!empty($users_rights_delete) ? " checkbox_checked" : NULL);?>">
                <input type="checkbox" name="rights" onclick="Checkbox(this);" <?php echo (!empty($users_rights_delete) ? " checked = 'checked'" : NULL);?> value="delete" />
              </div>
            </td>
            <td><?=$details_btn;?></td>
            <td>&nbsp;</td>
          </tr>
<?php
          list_user_menu_rights($menu_id, $user_id);
      }
      mysqli_free_result($result_menus);
    }
}
// function list_user_menu_rights($menu_id)
// list MENU END for User Access Rights

function get_user_rights($user_id) {

    global $db_link;
    global $laguages;
    global $default_lang;
    $query_users_rights = "SELECT `users_rights_id`, `user_id`, `menu_id`,`users_rights_edit`, `users_rights_delete`
                          FROM `users_rights` 
                          WHERE `user_id` = '$user_id'";
    $result_users_rights = mysqli_query($db_link, $query_users_rights);
    if(!$result_users_rights) echo mysqli_error($db_link);
    if (mysqli_num_rows($result_users_rights) > 0) {
?>
        <table>
          <thead>
            <tr>
              <td width="5%"></td>
              <td width="20%"><?=$laguages[$default_lang]['user_rights_page_thead'];?></td>
              <td width="20%"><?=$laguages[$default_lang]['user_rights_page_access_thead'];?></td>
              <td width="20%"><?=$laguages[$default_lang]['user_rights_page_edit_thead'];?></td>
              <td width="15%"><?=$laguages[$default_lang]['user_rights_page_delete_thead'];?></td>
              <td width="15%"><?=$laguages[$default_lang]['user_rights_page_subpages_thead'];?></td>
              <td width="5%"></td>
            </tr>
          </thead>
<?php list_user_menu_rights(0, $user_id);?>
        </table>
<?php
        mysqli_free_result($result_users_rights);
    }
}

// list GROUP MENU START
function listMenuGroup($theId, $theType, $theDepartment) {
    global $db_link;
    global $laguages;
    global $default_lang;
    $menuResult = mysqli_query($db_link, "SELECT `menu_id`, `menu_parent_id`, `menu_level`, `menu_name`,
    `menu_url`, `menu_friendly_url`, `menu_image_url`, `menu_sort`, `menu_active`,
    `user_company_type_id`,`user_department_id`, `users_rights_groups_access`, `users_rights_groups_edit`, `users_rights_groups_delete`
    FROM `menus`
    LEFT JOIN (`users_rights_groups`) ON (`users_rights_groups`.`user_company_type_id` = " . $theType . " AND `users_rights_groups`.`user_department_id` = ".$theDepartment." AND `users_rights_groups`.`users_rights_groups_access` = `menus`.`menu_id`) 
    WHERE `menu_parent_id` = " . $theId . " AND `menu_active` = 1 ORDER BY `menu_sort`");
    if ($menuResult) {

        $i = -1;
        while ($menuRow = mysqli_fetch_assoc($menuResult)) {
            $i++;
            $menu_name = $menuRow['menu_name'];
            $menu_name = $laguages[$default_lang][$menu_name];
            $class = ((($i % 2) == 1) ? " even" : " odd");
            $details_btn = "";
            if($menuRow['menu_level'] == 0) {
              $_SESSION['tyreslog']['first_level_id'] = $menuRow['menu_id'];
              $details_btn = '<button class="menu_header btn" button-id="'.$_SESSION['tyreslog']['first_level_id'].'">+</button>';
            }
            $class_menu_level = ($menuRow['menu_level'] == 0) ? "" : " children children".$_SESSION['tyreslog']['first_level_id'];
            echo "<tr class=\"page" . $menuRow['menu_id'] . "$class$class_menu_level\">";
            echo "<td>&nbsp;</td>\n";
            echo "<td style=\"text-align: left;\">";
            if ($menuRow['menu_level'] == 1)
                echo " - ";
            if ($menuRow['menu_level'] == 2)
                echo " - - ";
             if ($menuRow['menu_level'] == 3)
                echo " - - - ";
            echo $menu_name;
            echo ": </td>\n";
            echo "<td>\n";
            echo "<div class = \"checkbox" . (!empty($menuRow['users_rights_groups_access']) ? " checkbox_checked" : NULL) . "\">\n";
            echo "<input type = \"checkbox\" name=\"access\" " . (!empty($menuRow['users_rights_groups_access']) ? " checked = \"checked\"" : NULL) . " value=\"" . $menuRow['menu_id'] . "\">\n";
            echo "</div>\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "<div class = \"checkbox" . (!empty($menuRow['users_rights_groups_edit']) ? " checkbox_checked" : NULL) . "\">\n";
            echo "<input type = \"checkbox\" name=\"rights\" " . (!empty($menuRow['users_rights_groups_edit']) ? " checked = \"checked\"" : NULL) . " value=\"edit\">\n";
            echo "</div>\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "<div class = \"checkbox" . (!empty($menuRow['users_rights_groups_delete']) ? " checkbox_checked" : NULL) . "\">\n";
            echo "<input type = \"checkbox\" name=\"rights\" " . (!empty($menuRow['users_rights_groups_delete']) ? " checked = \"checked\"" : NULL) . " value=\"delete\">\n";
            echo "</div>\n";
            echo "</td>\n";
            echo "<td>$details_btn</td>\n";
            echo "<td>&nbsp;</td>\n";
            echo "</tr>\n";
            listMenuGroup($menuRow['menu_id'], $theType, $theDepartment);
        }// while ( $menuRow = mysqli_fetch_assoc($menuResult) )
        mysqli_free_result($menuResult);
    } else {// if ($menuResult)
        return mysqli_error($db_link);
    }// if ($menuResult)
}

// function listMenuGroup($theId)
// list GROUP MENU END

function groupAccessBy($theType,$theDepartment) {
  global $laguages;
  global $default_lang;
?>
    <table>
      <thead>
        <tr>
          <td width="5%"></td>
          <td width="20%"><?=$laguages[$default_lang]['user_rights_page_thead'];?></td>
          <td width="20%"><?=$laguages[$default_lang]['user_rights_page_access_thead'];?></td>
          <td width="20%"><?=$laguages[$default_lang]['user_rights_page_edit_thead'];?></td>
          <td width="15%"><?=$laguages[$default_lang]['user_rights_page_delete_thead'];?></td>
          <td width="15%"><?=$laguages[$default_lang]['user_rights_page_subpages_thead'];?></td>
          <td width="5%"></td>
        </tr>
      </thead>
    <?php listMenuGroup(0, $theType, $theDepartment);?>
    </table>
<?php
}

function list_menu($menu_id, $main_path_number) {
    
  global $db_link;
  global $first_iteration;
  global $laguages;
  global $default_lang;

  if($first_iteration) {
    $query_menus = "SELECT `menus`.* FROM `menus` WHERE `menu_id` = '$menu_id' ORDER BY `menu_sort` ASC";
  }
  else {
    $query_menus = "SELECT `menus`.* FROM `menus` WHERE `menu_parent_id` = '$menu_id' ORDER BY `menu_sort` ASC";
  }
  $first_iteration = false;
  $result_menus = mysqli_query($db_link,$query_menus);
  if (!$result_menus) echo mysqli_error($db_link);
  if(mysqli_num_rows($result_menus) > 0) {

    while ($menus = mysqli_fetch_assoc($result_menus)) {

      $menu_id = $menus['menu_id'];
      $menu_parent_id = $menus['menu_parent_id'];
      $menu_path_name = $menus['menu_path_name'];
      $menu_name = stripslashes($menus['menu_name']);
      $menu_url = $menus['menu_url'];
      $menu_level = $menus['menu_level'];
      $menu_has_children = $menus['menu_has_children'];
      $menu_friendly_url = $menus['menu_friendly_url'];
      $menu_image_url = $menus['menu_image_url'];
      $menu_sort = $menus['menu_sort'];
      $menu_active = $menus['menu_active'];
      if ($menu_level == 0) {
          $main_path_number++;
          $main_option_path_number = $main_path_number;
      } else {
          $main_option_path_number = "&nbsp;$main_path_number.$menu_sort";
      }
      ?>
      <div id="menu<?php echo $menu_id; ?>" class="row_over menu_level_<?php echo $menu_level; ?>">
        <table>
          <tr>
            <td width="5%">
              <button class="btn_save" onClick="EditMenuLink('<?php echo $menu_id; ?>')">
                <?=$laguages[$default_lang]['btn_save'];?>
              </button>
            </td>
            <td width="5%"><b><?php echo $main_option_path_number; ?></b></td>
            <td width="10%"><input type="text" name="menu_name" class="menu_name" value="<?php echo $menu_name; ?>" /></td>
            <td width="15%">
              <select class="menu_parent_id">
                <option value="0"></option>
                <?php list_menu_for_select_a_parent($parent_id = 0, $path_number = 0, $menu_parent_id); ?>
              </select>
            </td>
            <td width="5%">
              <div class="checkbox<?php if ($menu_has_children == 1) echo ' checkbox_checked'; ?>">
                <input type="checkbox" name="menu_has_children" class="menu_has_children" onClick="Checkbox(this)" <?php if ($menu_has_children == 1) echo 'checked="checked"'; ?> />
              </div>
            </td>
            <td width="15%"><input type="text" name="menu_url" class="menu_url" value="<?php echo $menu_url; ?>" /></td>
            <td width="15%"><input type="text" name="menu_friendly_url" class="menu_friendly_url" value="<?php echo $menu_friendly_url; ?>" /></td>
            <td width="9%"><input type="text" name="menu_path_name" class="menu_path_name" value="<?php echo $menu_path_name; ?>" /></td>
            <td width="8%"><input type="text" name="menu_image_url" class="menu_image_url" value="<?php echo $menu_image_url; ?>" /></td>
            <td width="3%"><input type="text" name="menu_sort" class="menu_sort" value="<?php echo $menu_sort; ?>" /></td>
            <td width="5%">
              <div class="checkbox<?php if ($menu_active == 1) echo ' checkbox_checked'; ?>">
                <input type="checkbox" name="menu_active" class="menu_active" onClick="Checkbox(this)" <?php if ($menu_active == 1) echo 'checked="checked"'; ?> />
              </div>
            </td>
            <td width="5%"><button class="remove" onClick="DeleteMenuLink('<?php echo $menu_id; ?>')"><?=$laguages[$default_lang]['btn_delete']; ?></button></td>
          </tr>
        </table>
      </div>
      <?php
      list_menu($menu_id, $main_option_path_number);
    }
  }
}

function list_menu_for_select_a_parent($parent_id, $path_number, $menu_parent_id) {
  
    global $db_link;
    global $laguages;
    global $default_lang;
  
    $query = "SELECT `menus`.* FROM `menus` WHERE `menu_parent_id` = '$parent_id' ORDER BY `menu_sort` ASC";
    //echo $query;
    $result = mysqli_query($db_link, $query);
    if (!$result) echo mysqli_error($db_link);
    if(mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {

            $parent_id = $row['menu_id'];
            $parent_name = stripslashes($row['menu_name']);
            $parent_name = $laguages[$default_lang][$parent_name];
            $parent_level = $row['menu_level'];
            $parent_sort = $row['menu_sort'];
            if ($parent_level == 0) {
                $path_number++;
                $option_path_number = $path_number;
            } else {
                $option_path_number = "&nbsp;$path_number.$parent_sort";
            }

            if ($menu_parent_id == $parent_id)
                $selected = 'selected="selected"';
            else
                $selected = "";

            echo "<option value=\"$parent_id\" level=\"$parent_level\" $selected>$option_path_number. $parent_name</option>";

            list_menu_for_select_a_parent($parent_id, $option_path_number, $menu_parent_id);
        }
    }
}

function list_menu_in_uls($menu_id, $user_id, $current_page) {
    global $db_link;
    global $laguages;
    global $default_lang;
    
    $query_menus = "SELECT `menus`.*, `users_rights`.*
                    FROM `menus`
                    INNER JOIN `users_rights` ON `users_rights`.`menu_id` = `menus`.`menu_id`
                    WHERE `menus`.`menu_parent_id` = '$menu_id' AND `users_rights`.`user_id` = '$user_id' AND `menu_active` = '1' 
              ORDER BY `menu_sort` ASC";
    //echo $query_menus."<br>";
    $result_menus = mysqli_query($db_link, $query_menus);
    if (!$result_menus) echo mysqli_error($db_link);
    if(mysqli_num_rows($result_menus) > 0) {
      while ($menu_row = mysqli_fetch_assoc($result_menus)) {
        if (!empty($menu_row['menu_id'])) {
          $menu_name = $menu_row['menu_name'];
          $menu_name = $laguages[$default_lang][$menu_name];
          $href = $menu_row['menu_friendly_url'];
          $a_class = "second_menu_link";
          $user_access_sha1 = sha1($menu_row['menu_id']);
          $menu_image_url = $menu_row['menu_image_url'];
          $users_rights_edit = $menu_row['users_rights_edit'];
          $users_rights_delete = $menu_row['users_rights_delete'];
          if($menu_row['menu_level'] == 2) $a_class = "third_menu_link";
          //echo "$current_page - $href";
          ?>
            <li<?php echo (!empty($menu_image_url) ? " id='$menu_image_url'" : NULL); ?><?php echo ($current_page == $href ? " class='active_menu active'" : NULL); ?>>
              <a href="<?php echo $href; ?>" users-rights-access="<?php echo $user_access_sha1;?>" user-access-edit="<?php echo $users_rights_edit;?>" user-access-delete="<?php echo $users_rights_delete;?>" class="<?php echo $a_class;?>"><?php echo $menu_name; ?></a>
                <ul>
                <?php list_menu_in_uls($menu_row['menu_id'], $user_id, $current_page); ?>
                </ul>
            </li>
          <?php
        }
      }
      mysqli_free_result($result_menus);
    }
}

function do_menu_management_page($first_iteration,$menu_id,$main_path_number) {
  global $current_page;
  global $laguages;
  global $default_lang;
?>
<input type="hidden" id="friendly_url" value="<?php echo $current_page;?>">
<table>
  <thead>
    <tr>
      <td width="5%"><?=$laguages[$default_lang]['btn_save'];?></td>
      <td width="5%"><?=$laguages[$default_lang]['menu_number_thead'];?></td>
      <td width="10%"><?=$laguages[$default_lang]['menu_name_thead'];?></td>
      <td width="15%"><?=$laguages[$default_lang]['menu_parent_thead'];?></td>
      <td width="5%"><?=$laguages[$default_lang]['menu_has_children_thead'];?></td>
      <td width="15%"><?=$laguages[$default_lang]['menu_url_address_thead'];?></td>
      <td width="15%"><?=$laguages[$default_lang]['menu_pretty_url_thead'];?></td>
      <td width="9%"><?=$laguages[$default_lang]['menu_directory_path_thead'];?></td>
      <td width="8%"><?=$laguages[$default_lang]['menu_image_thead'];?></td>
      <td width="3%"><?=$laguages[$default_lang]['menu_order_thead'];?></td>
      <td width="5%"><?=$laguages[$default_lang]['menu_is_active_thead'];?></td>
      <td width="5%"><?=$laguages[$default_lang]['btn_delete'];?></td>
    </tr>
  </thead>
</table>

<div id="menu_list" class="margin_bottom">
<?php
  list_menu($menu_id, $main_path_number); 
?>   
</div>
<div id="add_menu" class="add_new_form">
  <h3><?=$laguages[$default_lang]['form_add_new'];?></h3>
  <table>
    <tr class="row_over">
      <td width="5%">
        <button class="btn_save" onClick="AddMenuLink();">
          <?=$laguages[$default_lang]['btn_save'];?>
        </button>
      </td>
      <td width="5%">&nbsp;</td>
      <td width="10%"><input type="text" name="menu_name" id="add_menu_name" /></td>
      <td width="15%">
        <select id="add_menu_parent_id">
          <option value="0" level="-1"></option>
          <?php list_menu_for_select_a_parent($parent_id = 0, $path_number = 0, $menu_parent_id = 0); ?> 
        </select>
      </td>
      <td width="5%">
        <div class="checkbox">
          <input type="checkbox" name="menu_has_children" id="add_menu_has_children" onClick="Checkbox(this);" />
        </div>
      </td>
      <td width="15%"><input type="text" name="menu_url" id="add_menu_url" /></td>
      <td width="15%"><input type="text" name="menu_friendly_url" id="add_menu_friendly_url" /></td>
      <td width="9%"><input type="text" name="menu_path_name" id="add_menu_path_name" /></td>
      <td width="8%"><input type="text" name="menu_image_url" id="add_menu_image_url" /></td>
      <td width="3%"><input type="text" name="menu_sort" id="add_menu_sort" /></td>
      <td width="5%">
        <div class="checkbox">
          <input type="checkbox" name="menu_active" id="add_menu_active" onClick="Checkbox(this);" />
        </div>
      </td>
      <td width="5%"></td>
    </tr>
  </table>
</div>
<div class="clearfix"></div>
<?php
}
?>