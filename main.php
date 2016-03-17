<?php
//load the page content
if (isset($_SESSION['tyreslog']['user_id']) && !empty($_SESSION['tyreslog']['user_id'])):
  $user_id = $_SESSION['tyreslog']['user_id'];

  $query = "SELECT `menus`.`menu_id`, `menu_parent_id`, `menu_level`, `menu_name`,`menu_url`, `menu_friendly_url`, `menu_image_url`, `menu_sort`, `menu_active`,
                    `users_rights_id`, `user_id` , `users_rights_edit`, `users_rights_delete`
            FROM `menus`
            INNER JOIN `users_rights` ON `users_rights`.`menu_id` = `menus`.`menu_id`
            WHERE `users_rights`.`user_id` = '$user_id' AND `menus`.`menu_friendly_url` = '$current_page' AND `menus`.`menu_active` = '1'";
  //echo $query;
  $currentPageLoadResult = mysqli_query($db_link, $query);
  if(!$currentPageLoadResult):
    echo mysqli_error($db_link);
  endif;
  if (mysqli_num_rows($currentPageLoadResult) > 0):
    $currentPageLoadRow = mysqli_fetch_assoc($currentPageLoadResult);
    $menu_id = $currentPageLoadRow['menu_id'];
    if (!empty($menu_id)):
//      $query = "SELECT `language_id`, `language_name` FROM `languages` WHERE `language_id` = '1'";
      $query = "SELECT `language_id`, `language_name` FROM `languages`";
      $languages_result = mysqli_query($db_link,$query);
      if(!$languages_result): echo mysql_error();endif;
      if(mysqli_num_rows($languages_result) > 0):
        $there_are_menu_notes = false;
        $menu_notes = "";
        $default_lang_is_first = true;
?>
      <ul id="notes_box" style="display:block;">
        <li class="pull_out"><?=$laguages[$default_lang]['f3_notes'];?></li>
        <li id="notes_icon_lang">
          <span></span>
<?php
        while($languages = mysqli_fetch_assoc($languages_result)) {

          $language_id = $languages['language_id'];
          $language_code = $languages['language_name'];
          $language_name = $laguages[$default_lang][$language_code];

          $query = "SELECT `menu_note` FROM `menus_notes` WHERE `menu_id` = '$menu_id' AND `language_id` = '$language_id'";
          //echo $query;
          $result_menu = mysqli_query($db_link,$query);
          if(!$result_menu): 
            echo mysql_error();
          endif;
          if(mysqli_num_rows($result_menu) > 0):
            $default_language_link = ($default_lang_is_first) ? ' current' : '';
            echo '<a class="language_link'.$default_language_link.'" data="'.$language_code.'">'.$language_name.'</a>';

            $row = mysqli_fetch_assoc($result_menu);
            $menu_note = stripslashes($row['menu_note']);
            $default_note_style = ($default_lang_is_first) ? ' style="display:block;"' : '';
            $menu_notes .= "<span class=\"notes note_$language_code\"$default_note_style>$menu_note</span>";
            $there_are_menu_notes = true;
            $default_lang_is_first = false;
          endif;
        }
?>
        </li>
        <li id="notes_area">
          <?php echo $menu_notes;?>
        </li>
      </ul>
<?php 
        if($there_are_menu_notes):
?>
<script type="text/javascript">
  $(document).ready(function() {
    $("#notes_box").show();
    $("#notes_icon_lang a").click(function() {
      var note_lang_class = $(this).attr("data");
      $(".language_link").removeClass("current");
      $(this).addClass("current");
      $("#notes_area .notes").hide();
      $("#notes_area .note_"+note_lang_class).show();
    });
  });
</script>
<?php
        endif;
      endif;
      include_once($currentPageLoadRow['menu_url']);
    else:// if (!empty($currentPageLoadRow['menu_id']))
        ?><div style="text-align: center;color: #F00; font-weight: bold; padding: 50px 0;">Sorry! Insufficient rights!</div><?php
    endif; // if (!empty($currentPageLoadRow['menu_id']))
  elseif ($current_page == "login"):
      include_once("files/login.php");
  elseif ($current_page == "welcome"):

    include_once("index-content.php");

  else:// if (mysqli_num_rows($currentPageLoadResult) > 0)
      ?>
      <div style="color: #F00; font-weight: bold; padding: 50px 0;">The page you are looking does not exist!</div>
  <?php
  endif;// if (mysqli_num_rows($currentPageLoadResult) > 0)
  mysqli_free_result($currentPageLoadResult);
  echo '<input type="hidden" id="user_id" value="'.$_SESSION['tyreslog']['user_id'].'">';
else:
    include_once("files/login.php");
endif;
?>