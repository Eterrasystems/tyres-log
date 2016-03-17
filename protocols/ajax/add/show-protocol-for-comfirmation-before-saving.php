<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //echo"<pre>";print_r($_POST);exit;
  $user_id = $_SESSION['tyreslog']['user_id'];
  $user_fullname = $_SESSION['tyreslog']['user_fullname'];
  if(isset($_POST['tyre_storage_id'])) {
    $tyre_storage_id = $_POST['tyre_storage_id'];
  }
  if(isset($_POST['client_name'])) {
    $client_name = $_POST['client_name'];
  }
  if(isset($_POST['vehicle_type'])) {
    $vehicle_type = $_POST['vehicle_type'];
  }
  if(isset($_POST['vehicle_make'])) {
    $vehicle_make = $_POST['vehicle_make'];
  }
  if(isset($_POST['vehicle_model'])) {
    $vehicle_model = $_POST['vehicle_model'];
  }
  if(isset($_POST['vehicle_plate'])) {
    $vehicle_plate = $_POST['vehicle_plate'];
  }
  if(isset($_POST['warehouse_name'])) {
    $warehouse_name = $_POST['warehouse_name'];
  }
  if(isset($_POST['date_insert'])) {
    $tyre_storage_date = $_POST['date_insert'];
  }
  if(isset($_POST['tyre_positions'])) {
    $tyre_positions = $_POST['tyre_positions'];
  }
  if(isset($_POST['tyre_makes'])) {
    $tyre_makes = $_POST['tyre_makes'];
  }
  if(isset($_POST['tyre_models'])) {
    $tyre_models = $_POST['tyre_models'];
  }
  if(isset($_POST['tyre_seasons'])) {
    $tyre_seasons = $_POST['tyre_seasons'];
  }
  if(isset($_POST['tyre_widths'])) {
    $tyre_widths = $_POST['tyre_widths'];
  }
  if(isset($_POST['tyre_ratios'])) {
    $tyre_ratios = $_POST['tyre_ratios'];
  }
  if(isset($_POST['tyre_diameters'])) {
    $tyre_diameters = $_POST['tyre_diameters'];
  }
  if(isset($_POST['tyre_load_indexes'])) {
    $tyre_load_indexes = $_POST['tyre_load_indexes'];
  }
  if(isset($_POST['tyre_speed_indexes'])) {
    $tyre_speed_indexes = $_POST['tyre_speed_indexes'];
  }
  if(isset($_POST['tyre_dots'])) {
    $tyre_dots = $_POST['tyre_dots'];
  }
  if(isset($_POST['tyre_grapple_depths'])) {
    $tyre_grapple_depths = $_POST['tyre_grapple_depths'];
  }
  if(isset($_POST['tyre_note'])) {
    $tyre_note = prepare_for_null_row(mysqli_real_escape_string($db_link,$_POST['tyre_note']));
  }
  if(isset($_POST['tyre_defects'])) {
    $tyre_defects = $_POST['tyre_defects'];
  }
  if(isset($_POST['tyre_has_rim'])) {
    $tyre_has_rims = $_POST['tyre_has_rim'];
  }
  if(isset($_POST['tyre_rim_note'])) {
    $tyre_rim_notes = $_POST['tyre_rim_note'];
  }
  
?>

<div id="printable_area">
  <h1 style="text-align: center;"><?=$laguages[$default_lang]['header_protocol_print']." $tyre_storage_id";?></h1>
  <h2 style="margin: 20px 0;text-align: center;"><?=$laguages[$default_lang]['company_name'];?></h2>
  <table>
    <thead>
      <tr>
        <td><?=$laguages[$default_lang]['print_protocol_date_in'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_warehouse'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_employer'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_employer_signature'];?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?=$tyre_storage_date;?></td>
        <td><?=$warehouse_name;?></td>
        <td><?=$user_fullname;?></td>
        <td></td>
      </tr>
    </tbody>
  </table>
  <h2 style="margin: 20px 0;text-align: center;"><?=$vehicle_type;?></h2>
  <table>
    <thead>
      <tr>
        <td><?=$laguages[$default_lang]['print_protocol_vehicle_make'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_vehicle_model'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_vehicle_reg_plate'];?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?=$vehicle_make;?></td>
        <td><?=$vehicle_model;?></td>
        <td><?=$vehicle_plate;?></td>
      </tr>
    </tbody>
  </table>
  <h2 style="margin: 20px 0;text-align: center;"><?=$laguages[$default_lang]['header_protocol_tyres'];?></h2>
  <table>
    <thead>
      <tr>
        <td><?=$laguages[$default_lang]['print_protocol_tyre_position'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_tyre_make'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_tyre_model'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_tyre_season'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_tyre_dimensions'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_tyre_dot'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_tyre_load_index'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_tyre_speed_index'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_tyre_grapple_depth'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_tyre_defects'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_tyre_has_rim'];?></td>
        <td><?=$laguages[$default_lang]['print_protocol_tyre_rim_defects'];?></td>
      </tr>
    </thead>
    <tbody>
<?php
    foreach($tyre_positions as $key => $tyre_position) {
      
      $tyre_make = $tyre_makes[$key];
      $tyre_model = $tyre_models[$key];
      $tyre_season = $tyre_seasons[$key];
      $tyre_width = $tyre_widths[$key];
      $tyre_ratio = $tyre_ratios[$key];
      $tyre_diameter = $tyre_diameters[$key];
      $tyre_load_index =  $tyre_load_indexes[$key];
      $tyre_speed_index = $tyre_speed_indexes[$key];
      $tyre_dot = $tyre_dots[$key];
      $tyre_grapple_depth = $tyre_grapple_depths[$key];
      $tyre_defects_text = $tyre_defects[$key];
      $tyre_has_rim = ($tyre_has_rims[$key] == 0) ? $laguages[$default_lang]['no'] : $laguages[$default_lang]['yes'];;
      $tyre_rim_note = $tyre_rim_notes[$key];
?>
      <tr>
        <td><?=$tyre_position;?></td>
        <td><?=$tyre_make;?></td>
        <td><?=$tyre_model;?></td>
        <td><?=$tyre_season;?></td>
        <td><?="$tyre_width/$tyre_ratio"."R$tyre_diameter";?></td>
        <td><?=$tyre_dot;?></td>
        <td><?=$tyre_load_index;?></td>
        <td><?=$tyre_speed_index;?></td>
        <td><?=$tyre_grapple_depth." mm";?></td>
        <td><?=$tyre_defects_text;?></td>
        <td><?=$tyre_has_rim;?></td>
        <td><?=$tyre_rim_note;?></td>
      </tr> 
<?php
    }
?>
    </tbody>
  </table>
</div>
<p style="margin-bottom: 7%;"></p>
<div id="choice_btns">
  <p id="message"></p>
  <a id="confirm_protocol" onClick="AddTyresToWarehouse()"><?=$laguages[$default_lang]['btn_confirm'];?></a>
  <a id="cancel_protocol" class="close"><?=$laguages[$default_lang]['btn_cancel'];?></a>
  <a id="print_protocol" style="display:none;" onClick="PrintProtocol();"><?=$laguages[$default_lang]['btn_print'];?></a>
</div>

