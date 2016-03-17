<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['vehicle_type_id'])) {
    $selected_vehicle_type_id = $_POST['vehicle_type_id'];
  }
?>

  <table class="no_margin">
    <thead>
      <tr><td><?=$laguages[$default_lang]['choose_vehicle_make_thead'];?></td></tr>
    </thead>
  </table>
  <div id="choose_vehicle_make">
    <table>
      <tbody>
<?php
      $query = "SELECT `vehicles_makes`.`vehicle_make_id`, `vehicles_makes`.`vehicle_make` 
                FROM `vehicles_makes` 
                INNER JOIN `vehicles_makes_to_type` ON `vehicles_makes_to_type`.`vehicle_make_id` = `vehicles_makes`.`vehicle_make_id`
                WHERE `vehicles_makes_to_type`.`vehicle_type_id` = '$selected_vehicle_type_id'
                ORDER BY `vehicles_makes`.`vehicle_make` ASC";
      $result = mysqli_query($db_link, $query);
      if(mysqli_num_rows($result) > 0) {
        while($vehicles_makes = mysqli_fetch_assoc($result)) {

          $vehicle_make_id = $vehicles_makes['vehicle_make_id'];
          $vehicle_make = $vehicles_makes['vehicle_make'];

          echo "<tr><td><a data-id='$vehicle_make_id'>$vehicle_make</a></td></tr>";
        }
      }
      else {   
?>
        <tr><td><?=$laguages[$default_lang]['no_vehicle_makes_yet'];?></td></tr>
<?php    
      }
  
  DB_CloseI($db_link);
?>
      </tbody>
    </table>
  </div>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#choose_vehicle_make a").click(function() {
        $("#choose_vehicle_make td").removeClass("selected_vehicle_make")
        $(this).parent().addClass("selected_vehicle_make");
        GetVehicleModels();
      });
    });
  </script>