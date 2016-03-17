<?php
  require_once("config.php");
  
  $db_link = DB_OpenI();

  //secured();
  //print_r($_GET);
  if (isset($_GET['page']) && $_GET['page'] === "logout") {
      unset($_SESSION['tyreslog']['user_id']);
      unset($_SESSION['tyreslog']['user_type_id']);
      unset($_SESSION['tyreslog']['user_username']);
      unset($_SESSION['tyreslog']['user_fullname']);
      session_destroy();
      echo "<script type='text/jscript'>\n window.location='/'\n</script>\n";
  }
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title><?=$laguages[$default_lang]['tyreslog_description'];?></title>
    <!--<link rel="icon" href="images/favicon.ico" type="image/x-icon">-->
    <link href="style.css" rel="stylesheet" type="text/css"  media="screen">
    <link href="js/vader/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.10.4.custom.min.js"></script>
</head>
<body <?php echo (!isset($_GET['page']) ? !isset($_SESSION['tyreslog']['user_id']) ? 'class="index login"': 'class="index"' : 'class="pages"') ?>>
  <div id="modal_window_backgr"></div>
  <div id="modal_window"></div>
  <div id="ajax_loader_backgr"></div>
  <div id="ajax_loader">
    <div class="sk-cube-grid">
      <div class="sk-cube sk-cube1"></div>
      <div class="sk-cube sk-cube2"></div>
      <div class="sk-cube sk-cube3"></div>
      <div class="sk-cube sk-cube4"></div>
      <div class="sk-cube sk-cube5"></div>
      <div class="sk-cube sk-cube6"></div>
      <div class="sk-cube sk-cube7"></div>
      <div class="sk-cube sk-cube8"></div>
      <div class="sk-cube sk-cube9"></div>
    </div>
  </div>
  <header id="header">
    <?php include_once("header.php"); ?>
  </header>
  <section class="clearfix"></section>
  <section id="content">
    <?php include_once("main.php"); ?>
    <section class="clearfix"></section>
  </section>
  <footer id="footer">
    <?php include_once("footer.php"); ?>
  </footer>
<?php 
  DB_CloseI($db_link);
  if(isset($_SESSION['tyreslog']['user_id']) && !empty($_SESSION['tyreslog']['user_id'])) {
?>
  <script type="text/javascript" src="js/functions.js"></script>
<?php
  }
?>
  <script type="text/javascript">
<?php 
    if(isset($_GET['page']) && isset($_SESSION['tyreslog']['user_id']) && !empty($_SESSION['tyreslog']['user_id'])) {
?>
    $(".row_over *").click(function() {
      $(".row_over").removeClass("row_over_edit");
      $(this).closest(".row_over").addClass("row_over_edit");
    });
    $(".active_menu").parent().parent().addClass("active"); 
    $(".second_menu li.active ul").show();
    if ($("#active_first_level").length) {
      var first_level_name = $("#active_first_level").val();
      var second_level_name = $(".second_menu .active .second_menu_link").html();
      if(second_level_name == undefined) {
        second_level_name = "";
      }
      var page_title = first_level_name+" - "+second_level_name;
      $(document).attr('title', page_title);
    }
    //var third_menu_visibility = $(".second_menu li.active ul").css("display");
    var third_menu_content = $(".second_menu li.active ul").html();
    //alert(third_menu_content);
    if(third_menu_content == "" || third_menu_content == undefined) {
      $(".second_menu").removeClass("active");
    }
    else $(".second_menu").addClass("active");
<?php
    }
?>
    if ($("#loginform").length) {
      $("#loginform #user_username").focus();
    }
  </script>
</body>
</html>