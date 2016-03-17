<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['vehicle_type_id'])) {
    $selected_vehicle_type_id = $_POST['vehicle_type_id'];
  }
  
  $query = "SELECT `tyres_width`.`tyre_width_id`, `tyres_width`.`tyre_width` 
            FROM `tyres_width_to_vehicle_type`
            INNER JOIN `tyres_width` ON `tyres_width`.`tyre_width_id` = `tyres_width_to_vehicle_type`.`tyre_width_id`
            WHERE `tyres_width_to_vehicle_type`.`vehicle_type_id` = '$selected_vehicle_type_id'
            ORDER BY `tyres_width`.`tyre_width_order` ASC";
  //echo $query;
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    
    echo "<option value='0' selected='selected'>".$laguages[$default_lang]['choose_tyre_width']."</option>";
    
    while($row = mysqli_fetch_assoc($result)) {
      $tyre_width_id = $row['tyre_width_id'];
      $tyre_width = $row['tyre_width'];

      echo "<option value='$tyre_width_id'>$tyre_width</option>";
    }
  }
  else {   
?>
    <option selected="selected"><?=$laguages[$default_lang]['form_no_tyre_widths_yet'];?></option>
<?php    
  }
  
  DB_CloseI($db_link);
?>
<script type="text/javascript">
  $(document).ready(function() {
    $("#choose_tyre_width a").click(function() {
      $("#choose_tyre_width td").removeClass("selected_tyre_width")
      $(this).parent().addClass("selected_tyre_width");
      GetTyreDiametersForRatio();
    });
  });
</script>