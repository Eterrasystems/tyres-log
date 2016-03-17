<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_ratio_id'])) {
    $selected_tyre_ratio_id = $_POST['tyre_ratio_id'];
  }
  
  $query = "SELECT `tyres_diameter`.`tyre_diameter_id`, `tyres_diameter`.`tyre_diameter` 
            FROM `tyres_diameter_to_ratio`
            INNER JOIN `tyres_diameter` ON `tyres_diameter`.`tyre_diameter_id` = `tyres_diameter_to_ratio`.`tyre_diameter_id`
            WHERE `tyres_diameter_to_ratio`.`tyre_ratio_id` = '$selected_tyre_ratio_id'
            ORDER BY `tyres_diameter`.`tyre_diameter_order` ASC";
  //echo $query;
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    
    echo "<option value='0' selected='selected'>".$laguages[$default_lang]['choose_tyre_diameter']."</option>";
    
    while($row = mysqli_fetch_assoc($result)) {
      $tyre_diameter_id = $row['tyre_diameter_id'];
      $tyre_diameter = $row['tyre_diameter'];

      echo "<option value='$tyre_diameter_id'>$tyre_diameter</option>";
    }
  }
  else {   
?>
    <option selected="selected"><?=$laguages[$default_lang]['no_tyre_diameters_yet'];?></option>
<?php    
  }
  
  
  DB_CloseI($db_link);
?>
<script type="text/javascript">
  $(document).ready(function() {
    $( ".tyre_diameter" ).change(function() {
      var tyre_diameter_id = $(this).val();
      $(".tyre_form.active .tyre_diameter").val(tyre_diameter_id);
    });
  });
</script>