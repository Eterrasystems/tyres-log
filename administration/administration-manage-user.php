<!--begin of left_col-->
<div id="left_col">
<?php
  $db_link = DB_OpenI();
?>
  <table class="no_margin">
    <thead>
      <tr><td><?=$laguages[$default_lang]['choose_user_type_thead'];?></td></tr>
    </thead>
  </table>
  <div id="choose_user_type">
    <table>
      <tbody>
<?php
      $query = "SELECT `user_type_id`, `user_type_name` FROM `users_types` ORDER BY `user_type_name` ASC";
      //echo $query;
      $users_result = mysqli_query($db_link, $query);
      if (!$users_result) echo mysqli_error($db_link);
      if(mysqli_num_rows($users_result) > 0) {
        while ($user_details = mysqli_fetch_assoc($users_result)) {
          $user_type_id = $user_details['user_type_id'];
          $user_type_name = $user_details['user_type_name'];
          $user_type_name = $laguages[$default_lang][$user_type_name];

          echo "<tr><td><a data-id='$user_type_id'>$user_type_name</a></td></tr>";
        }
      }
      else {   
?>
        <tr><td><?=$laguages[$default_lang]['no_user_types_yet'];?></td></tr>
<?php    
      }
?>
      </tbody>
    </table>
  </div>
  
  <div id="users_list">

  </div>
  
</div>
<!--end of left_col-->

<div id="right_col">

  <div id="user_details">

  </div>
  
  <div id="add_user" class="add_new_form" style="display: none;">
    <table class="no_margin">
      <thead>
        <tr>
          <td width="5%"><?=$laguages[$default_lang]['btn_save'];?></td>
          <td><?=$laguages[$default_lang]['form_add_new'];?></td>
        </tr>
      </thead>
    </table>
    <table class="row_over">
      <tbody>
        <tr>
          <td width="5%" rowspan="7" class="no_background">
            <button class="button btn_save" onClick="AddUser()">Save</button>
          </td>
        </tr>
        <tr>
          <td width="15%" class="first_child"><span><?=$laguages[$default_lang]['user_username_label'];?></span></td>
          <td width="33%"><input type="text" id="add_user_username" value="" /></td>
          <td width="15%"><span><?=$laguages[$default_lang]['user_address_label'];?></span></td>
          <td width="33%"><input type="text" name="user_address" id="add_user_address" value="" /></td>
        </tr>
        <tr>
          <td width="15%" class="first_child"><span><?=$laguages[$default_lang]['user_password_label'];?></span></td>
          <td width="33%"><input type="password" id="add_user_password" placeholder="******" /></td>
          <td width="15%"><span><?=$laguages[$default_lang]['user_email_label'];?></span></td>
          <td width="33%"><input type="text" name="user_email" id="add_user_email" value="" /></td>
        </tr>
        <tr>
          <td width="15%" class="first_child"><span><?=$laguages[$default_lang]['user_firstname_label'];?></span><span class="red">*</span></td>
          <td width="33%"><input type="text" id="add_user_firstname" value="" /></td>
          <td width="15%"><span><?=$laguages[$default_lang]['user_phone_label'];?></span></td>
          <td width="33%"><input type="text" name="user_phone" id="add_user_phone" value="" /></td>
        </tr>
        <tr>
          <td width="15%" class="first_child"><span><?=$laguages[$default_lang]['user_lastname_label'];?></span></td>
          <td width="33%"><input type="text" id="add_user_lastname" value="" /></td>
          <td width="15%"><span><?=$laguages[$default_lang]['user_info_label'];?></span></td>
          <td width="33%"><input type="text" id="add_user_info" value="" /></td>
        </tr>
        <tr>
          <td width="15%"><span><?=$laguages[$default_lang]['user_create_account_label'];?></span></td>
          <td width="33%">
            <div class="checkbox">
              <input type="checkbox" id="add_create_user_account" onClick="Checkbox(this)" />
            </div>
          </td>
          <td width="15%" class="first_child"><span><?=$laguages[$default_lang]['user_assigned_to_warehouse_thead'];?></span></td>
          <td width="33%">
            <select id="add_user_warehouse_id">
              <option value="0"></option>
<?php
              $query_warehouses = "SELECT `warehouses_types`.`warehouse_type_name`,`warehouses`.`warehouse_id`, `warehouses`.`warehouse_name`
                                  FROM `warehouses`
                                  INNER JOIN `warehouses_types` ON `warehouses_types`.`warehouse_type_id` = `warehouses`.`warehouse_type_id`
                                  ORDER BY `warehouses_types`.`warehouse_type_name` ASC, `warehouses`.`warehouse_name` ASC";
              $result_warehouses = mysqli_query($db_link, $query_warehouses);
              if (!$result_warehouses) echo mysqli_error($db_link);
              if(mysqli_num_rows($result_warehouses) > 0) {
                while($warehouse = mysqli_fetch_assoc($result_warehouses)) {

                  $warehouse_type_name = $warehouse['warehouse_type_name'];
                  $warehouse_id = $warehouse['warehouse_id'];
                  $warehouse_name = $warehouse['warehouse_name'];

                  echo "<option value='$warehouse_id'>$warehouse_type_name - $warehouse_name</option>";
                }
              }
?>
            </select>
          </td>
        </tr>
        <tr>
          <td width="15%"><span><?=$laguages[$default_lang]['user_is_active_thead'];?></span></td>
          <td width="33%">
            <div class="checkbox">
              <input type="checkbox" id="add_user_is_active" onClick="Checkbox(this)" />
            </div>
          </td>
          <td width="15%"><span><?=$laguages[$default_lang]['user_is_ip_in_use_thead'];?></span></td>
          <td width="33%">
            <div class="checkbox">
              <input type="checkbox" id="add_user_is_ip_in_use" onClick="Checkbox(this)" />
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $("#choose_user_type a").click(function() {
      $("#choose_user_type td").removeClass("selected_user_type")
      $(this).parent().addClass("selected_user_type");
      $("#user_details").html("");
      $("#add_user").hide();
      GetUsersForType();
    });
  });
</script>