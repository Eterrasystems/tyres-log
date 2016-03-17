<?php
//  unset($_SESSION['tyreslog']['login_error']);
//  unset($_SESSION['tyreslog']['bfa']);
//  session_destroy();
//  echo"<pre>";print_r($_SESSION['tyreslog']);
  
  $sitekey = "6LfS9hoTAAAAAKEsBdUvGaegjAcNmY7qcaKp4n_e";
  $secretkey = "6LfS9hoTAAAAABNQcNWyhr_ST6bnQgqcvhiDAKdn";
  
  $form_is_submitted = false;
  $recaptcha_response = false;
  if(isset($_POST['g-recaptcha-response'])) {
    $g_recaptcha_response = $_POST['g-recaptcha-response'];
    $url = 'https://www.google.com/recaptcha/api/siteverify';
      $data = array('secret' => "$secretkey", 'response' => $g_recaptcha_response);

    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);
    $result = json_decode(file_get_contents($url, false, $context));
  }

  if(!isset($_SESSION['login_error']['count'])){
    $_SESSION['login_error'] = array();
    $_SESSION['login_error']['count'] = 0;
  }
  
  if(isset($_POST['login'])) {
    //echo"<pre>";print_r($_POST);
    
    $form_is_submitted = true;
    $recaptcha_response = $result->success;
    if(!$recaptcha_response) {
      $errors['recaptcha_response_field'] = "<h2 class='red' style='text-align:center;'>Трябва да маркирате полето \"Не съм робот\"</h2>";  
    }
  
    if($recaptcha_response = $result->success) {
    //if(true){

      unset($_SESSION['tyreslog']['captcha_error']);
      $password = $_POST['password'];
      $user_username = $_POST['user_username'];
      $user_location_city = prepare_for_null_row(mysqli_real_escape_string($db_link,$_POST['user_location_city']));
      $user_location_latitude = $_POST['user_location_latitude'];
      $user_location_longitude = $_POST['user_location_longitude'];
      $post_user_ip = $_SERVER['REMOTE_ADDR'];
      $bcrypt_salt = "$2y$08$".generate_bcrypt_salt()."$";
      $bcrypt_password = crypt($password , $bcrypt_salt);

      $query_user = "SELECT `user_id`,`user_type_id`,`user_is_ip_in_use`,`user_ip` FROM `users` WHERE `user_username` = '$user_username'";
      $_SESSION['tyreslog']['query'] = $query_user."<br>";
      $result_user = mysqli_query($db_link,$query_user);
      if (!$result_user) echo mysqli_error($db_link);
      if(mysqli_num_rows($result_user) > 0) {
        $user = mysqli_fetch_assoc($result_user);
        
        $db_user_id = $user['user_id'];
        $user_is_ip_in_use = $user['user_is_ip_in_use'];
        $user_ip_in_database = $user['user_ip'];
        $user_remote_ip = ($user_is_ip_in_use == 1) ? (!empty($user_ip_in_database)) ? $post_user_ip : "" : "";

        $query = "SELECT `users`.*,`warehouses`.`warehouse_id`,`warehouses`.`warehouse_name`
                  FROM `users`
                  LEFT JOIN `warehouses` ON `warehouses`.`warehouse_id` = `users`.`warehouse_id`
                  WHERE `users`.`user_id` = '$db_user_id' AND `users`.`user_is_active` = '1'". (!empty($user_remote_ip) ? " 
                    AND `users`.`user_ip` = '$user_remote_ip'" : NULL);
        $_SESSION['tyreslog']['query'] .= $query."<br>";
        //echo $query;EXIT;
        $result = mysqli_query($db_link, $query);
        if (!$result) echo mysqli_error($db_link);
        if(mysqli_num_rows($result) > 0) {
          $user_details = mysqli_fetch_assoc($result);
        }
        //echo "<pre>";print_r($user_details);exit;
        if (!empty($user_details)) {

          $password_hash = $user_details['user_salted_password'];

          if(crypt($password, $password_hash) == $password_hash) {
            // password is correct

            //update ip if empty START
            if (empty($user_ip_in_database)) {
                $query_update_ip = "UPDATE `users` SET `user_ip`='$post_user_ip' WHERE `user_id` = '$db_user_id'";
                //$_SESSION['tyreslog']['$query'] = $query_update_ip;
                mysqli_query($db_link,$query_update_ip);
            }

            //make record for table users_log
            $query = "INSERT INTO `users_logs`(
                                          `user_log_id`, 
                                          `user_id`, 
                                          `user_ip`, 
                                          `user_location_city`, 
                                          `user_location_latitude`, 
                                          `user_location_longitude`, 
                                          `user_log_date`)
                                  VALUES ('',
                                          '$db_user_id',
                                          '$post_user_ip',
                                          $user_location_city,
                                          '$user_location_latitude',
                                          '$user_location_longitude',
                                          NOW())";
            $result = mysqli_query($db_link, $query);
            if (!$result) echo mysqli_error($db_link);
            
            $user_username = $user_details['user_username'];
            $contact_first_name = $user_details['user_firstname'];
            $contact_last_name = $user_details['user_lastname'];
            $user_type_id = $user_details['user_type_id'];
            $warehouse_id = $user_details['warehouse_id'];
            $warehouse_name = $user_details['warehouse_name'];

            $_SESSION['tyreslog']['user_id'] = $db_user_id;
            $_SESSION['tyreslog']['user_type_id'] = $user_type_id;
            $_SESSION['tyreslog']['user_username'] = $user_username;
            $_SESSION['tyreslog']['user_fullname'] = (empty($contact_last_name) ? "$contact_first_name" : "$contact_first_name $contact_last_name");
            $_SESSION['tyreslog']['warehouse_id'] = $warehouse_id;
            $_SESSION['tyreslog']['warehouse_name'] = $warehouse_name;
            unset($_SESSION['tyreslog']['login_error']);
            unset($_SESSION['tyreslog']['bfa']);
            echo "<script type='text/jscript'>\n window.location='/'\n</script>\n";
          }
          else {
            $_SESSION['tyreslog']['login_error']['count']++;
            $_SESSION['tyreslog']['login_error']['text'] = "<h2 style=\"text-align:center;color:red;\">Username and password mismatch</h2>";
          }

        } // if(!empty($user_details))
      } // if(mysqli_num_rows($result_user) > 0)
      else {
        $_SESSION['tyreslog']['login_error']['count'] ++;
        $_SESSION['tyreslog']['login_error']['text'] = "<h2 style=\"text-align:center;color:red;\">Username and password mismatch</h2>";
      }
    }
  }// if(isset($_POST['login']))
