<table>
  <thead>
    <tr>
      <td width="5%"><?=$laguages[$default_lang]['btn_save'];?></td>
      <td width="20%"><?=$laguages[$default_lang]['user_username_thead'];?></td>
      <td width="10%"><?=$laguages[$default_lang]['user_password_thead'];?></td>
      <td width="15%"><?=$laguages[$default_lang]['user_firstname_thead'];?></td>
      <td width="15%"><?=$laguages[$default_lang]['user_lastname_thead'];?></td>
      <td></td>
    </tr>
  </thead>
</table>
<?php
  if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
  }
  
  $db_link = DB_OpenI();
  
  $query = "SELECT `users`.`user_username`, `users`.`user_firstname`, `users`.`user_lastname`
            FROM `users`
            WHERE `users`.`user_id` = '$user_id'";
  $result_users = mysqli_query($db_link,$query);
  if (!$result_users) echo mysqli_error($db_link);
  if(mysqli_num_rows($result_users) > 0) {// if (!$result_users)
    
    $user_details = mysqli_fetch_assoc($result_users);

    $user_username = $user_details['user_username'];
    $user_firstname = $user_details['user_firstname'];
    $user_lastname = $user_details['user_lastname'];
?>
      <div id="user<?php echo $user_id; ?>" class="row_over">
        <table>
          <tbody>
            <tr>
              <td width="5%"><button class="btn_save" onClick="EditRestrictedUser('<?php echo $user_id; ?>')" title="Update"><?=$laguages[$default_lang]['btn_save'];?></button></td>
              <td width="20%"><input type="text" id="user_username<?php echo $user_id; ?>" class="user_username" value="<?php echo $user_username; ?>" ></td>
              <td width="10%"><input type="password" id="user_password<?php echo $user_id; ?>" class="user_password" placeholder="******" ></td>
              <td width="15%"><?php echo $user_firstname; ?></td>
              <td width="15%"><?php echo $user_lastname; ?></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
<?php
    mysqli_free_result($result_users);
  }// if (!$result_users)
?>
<div class="clearfix"></div>