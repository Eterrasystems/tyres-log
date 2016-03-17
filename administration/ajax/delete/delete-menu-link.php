<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  if(isset($_POST['menu_id'])) {
    $menu_id = $_POST['menu_id'];
  }
  
  if(!empty($menu_id)) {
    
    mysqli_query($db_link,"BEGIN");
    
    $all_queries = "";
    
    $query = "SELECT `menu_id` FROM `menus` WHERE `menu_parent_id` = '$menu_id'";
    //echo $query;exit;
    $all_queries .= "\n".$query;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_num_rows($result) > 0) {
      echo "This menu has children. Please delete the children first!";
      mysqli_query($db_link,"ROLLBACK");
      exit;
    }
    
    $query = "DELETE FROM `menus` WHERE `menu_id` = '$menu_id'";
    //echo $query;exit;
    $all_queries .= "\n".$query;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) <= 0) {
      echo "Something went wrong";mysqli_query($db_link,"ROLLBACK");exit;
    }
    
    $query = "DELETE FROM `users_rights` WHERE `menu_id` = '$menu_id'";
    //echo $query;exit;
    $all_queries .= "\n".$query;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) <= 0) {
      echo "Something went wrong";mysqli_query($db_link,"ROLLBACK");exit;
    }
    
    //echo $all_queries;mysqli_query($db_link,"ROLLBACK");exit;
    mysqli_query($db_link,"COMMIT");
  }
  
  DB_CloseI($db_link);