?>
<div>
  <h1>Log in</h1>
<?php if(isset($errors['recaptcha_response_field'])) echo $errors['recaptcha_response_field'];?>
<?php if(isset($_SESSION['login_error']['text'])) echo $_SESSION['login_error']['text'];?>
  <form name="loginform" method="post" id="loginform" action="<?=$_SERVER['PHP_SELF'];?>">
    <table>
      <tr>
        <td <?php if(isset($_SESSION['login_error']) && $_SESSION['login_error']['count'] > 0) echo 'class="error"';?>>
          <label for="user_username">Username:</label>
          <input name="user_username" autofocus type="text" id="user_username" class="input_text">
        </td>
      </tr>
      <tr>
        <td <?php if(isset($_SESSION['login_error']) && $_SESSION['login_error']['count'] > 0) echo 'class="error"';?>>
          <label for="password">Password:</label>
          <input name="password" type="password" id="password" class="input_text">
        </td>
      </tr>
      <tr>
        <td <?php if($form_is_submitted && !$recaptcha_response) echo 'class="error"';?>>
          <p></p>
          <div id="recaptcha_dark" class="g-recaptcha" data-sitekey="<?=$sitekey?>"></div>
        </td>
      </tr>
      <tr>
        <td>
          <input type="submit" name="login" value="Log in" id="login" class="button">
        </td>
      </tr>
    </table>
  </form>
</div>
<script src="https://www.google.com/recaptcha/api.js"></script>