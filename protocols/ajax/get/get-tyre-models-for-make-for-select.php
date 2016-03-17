<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['tyre_make_id'])) {
    $selected_tyre_make_id = $_POST['tyre_make_id'];
  }

  $query = "SELECT `tyres_models`.* 
            FROM `tyres_models` 
            WHERE `tyre_make_id` = '$selected_tyre_make_id'
            ORDER BY `tyre_model` ASC";
  //echo $query;
  $result = mysqli_query($db_link, $query);
  if(mysqli_num_rows($result) > 0) {
    
    echo "<option value='0'  selected='selected'>".$laguages[$default_lang]['choose_model']."</option>";
    while($tyres_models = mysqli_fetch_assoc($result)) {
      
      $tyre_model_id = $tyres_models['tyre_model_id'];
      $tyre_model = $tyres_models['tyre_model'];
      
      echo "<option value='$tyre_model_id'>$tyre_model</option>";
    }
?>
<script type="text/javascript">
$(document).ready(function() {
  $( ".tyre_model" ).change(function() {
    if($("#copy_to_next_forms").is(":checked") == "1") {
      var tyre_model_id = $(this).val();
      $(".tyre_form.active .tyre_model").val(tyre_model_id);
    }
  });
});
</script>
<?php
  }
  else {   
?>
    <option selected="selected"><?=$laguages[$default_lang]['no_models_yet'];?></option>
<?php    
  }
  
  DB_CloseI($db_link);