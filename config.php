<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

define("BASEPATH", "http://tyres-log.eterrasystems.eu/");
define("WEBSITE", dirname(__FILE__)); // no trailing slash
define("URLWEBSITE", "");
//setlocale(LC_ALL, 'bg_BG.UTF-8');
date_default_timezone_set('Europe/Sofia');

$default_lang = "bg_BG";
if(isset($_COOKIE['lang'])) {
  $default_lang = $_COOKIE['lang'];
}

require_once("actions/functions.php");
require_once("languages/languages.php");

//start session
if (!strpos($_SERVER['PHP_SELF'], "ajax") || strlen(session_id()) < 1) {
    session_start();
}

if (empty($_SESSION['tyreslog']['user_id']) && !empty($_GET['page'])) {
    echo "<script type='text/jscript'>\n window.location='/'\n</script>\n";
}

function DB_OpenI() {

    $db_name = "tyres_log_system";
    $db_user = "root";
    $db_password = "Idimitrov";

    $mysqli = new mysqli("localhost", $db_user, $db_password, $db_name);

    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    /* change character set to utf8 */
    if (!$mysqli->set_charset("utf8")) {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    } else {
        //printf("Current character set: %s\n", $mysqli->character_set_name());
    }

    return $mysqli;
}

function DB_CloseI($db_link) {
  mysqli_close($db_link);
}

function secured() {
  
  if(!defined('BASEPATH')) exit('<h1>No sufficient rights!</h1>');
  
  if(isset($_SESSION['tyreslog']['user_id']) && !empty($_SESSION['tyreslog']['user_id'])) {
    // it's ok
  }
  else {
    // this seems to be an outside atack
    exit('<h1>No sufficient rights!</h1>');
  }
  
}

function check_ajax_request() {
  
  if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // this is an ajax request
  }
  else {
    exit ("<h1>No sufficient rights!</h1>");
  }
}

function check_for_csrf() {
  
  global $db_link;
  
  secured();
  check_ajax_request();
  
  if(isset($_POST['user_access'])) {
    $user_access = $_POST['user_access'];
  }
    
  if(strpos($_SERVER['PHP_SELF'], "ajax/edit") || strpos($_SERVER['PHP_SELF'], "ajax/add")) {
    
    $query = "SELECT `users_rights_edit` FROM `users_rights` WHERE `user_id` = '".$_SESSION['tyreslog']['user_id']."' AND SHA1( menu_id ) = '$user_access'";
    //echo $query;exit;
    $result = mysqli_query($db_link, $query);
    if(!$result) echo mysqli_error($db_link);
    if(mysqli_num_rows($result) > 0) {
      $users_rights = mysqli_fetch_assoc($result);
      $users_rights_edit = $users_rights['users_rights_edit'];
    }

    if($users_rights_edit == 0) {
      exit('<h1>No sufficient rights!</h1>');
    }
  }
  
  if(strpos($_SERVER['PHP_SELF'], "ajax/delete")) {
    
    $query = "SELECT `users_rights_delete` FROM `users_rights` WHERE `user_id` = '".$_SESSION['tyreslog']['user_id']."' AND SHA1( menu_id ) = '$user_access'";
    $result = mysqli_query($db_link, $query);
    if(!$result) {
      echo mysqli_error($db_link);
    }
    else {
      $users_rights = mysqli_fetch_assoc($result);
      $users_rights_delete = $users_rights['users_rights_delete'];
    }

    if($users_rights_delete == 0) {
      exit('<h1>No sufficient rights!</h1>');
    }
  }
  
}

function check_for_csrf_in_reports() {
  
  secured();
  
}
?>