<!--begin of left_col-->
<div id="left_col">
<?php
  $db_link = DB_OpenI();
?>
  <table class="no_margin">
    <thead>
      <tr><td><?=$laguages[$default_lang]['choose_vehicle_type_thead'];?></td></tr>
    </thead>
  </table>
  <div id="choose_vehicle_type">
    <table>
      <tbody>
<?php
      $query = "SELECT `vehicles_types`.* FROM `vehicles_types` ORDER BY `vehicle_type` ASC";
      $result = mysqli_query($db_link, $query);
      if(mysqli_num_rows($result) > 0) {
        while($vehicles_types = mysqli_fetch_assoc($result)) {

          $vehicle_type_id = $vehicles_types['vehicle_type_id'];
          $vehicle_type = $vehicles_types['vehicle_type'];
          $vehicle_type = $laguages[$default_lang][$vehicle_type];

          echo "<tr><td><a data-id='$vehicle_type_id'>$vehicle_type</a></td></tr>";
        }
      }
      else {   
?>
        <tr><td><?=$laguages[$default_lang]['no_vehicle_types_yet'];?></td></tr>
<?php    
      }
?>
      </tbody>
    </table>
  </div>

  <div id="vehicle_makes_list">
    
  </div>
  
</div>
<!--end of left_col-->

<div id="right_col">

  <div id="vehicle_models_list">

  </div>
  
  <div id="add_new_vehicle_model">

  </div>
  
  <div id="add_vehicle_model_field" class="add_new_form" style="display: none;">
    <h3><?=$laguages[$default_lang]['form_add_new'];?></h3>
    <table>
      <tr class="row_over">
        <td width="7%"><button class="button btn_save" onClick="AddVehicleMоdel()"><?=$laguages[$default_lang]['btn_save'];?></button></td>
        <td width="30%"><input type="text"  id="add_vehicle_model" /></td>
        <td></td>
      </tr>
    </table>
  </div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $("#choose_vehicle_type a").click(function() {
      $("#choose_vehicle_type td").removeClass("selected_vehicle_type")
      $(this).parent().addClass("selected_vehicle_type");
      $("#vehicle_models_list").html("");
      $("#add_vehicle_model_field").hide();
      GetVehicleMakesListForType();
    });
    $("#choose_vehicle_make a").click(function() {
      $("#choose_vehicle_make td").removeClass("selected_vehicle_make")
      $(this).parent().addClass("selected_vehicle_make");
      GetVehicleModels();
    });
  });
</script>