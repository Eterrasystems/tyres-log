<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_width_id'])) {
    $selected_tyre_width_id = $_POST['tyre_width_id'];
  }
  
  $query = "SELECT `tyres_ratio`.`tyre_ratio_id`, `tyres_ratio`.`tyre_ratio` 
            FROM `tyres_ratio_to_width`
            INNER JOIN `tyres_ratio` ON `tyres_ratio`.`tyre_ratio_id` = `tyres_ratio_to_width`.`tyre_ratio_id`
            WHERE `tyres_ratio_to_width`.`tyre_width_id` = '$selected_tyre_width_id'
            ORDER BY `tyres_ratio`.`tyre_ratio_order` ASC";
  //echo $query;
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    
    echo "<option value='0' selected='selected'>".$laguages[$default_lang]['choose_tyre_ratio']."</option>";
    
    while($row = mysqli_fetch_assoc($result)) {
      $tyre_ratio_id = $row['tyre_ratio_id'];
      $tyre_ratio = $row['tyre_ratio'];

      echo "<option value='$tyre_ratio_id'>$tyre_ratio</option>";
    }
  }
  else {   
?>
    <option selected="selected"><?=$laguages[$default_lang]['no_tyre_ratios_yet'];?></option>
<?php    
  }
  
  
  DB_CloseI($db_link);
?>
<script type="text/javascript">
  $(document).ready(function() {
    $("#choose_tyre_ratio a").click(function() {
      $("#choose_tyre_ratio td").removeClass("selected_tyre_ratio")
      $(this).parent().addClass("selected_tyre_ratio");
      GetTyreDiametersForRatio();
    });
  });
</script>