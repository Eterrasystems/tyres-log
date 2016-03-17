<?php
  include_once("config.php");
  
  $db_link = DB_OpenI();
  
  $query = "SELECT * FROM `users_rights` WHERE `user_id` = '1'";
  $users_result = mysqli_query($db_link, $query);
  if (!$users_result) echo mysqli_error($db_link);
  if(mysqli_num_rows($users_result) > 0) {
    while ($user_details = mysqli_fetch_assoc($users_result)) {
      $user_id = 4;
      $menu_id = $user_details['menu_id'];
      $users_rights_edit = $user_details['users_rights_edit'];
      $users_rights_delete = $user_details['users_rights_delete'];
      
      $query_insert = "INSERT INTO `users_rights`(`users_rights_id`, `user_id`, `menu_id`, `users_rights_edit`, `users_rights_delete`) 
                                          VALUES ('','$user_id','$menu_id','$users_rights_edit','$users_rights_delete')";
      $result_insert = mysqli_query($db_link, $query_insert);
      if(!$result_insert) echo mysqli_error($db_link);
    }
  }
