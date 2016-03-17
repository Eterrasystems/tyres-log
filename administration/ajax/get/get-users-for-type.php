<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['user_type_id'])) {
    $user_type_id = $_POST['user_type_id'];
  }
  if(isset($_POST['user_type'])) {
    $user_type = $_POST['user_type'];
  }
?>
  <table class="no_margin">
    <thead>
      <tr><td><?php echo $user_type; ?></td></tr>
    </thead>
  </table>
  <div id="choose_user">
    <table>
      <tbody>
<?php
      $query = "SELECT `users`.`user_id`, `users`.`user_firstname`,`users`.`user_lastname`
                FROM `users`
                WHERE `users`.`user_type_id` = '$user_type_id'
                ORDER BY `users`.`user_firstname` ASC";
      //echo $query;
      $users_result = mysqli_query($db_link, $query);
      if (!$users_result) echo mysqli_error($db_link);
      if(mysqli_num_rows($users_result) > 0) {
        while ($user_details = mysqli_fetch_assoc($users_result)) {
          $user_id = $user_details['user_id'];
          $user_firstname = $user_details['user_firstname'];
          $user_lastname = $user_details['user_lastname'];

          echo "<tr id='tr$user_id'><td><a data-id='$user_id'>$user_firstname $user_lastname</a></td></tr>";
        }
      }
      else {   
?>
        <tr><?=$laguages[$default_lang]['no_users_yet'];?></tr>
<?php    
      }
?>
      </tbody>
    </table>
  </div>
  <p></p>
  <a id="add_new_user" class="button add">
    <i class="icon_plus_sign"></i>
    <?=$laguages[$default_lang]['btn_add_new_user'];?>
  </a>
<script type="text/javascript">
  $(document).ready(function() {
    $("#choose_user a").click(function() {
      $("#choose_user td").removeClass("selected_user")
      $(this).parent().addClass("selected_user");
      $("#add_user").hide();
      GetUserDetails();
    });
    $("#add_new_user").click(function() {
      $("#user_details").html("");
      $("#add_user").show();
    });
  });
</script>
<?php
  DB_CloseI($db_link);