<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  if(isset($_POST['menu_id'])) {
    $menu_id = $_POST['menu_id'];
  }
  if(isset($_POST['menu_name'])) {
    $menu_name = $_POST['menu_name'];
  }
  if(isset($_POST['level'])) {
    $level = $_POST['level'];
  }
  
  if(!empty($menu_id) && !empty($level)) {
    
    $query_menus = "SELECT `menu_id`, `menu_name` FROM `menus` WHERE `menu_parent_id` = '$menu_id' AND `menu_level` = '$level' ORDER BY `menu_sort` ASC";
    //echo $query;
    $result_menus= mysqli_query($db_link, $query_menus);
    if (!$result_menus) echo mysqli_error($db_link);
    if(mysqli_num_rows($result_menus) > 0) {
?>
  <table id="menu_links_level_<?php echo $level;?>">
    <thead>
      <tr><td><?=$menu_name;?></td></tr>
    </thead>
    <tbody>
<?php
      while($menu_links = mysqli_fetch_assoc($result_menus)) {

        $menu_id = $menu_links['menu_id'];
        $menu_name = $menu_links['menu_name'];
        $menu_name = $laguages[$default_lang][$menu_name];

        echo '<tr><td><a data="'.$menu_id.'">'.$menu_name.'</a></td></tr>';
      }
?>
    </tbody>
  </table>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#menu_links_level_<?php echo $level;?> a").click(function() {
        $("#menu_links_level_<?php echo $level;?> td").removeClass("selected_menu_link_level_<?php echo $level;?>");
        $(this).parent().addClass("selected_menu_link_level_<?php echo $level;?>");
        $("#users_menu_rights").html("");
        $("#choose_menu_right td").removeClass("selected_menu_link_right");
        GetMenuLinkChildren('<?php echo $level+1;?>');
      });
    });
  </script>
<?php
    }
  }