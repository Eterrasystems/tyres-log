<aside id="user_area">
<?php
//  phpinfo();exit;
  
  if(!empty($_GET['page'])) {
    $current_page = rawurlencode($_GET['page']);
    //echo $current_page;
  } 
  else {
    if(!empty($_SESSION['tyreslog']['user_id'])){
      $current_page = "welcome";
    } 
    else {
      $current_page = "login";
    }
  }

  if (isset($_SESSION['tyreslog']['user_id']) && !empty($_SESSION['tyreslog']['user_id'])) {
    
    echo $_SESSION['tyreslog']['user_fullname'];
?>
  <strong><a href="logout"><?=$laguages[$default_lang]['logout'];?></a></strong>
<?php } ?>

</aside>
<div id="languages">
  <a href="<?=$current_page;?>" onclick="createCookie('lang','bg_BG')">bg</a>
  <a href="<?=$current_page;?>" onclick="createCookie('lang','en_US')">en</a>
</div>
<h1 id="logo">
  <a href="index.php" title="<?=$laguages[$default_lang]['goto_home_page'];?>">
    <img src="images/logoMX.png" alt="<?=$laguages[$default_lang]['tyreslog_description'];?>" />
  </a>
  <span><?=$laguages[$default_lang]['tyreslog_description'];?></span>
</h1>
<section class="clearfix"></section>
<?php 
  if(!empty($_SESSION['tyreslog']['user_id'])) {
    require_once("files/main-menu.php"); 
?>
<section class="clearfix"></section>
<?php } ?>
