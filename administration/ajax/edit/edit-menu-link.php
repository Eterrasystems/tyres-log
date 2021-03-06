<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['menu_id'])) {
    $menu_id = $_POST['menu_id'];
  }
  if(isset($_POST['menu_parent_id'])) {
    $menu_parent_id = $_POST['menu_parent_id'];
  }
  if(isset($_POST['menu_parent_level'])) {
    $menu_parent_level = $_POST['menu_parent_level']+1;
  }
  if(isset($_POST['menu_name'])) {
    $menu_name = mysqli_real_escape_string($db_link,$_POST['menu_name']);
  }
  if(isset($_POST['menu_has_children'])) {
    $menu_has_children = $_POST['menu_has_children'];
  }
  if(isset($_POST['menu_url'])) {
    $menu_url = mysqli_real_escape_string($db_link,$_POST['menu_url']);
  }
  if(isset($_POST['menu_friendly_url'])) {
    $menu_friendly_url = prepare_for_null_row(mysqli_real_escape_string($db_link,$_POST['menu_friendly_url']));
  }
  if(isset($_POST['menu_image_url'])) {
    $menu_image_url = prepare_for_null_row(mysqli_real_escape_string($db_link,$_POST['menu_image_url']));
  }
  if(isset($_POST['menu_path_name'])) {
    $menu_path_name = $_POST['menu_path_name'];
  }
  if(isset($_POST['menu_sort'])) {
    $menu_sort = $_POST['menu_sort'];
  }
  if(isset($_POST['menu_active'])) {
    $menu_active = $_POST['menu_active'];
  }
  
  if(!empty($menu_id) && !empty($menu_name) && !empty($menu_url)) {
    
    $query = "UPDATE `menus` SET 
                          `menu_parent_id` = '$menu_parent_id', 
                          `menu_level` = '$menu_parent_level',
                          `menu_has_children` = '$menu_has_children',
                          `menu_path_name` = '$menu_path_name',
                          `menu_name` = '$menu_name', 
                          `menu_url` = '$menu_url', 
                          `menu_friendly_url` = $menu_friendly_url, 
                          `menu_image_url` = $menu_image_url, 
                          `menu_sort` = '$menu_sort', 
                          `menu_active` = '$menu_active'
                        WHERE `menu_id` = '$menu_id'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo mysqli_error($db_link);
    }
    else { 
      
    }
  }
  
  DB_CloseI($db_link);