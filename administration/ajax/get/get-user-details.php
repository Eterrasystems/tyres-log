<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
  }
  if(isset($_POST['user_name'])) {
    $user_name = $_POST['user_name'];
  }
?>
  <table class="no_margin">
    <thead>
      <tr>
        <td width="5%"><?=$laguages[$default_lang]['btn_save'];?></td>
        <td><?php echo $laguages[$default_lang]['user_details_thead']." $user_name"; ?></td>
        <td width="5%"><?=$laguages[$default_lang]['btn_delete']; ?></td>
      </tr>
    </thead>
  </table>
<?php
  
  $all_warehouses = array();
  $query_warehouses = "SELECT `warehouses_types`.`warehouse_type_name`,`warehouses`.`warehouse_id`, `warehouses`.`warehouse_name`
                      FROM `warehouses`
                      INNER JOIN `warehouses_types` ON `warehouses_types`.`warehouse_type_id` = `warehouses`.`warehouse_type_id`
                      ORDER BY `warehouses_types`.`warehouse_type_name` ASC, `warehouses`.`warehouse_name` ASC";
  $result_warehouses = mysqli_query($db_link, $query_warehouses);
  if (!$result_warehouses) echo mysqli_error($db_link);
  if(mysqli_num_rows($result_warehouses) > 0) {
    while($row_warehouses = mysqli_fetch_assoc($result_warehouses)) {
      $all_warehouses[] = $row_warehouses;
    }
  }
  
  $query = "SELECT `users`.* FROM `users`
            WHERE `user_id` = '$user_id'
            ORDER BY `user_firstname` ASC";
  //echo $query;
  $users_result = mysqli_query($db_link, $query);
  if (!$users_result) echo mysqli_error($db_link);
  if(mysqli_num_rows($users_result) > 0) {
      $user_details = mysqli_fetch_assoc($users_result);
      //echo "<pre>";print_r($user_details);
      $user_id = $user_details['user_id'];
      $user_warehouse_id = $user_details['warehouse_id'];
      $user_username = $user_details['user_username'];
      $user_firstname = $user_details['user_firstname'];
      $user_lastname = $user_details['user_lastname'];
      $user_address = stripslashes($user_details['user_address']);
      $user_phone = $user_details['user_phone'];
      $user_email = $user_details['user_email'];
      $user_info = stripslashes($user_details['user_info']);
      $user_is_ip_in_use = $user_details['user_is_ip_in_use'];
      $user_is_active = $user_details['user_is_active'];
      $user_has_account = $user_details['user_has_account'];

?>
  <div id="user_details<?php echo $user_id;?>" class="row_over" style="padding: 2px 0;">
    <table style="width:95%;float:left;">
      <tbody>
        <tr>
          <td width="5%" rowspan="7" class="no_background">
            <button class="button btn_save" onClick="EditUserFullDetails('<?php echo $user_id;?>')">Save</button>
          </td>
        </tr>
        <tr>
          <td width="15%" class="first_child"><span><?=$laguages[$default_lang]['user_username_label'];?></span></td>
          <td width="33%"><input type="text" id="user_username" value="<?php echo $user_username;?>" /></td>
          <td width="15%"><span><?=$laguages[$default_lang]['user_address_label'];?></span></td>
          <td width="33%"><input type="text" name="user_address" id="user_address" value='<?php echo $user_address;?>' /></td>
        </tr>
        <tr>
          <td width="15%" class="first_child"><span><?=$laguages[$default_lang]['user_password_label'];?></span></td>
          <td width="33%"><input type="password" id="user_password" placeholder="******" /></td>
          <td width="15%"><span><?=$laguages[$default_lang]['user_email_label'];?></span></td>
          <td width="33%"><input type="text" name="user_email" id="user_email" value="<?php echo $user_email;?>" /></td>
        </tr>
        <tr>
          <td width="15%" class="first_child"><span><?=$laguages[$default_lang]['user_firstname_label'];?></span></td>
          <td width="33%"><input type="text" id="user_firstname" value="<?php echo $user_firstname;?>" /></td>
          <td width="15%"><span><?=$laguages[$default_lang]['user_phone_label'];?></span></td>
          <td width="33%"><input type="text" name="user_phone" id="user_phone" value="<?php echo $user_phone;?>" /></td>
        </tr>
        <tr>
          <td width="15%" class="first_child"><span><?=$laguages[$default_lang]['user_lastname_label'];?></span></td>
          <td width="33%"><input type="text" id="user_lastname" value="<?php echo $user_lastname;?>" /></td>
          <td width="15%"><span><?=$laguages[$default_lang]['user_info_label'];?></span></td>
          <td width="33%"><input type="text" id="user_info" value='<?php echo $user_info;?>' /></td>
        </tr>
<?php
        if($user_has_account == 1) {
?>
        <tr>
          <td width="15%"><span><?=$laguages[$default_lang]['user_account_label'];?></span></td>
          <td width="33%">
            <?=$laguages[$default_lang]['user_has_account'];?>
            <input type="hidden" id="user_has_account" />
          </td>
          <td width="15%" class="first_child"><span><?=$laguages[$default_lang]['user_assigned_to_warehouse_thead'];?></span></td>
          <td width="33%">
            <select id="user_warehouse_id">
              <option value="0"></option>
<?php
              foreach($all_warehouses as $warehouse) {

                $warehouse_type_name = $warehouse['warehouse_type_name'];
                $warehouse_id = $warehouse['warehouse_id'];
                $warehouse_name = $warehouse['warehouse_name'];

                if ($user_warehouse_id == $warehouse_id)
                    $selected = 'selected="selected"';
                else
                    $selected = "";

                echo "<option value='$warehouse_id' $selected>$warehouse_type_name - $warehouse_name</option>";
              }
?>
            </select>
          </td>
        </tr>
<?php   
        } else {
?>
        <tr>
          <td width="15%"><span><?=$laguages[$default_lang]['user_create_account_label'];?></span></td>
          <td width="33%">
            <div class="checkbox">
              <input type="checkbox" id="create_user_account" onClick="Checkbox(this)" />
            </div>
          </td>
          <td width="15%" class="first_child"><span><?=$laguages[$default_lang]['user_assigned_to_warehouse_thead'];?></span></td>
          <td width="33%">
            <select id="user_warehouse_id">
              <option value="0"></option>
<?php
              foreach($all_warehouses as $warehouse) {

                $warehouse_type_name = $warehouse['warehouse_type_name'];
                $warehouse_id = $warehouse['warehouse_id'];
                $warehouse_name = $warehouse['warehouse_name'];

                echo "<option value='$warehouse_id'>$warehouse_type_name - $warehouse_name</option>";
              }
?>
            </select>
          </td>
        </tr>
<?php   
        }
?>
        <tr>
          <td width="15%"><span><?=$laguages[$default_lang]['user_is_active_thead'];?></span></td>
          <td width="33%">
            <div class="checkbox<?php if ($user_is_active == 1) echo ' checkbox_checked'; ?>">
              <input type="checkbox" id="user_is_active" onClick="Checkbox(this)" <?php if ($user_is_active == 1) echo 'checked="checked"'; ?> />
            </div>
          </td>
          <td width="15%"><span><?=$laguages[$default_lang]['user_is_ip_in_use_thead'];?></span></td>
          <td width="33%">
                <div class="checkbox<?php if ($user_is_ip_in_use == 1) echo ' checkbox_checked'; ?>">
              <input type="checkbox" id="user_is_ip_in_use" onClick="Checkbox(this)" <?php if ($user_is_ip_in_use == 1) echo 'checked="checked"'; ?> />
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <table style="width:5%;float:right;">
      <tbody>
        <tr>
          <td width="3%" rowspan="7" class="no_background" style="text-align:right;">
            <button class="remove" onClick="DeleteUser('<?php echo $user_id;?>')">Delete</button>
          </td>
        </tr>
        <tr><td width="2%" class="no_background" style="height: 30px;">&nbsp;</td></tr>
        <tr><td width="2%" class="no_background" style="height: 30px;">&nbsp;</td></tr>
        <tr><td width="2%" class="no_background" style="height: 30px;">&nbsp;</td></tr>
        <tr><td width="2%" class="no_background" style="height: 30px;">&nbsp;</td></tr>
        <tr><td width="2%" class="no_background" style="height: 30px;">&nbsp;</td></tr>
        <tr><td width="2%" class="no_background" style="height: 30px;">&nbsp;</td></tr>
      </tbody>
    </table>
    <div class="clearfix"></div>
  </div>  
<?php
  }
  else {
?>
    <tr><?=$laguages[$default_lang]['no_clients_yet'];?></tr>
<?php    
  }
  
  DB_CloseI($db_link);