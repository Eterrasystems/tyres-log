<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  if(isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
  }
  
  if(!empty($user_id) && $user_id != 1) {
    $query = "DELETE FROM `users` WHERE `user_id` = '$user_id'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo mysqli_error($db_link);
    }
    else {
      $query = "DELETE FROM `users_rights` WHERE `user_id` = '$user_id'";
      //echo $query;exit;
      $result = mysqli_query($db_link, $query);
      if(!$result) {
        echo mysqli_error($db_link);
      }
      else {
        
      }
    }
  }
  
  DB_CloseI($db_link);