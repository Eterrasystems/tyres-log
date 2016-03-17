<!--begin of left_col-->
<div id="left_col">
<?php
  $db_link = DB_OpenI();
?>
  <table class="no_margin">
    <thead>
      <tr><td><?=$laguages[$default_lang]['choose_client_thead'];?></td></tr>
    </thead>
  </table>
  <div id="choose_client">
    <table>
      <tbody>
<?php
      $query = "SELECT `users`.`user_id`, `users`.`user_firstname`,`users`.`user_lastname`
                FROM `users`
                WHERE `users`.`user_type_id` = '3'
                ORDER BY `users`.`user_firstname` ASC";
      //echo $query;
      $users_result = mysqli_query($db_link, $query);
      if (!$users_result) echo mysqli_error($db_link);
      if(mysqli_num_rows($users_result) > 0) {
        while ($user_details = mysqli_fetch_assoc($users_result)) {
          $user_id = $user_details['user_id'];
          $user_firstname = $user_details['user_firstname'];
          $user_lastname = $user_details['user_lastname'];

          echo "<tr><td><a data-id='$user_id'>$user_firstname $user_lastname</a></td></tr>";
        }
      }
      else {   
?>
        <tr><?php echo NO_CLIENTS_YET; ?></tr>
<?php    
      }
?>
      </tbody>
    </table>
  </div>
  
</div>
<!--end of left_col-->

<div id="right_col">

  <div id="clients_car_plates">

  </div>
  
  <div id="add_new_car_plate">

  </div>
  
  <div id="add_car_plate_field" class="add_new_form" style="display: none;">
    <h3><?=$laguages[$default_lang]['form_add_new'];?></h3>
    <table>
      <tr class="row_over">
        <td width="7%"><button class="button btn_save" onClick="AddVehiclePlate()"><?=$laguages[$default_lang]['btn_save'];?></button></td>
        <td width="30%"><input type="text"  id="add_car_plate" /></td>
        <td></td>
      </tr>
    </table>
  </div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $("#choose_client a").click(function() {
      $("#choose_client td").removeClass("selected_client")
      $(this).parent().addClass("selected_client");
      GetClientCarPlates();
    });
  });
</script>