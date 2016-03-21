<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
//  print_r($_POST);exit;
  $user_id = $_SESSION['tyreslog']['user_id'];
  if(isset($_POST['tyre_storage_id'])) {
    $tyre_storage_id = $_POST['tyre_storage_id'];
  }
  if(isset($_POST['tyre_position_ids'])) {
    $tyre_position_ids = $_POST['tyre_position_ids'];
  }
  if(isset($_POST['tyre_model_ids'])) {
    $tyre_model_ids = $_POST['tyre_model_ids'];
  }
  if(isset($_POST['tyre_season_ids'])) {
    $tyre_season_ids = $_POST['tyre_season_ids'];
  }
  if(isset($_POST['tyre_width_ids'])) {
    $tyre_width_ids = $_POST['tyre_width_ids'];
  }
  if(isset($_POST['tyre_ratio_ids'])) {
    $tyre_ratio_ids = $_POST['tyre_ratio_ids'];
  }
  if(isset($_POST['tyre_diameter_ids'])) {
    $tyre_diameter_ids = $_POST['tyre_diameter_ids'];
  }
  if(isset($_POST['tyre_load_index_ids'])) {
    $tyre_load_index_ids = $_POST['tyre_load_index_ids'];
  }
  if(isset($_POST['tyre_speed_index_ids'])) {
    $tyre_speed_index_ids = $_POST['tyre_speed_index_ids'];
  }
  if(isset($_POST['tyre_dots'])) {
    $tyre_dots = $_POST['tyre_dots'];
  }
  if(isset($_POST['tyre_grapple_depths'])) {
    $tyre_grapple_depths = $_POST['tyre_grapple_depths'];
  }
  if(isset($_POST['tyre_note'])) {
    $tyre_note = prepare_for_null_row(mysqli_real_escape_string($db_link,$_POST['tyre_note']));
  }
  if(isset($_POST['tyre_defects'])) {
    $tyre_defects = $_POST['tyre_defects'];
  }
  if(isset($_POST['tyre_has_rim'])) {
    $tyre_has_rims = $_POST['tyre_has_rim'];
  }
  if(isset($_POST['tyre_rim_note'])) {
    $tyre_rim_notes = $_POST['tyre_rim_note'];
  }
  
  //echo $laguages[$default_lang]['protocol_inserted_successfully'];exit;
  if(!empty($user_id) && isset($_POST['tyre_position_ids']) && !empty($tyre_position_ids)) {
    
    mysqli_query($db_link, "START TRANSACTION");
    
    $query = "UPDATE `tyres_storages` SET `tyre_storage_state`='2' WHERE `tyre_storage_id`='$tyre_storage_id'";
    $all_queries = $query."\n";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo $laguages[$default_lang]['sql_error_update'];
      echo mysqli_error($db_link);
      mysqli_query($db_link, "ROLLBACK");
      exit;
    }
    
    //log
    //log_tyre_storage_action: 0 - create, 1 - edit, 2 - delete
    $query_log_action = "INSERT INTO `logs_tyres_storages_actions`(
                                              `ltsa_id`, 
                                              `user_id`, 
                                              `log_tyre_storage_date`, 
                                              `log_tyre_storage_action`) 
                                      VALUES ('',
                                              '$user_id',
                                              NOW(),
                                              '0')";
    $all_queries .= $query_log_action."\n";
    //echo $query;exit;
    $result_log_action = mysqli_query($db_link, $query_log_action);
    if(!$result_log_action) echo mysqli_error($db_link);
    if(mysqli_affected_rows($db_link) <= 0) {
      echo $laguages[$default_lang]['sql_error_insert'];
      mysqli_query($db_link, "ROLLBACK");
      exit;
    }
    
    $ltsa_id = mysqli_insert_id($db_link);
    
    foreach($tyre_position_ids as $key => $tyre_position_id) {
      
      $tyre_model_id = $tyre_model_ids[$key];
      $tyre_season_id = $tyre_season_ids[$key];
      $tyre_width_id = $tyre_width_ids[$key];
      $tyre_ratio_id = $tyre_ratio_ids[$key];
      $tyre_diameter_id = $tyre_diameter_ids[$key];
      $tyre_load_index_id =  $tyre_load_index_ids[$key];
      $tyre_speed_index_id = $tyre_speed_index_ids[$key];
      $tyre_dot = $tyre_dots[$key];
      $tyre_grapple_depth = $tyre_grapple_depths[$key];
      $tyre_defects_text = prepare_for_null_row(mysqli_real_escape_string($db_link,$tyre_defects[$key]));
      $tyre_has_rim = $tyre_has_rims[$key];
      $tyre_rim_note = prepare_for_null_row(mysqli_real_escape_string($db_link,$tyre_rim_notes[$key]));
      //echo $tyre_defects."\n";
      
      $query = "INSERT INTO `tyres_storages_details`(
                                    `tyre_storage_details_id`, 
                                    `tyre_storage_id`, 
                                    `tyre_position_id`, 
                                    `tyre_model_id`, 
                                    `tyre_season_id`, 
                                    `tyre_width_id`, 
                                    `tyre_ratio_id`, 
                                    `tyre_diameter_id`, 
                                    `tyre_load_index_id`, 
                                    `tyre_speed_index_id`, 
                                    `tyre_has_rim`, 
                                    `tyre_rim_note`, 
                                    `tyre_dot`, 
                                    `tyre_grapple_depth`, 
                                    `tyre_defects`)
                            VALUES ('',
                                    '$tyre_storage_id',
                                    '$tyre_position_id',
                                    '$tyre_model_id',
                                    '$tyre_season_id',
                                    '$tyre_width_id',
                                    '$tyre_ratio_id',
                                    '$tyre_diameter_id',
                                    '$tyre_load_index_id',
                                    '$tyre_speed_index_id',
                                    '$tyre_has_rim',
                                    $tyre_rim_note,
                                    '$tyre_dot',
                                    '$tyre_grapple_depth',
                                    $tyre_defects_text)";
      $all_queries .= $query."\n";
      //echo $query;exit;
      $result = mysqli_query($db_link, $query);
      if(!$result) echo mysqli_error($db_link);
      if(mysqli_affected_rows($db_link) <= 0) {
        echo $laguages[$default_lang]['sql_error_insert'];
        mysqli_query($db_link, "ROLLBACK");
        exit;
      }
      else {
        $tyre_storage_details_id = mysqli_insert_id($db_link);
        
        $query_storage_details = "INSERT INTO `logs_tyres_storages_details`(
                                      `ltsd_id`, 
                                      `ltsa_id`, 
                                      `tyre_storage_details_id`, 
                                      `tyre_storage_id`, 
                                      `tyre_position_id`, 
                                      `tyre_model_id`, 
                                      `tyre_season_id`, 
                                      `tyre_width_id`, 
                                      `tyre_ratio_id`, 
                                      `tyre_diameter_id`, 
                                      `tyre_load_index_id`, 
                                      `tyre_speed_index_id`, 
                                      `tyre_has_rim`, 
                                      `tyre_rim_note`, 
                                      `tyre_dot`, 
                                      `tyre_grapple_depth`, 
                                      `tyre_defects`)
                              VALUES ('',
                                      '$ltsa_id',
                                      '$tyre_storage_details_id',
                                      '$tyre_storage_id',
                                      '$tyre_position_id',
                                      '$tyre_model_id',
                                      '$tyre_season_id',
                                      '$tyre_width_id',
                                      '$tyre_ratio_id',
                                      '$tyre_diameter_id',
                                      '$tyre_load_index_id',
                                      '$tyre_speed_index_id',
                                      '$tyre_has_rim',
                                      $tyre_rim_note,
                                      '$tyre_dot',
                                      '$tyre_grapple_depth',
                                      $tyre_defects_text)";
        $all_queries .= $query_storage_details."\n";
        //echo $query;exit;
        $result_storage_details = mysqli_query($db_link, $query_storage_details);
        if(!$result_storage_details) echo mysqli_error($db_link);
        if(mysqli_affected_rows($db_link) <= 0) {
          echo $laguages[$default_lang]['sql_error_insert'];
          mysqli_query($db_link, "ROLLBACK");
          exit;
        }
      
      }
    }
    //echo $all_queries;mysqli_query($db_link, "ROLLBACK");exit;
    
    echo $laguages[$default_lang]['protocol_inserted_successfully'];
    mysqli_query($db_link, "COMMIT");
  }
  DB_CloseI($db_link);