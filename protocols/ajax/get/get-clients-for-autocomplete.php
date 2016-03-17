<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_GET['term'])) {
    $term = $_GET['term'];
  }

  $users_array = array();
  $query_clients = "SELECT `user_id` as client_id, CONCAT(`user_firstname`,' ',`user_lastname`) as client_name
                    FROM `users` 
                    WHERE `users`.`user_type_id` = '3' AND (`user_firstname` LIKE '%$term%' OR `user_lastname` LIKE '%$term%')
                    ORDER BY client_name ASC";
  //echo $query_clients;
  $result_clients = mysqli_query($db_link, $query_clients);
  if(mysqli_num_rows($result_clients) > 0) {
    while($clients = mysqli_fetch_array($result_clients)) {

      $clients['label'] = $clients['client_name'];
      $users_array[] = $clients;
    }
  }
  //print_r($users_array);exit;
  if(!empty($users_array)) echo json_encode($users_array);
?>