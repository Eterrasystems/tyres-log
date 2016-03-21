<h1><?=$laguages[$default_lang]['protocols_list'];?></h1>
<div id="protocols_list">
<?php
  $user_id = $_SESSION['tyreslog']['user_id'];
  $user_fullname = $_SESSION['tyreslog']['user_fullname'];
  $current_date = date("Y-m-d");
  $expired_date = new DateTime($current_date);
  $expired_date->sub(new DateInterval('P8M'));
  $expired_date = $expired_date->format('Y-m-d');
  $query_protocols = "SELECT `tyres_storages`.*, `warehouses`.`warehouse_name`,`vehicles_types`.`vehicle_type`,`vehicles_models`.`vehicle_model`,
                              `vehicles_makes`.`vehicle_make`,`warehouse_workers`.`user_firstname`, `warehouse_workers`.`user_lastname`, 
                              `clients`.`user_firstname` as client_firstname, `clients`.`user_lastname` as client_lastname
                      FROM `tyres_storages`
                      INNER JOIN `warehouses` ON `warehouses`.`warehouse_id` = `tyres_storages`.`warehouse_id`
                      INNER JOIN `vehicles_models` ON `vehicles_models`.`vehicle_model_id` = `tyres_storages`.`vehicle_model_id`
                      INNER JOIN `vehicles_makes` ON `vehicles_makes`.`vehicle_make_id` = `vehicles_models`.`vehicle_make_id`
                      INNER JOIN `vehicles_types` ON `vehicles_types`.`vehicle_type_id` = `vehicles_models`.`vehicle_type_id`
                      INNER JOIN `users` as `warehouse_workers` ON `warehouse_workers`.`user_id` = `tyres_storages`.`employer_took_tyres`
                      INNER JOIN `users` as `clients` ON `clients`.`user_id` = `tyres_storages`.`client_id`
                      WHERE `tyre_storage_state` = '2' AND `tyres_storages`.`tyre_storage_datein` >= '$expired_date'
                      ORDER BY `tyres_storages`.`tyre_storage_datein` DESC, `tyres_storages`.`tyre_storage_id` DESC";
  //echo $query_protocols;
  $result_protocols = mysqli_query($db_link, $query_protocols);
  $protocols_count = mysqli_num_rows($result_protocols);
  if($protocols_count > 0) {
    
    // if the results are more then $page_offset
    // making a pagination, finding how many pages will be needed
    $current_page = 1;
    $page_offset = 10;

    if($protocols_count > $page_offset) {
      $page_count = ceil($protocols_count/$page_offset);
    }
    // echo $page_count;
    $div_class = 1;
    $rows_count = 0;
?>
  <table>
    <thead>
      <td width="9%"><?=$laguages[$default_lang]['reception_protocol_thead'];?></td>
      <td width="7%"><?=$laguages[$default_lang]['vehicle_plate_thead'];?></td>
      <td width="10%"><?=$laguages[$default_lang]['warehouse_name_thead'];?></td>
      <td width="13%"><?=$laguages[$default_lang]['warehouse_employer_name_thead'];?></td>
      <td width="13%"><?=$laguages[$default_lang]['client_name_thead'];?></td>
      <td width="10%"><?=$laguages[$default_lang]['vehicle_make_thead'];?></td>
      <td width="10%"><?=$laguages[$default_lang]['vehicle_model_thead'];?></td>
      <td width="10%"><?=$laguages[$default_lang]['warehouse_storage_date_thead'];?></td>
      <td width="5%"><?=$laguages[$default_lang]['details_thead'];?></td>
      <td width="7%"><?=$laguages[$default_lang]['print_thead'];?></td>
      <td width="6%"><?=$laguages[$default_lang]['edit_thead'];?></td>
    </thead>
  </table>
<?php
  $key_1 = 0;
  while($protocols_row = mysqli_fetch_assoc($result_protocols)) {
    //echo"<pre>";print_r($protocols_row);

    if($rows_count == $page_offset) {
      $rows_count = 0;
      $div_class++; 
    }
    if($div_class == 1) $tr_visibility = ""; 
    else $tr_visibility = ' style="display:none;"';

    $tyre_storage_id = $protocols_row['tyre_storage_id'];
    $tyre_storage_id_formatted = sprintf('%010d', $tyre_storage_id);
    $warehouse_name = $protocols_row['warehouse_name'];
    $vehicle_type = $protocols_row['vehicle_type'];
    $vehicle_type = $laguages[$default_lang][$vehicle_type];
    $vehicle_make = $protocols_row['vehicle_make'];
    $vehicle_model = $protocols_row['vehicle_model'];
    $vehicle_plate = $protocols_row['vehicle_plate'];
    $user_firstname = $protocols_row['user_firstname'];
    $user_lastname = $protocols_row['user_lastname'];
    $client_firstname = $protocols_row['client_firstname'];
    $client_lastname = $protocols_row['client_lastname'];
    $tyre_storage_datein = $protocols_row['tyre_storage_datein'];
    $class = ((($key_1 % 2) == 1) ? " even" : " odd");

?>
  <div class="tyre_storage protocols_row row_over_<?php echo $tyre_storage_id;?><?php echo "$class $div_class";?>" <?php echo $tr_visibility;?>>
    <table>
      <tbody>
        <tr>
          <td width="9%"><?php echo $tyre_storage_id_formatted;?></td>
          <td width="7%" style="background: #006DCC !important;color: #fff;"><?php echo $vehicle_plate;?></td>
          <td width="10%"><?php echo $warehouse_name;?></td>
          <td width="13%"><?php echo "$user_firstname $user_lastname";?></td>
          <td width="13%"><?php echo "$client_firstname $client_lastname";?></td>
          <td width="10%"><?php echo $vehicle_make;?></td>
          <td width="10%"><?php echo $vehicle_model;?></td>
          <td width="10%"><?php echo $tyre_storage_datein;?></td>
          <td width="5%"><button class="button show_tyres_details" data-id="<?php echo $tyre_storage_id;?>">+</button></td>
          <td width="7%"><button class="button" onClick="PrintProtocolById('<?php echo $tyre_storage_id;?>')"><?=$laguages[$default_lang]['btn_print'];?></button></td>
          <td width="6%"><button class="button" onClick="GetProtocolDetails('<?php echo $tyre_storage_id;?>')"><?=$laguages[$default_lang]['btn_edit'];?></button></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="tyre_storage details_<?php echo $tyre_storage_id; ?>" style="display: none;">
    <div class="printable_area_01_<?php echo $tyre_storage_id; ?>" style="display: none;">
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
            <td><?=$tyre_storage_datein;?></td>
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
    </div>
<?php
  $query_storage_details = "SELECT `tyres_storages_details`.`tyre_dot`,`tyres_storages_details`.`tyre_grapple_depth`,`tyres_storages_details`.`tyre_defects`, 
                                    `tyres_storages_details`.`tyre_has_rim`,`tyres_storages_details`.`tyre_rim_note`,`tyres_position`.`tyre_position_code`, 
                                    `tyres_makes`.`tyre_make`,`tyres_models`.`tyre_model`,`tyres_seasons`.`tyre_season_code`,`tyres_width`.`tyre_width`, 
                                    `tyres_ratio`.`tyre_ratio`,`tyres_diameter`.`tyre_diameter`,`tyres_load_index`.`tyre_load_index`,`tyres_speed_index`.`tyre_speed_index`
                            FROM `tyres_storages_details`
                            INNER JOIN `tyres_position` ON `tyres_position`.`tyre_position_id` = `tyres_storages_details`.`tyre_position_id`
                            INNER JOIN `tyres_models` ON `tyres_models`.`tyre_model_id` = `tyres_storages_details`.`tyre_model_id`
                            INNER JOIN `tyres_seasons` ON `tyres_seasons`.`tyre_season_id` = `tyres_storages_details`.`tyre_season_id`
                            INNER JOIN `tyres_makes` ON `tyres_makes`.`tyre_make_id` = `tyres_models`.`tyre_make_id`
                            INNER JOIN `tyres_width` ON `tyres_width`.`tyre_width_id` = `tyres_storages_details`.`tyre_width_id`
                            INNER JOIN `tyres_ratio` ON `tyres_ratio`.`tyre_ratio_id` = `tyres_storages_details`.`tyre_ratio_id`
                            INNER JOIN `tyres_diameter` ON `tyres_diameter`.`tyre_diameter_id` = `tyres_storages_details`.`tyre_diameter_id`
                            INNER JOIN `tyres_load_index` ON `tyres_load_index`.`tyre_load_index_id` = `tyres_storages_details`.`tyre_load_index_id`
                            INNER JOIN `tyres_speed_index` ON `tyres_speed_index`.`tyre_speed_index_id` = `tyres_storages_details`.`tyre_speed_index_id`
                            WHERE `tyres_storages_details`.`tyre_storage_id` = '$tyre_storage_id'
                            ORDER BY `tyres_position`.`tyre_position_id` ASC";
  //echo $query_storage_details."<br>";
  $result_storage_details = mysqli_query($db_link, $query_storage_details);
  if(mysqli_num_rows($result_storage_details) > 0) {
?>
    <table>
      <thead>
        <tr>
          <td><?=$laguages[$default_lang]['tyre_position_thead'];?></td>
          <td><?=$laguages[$default_lang]['tyre_make_thead'];?></td>
          <td><?=$laguages[$default_lang]['tyre_model_thead'];?></td>
          <td><?=$laguages[$default_lang]['tyre_season_thead'];?></td>
          <td><?=$laguages[$default_lang]['tyre_width_thead'];?></td>
          <td><?=$laguages[$default_lang]['tyre_ratio_thead'];?></td>
          <td><?=$laguages[$default_lang]['tyre_diameter_thead'];?></td>
          <td><?=$laguages[$default_lang]['tyre_load_index_thead'];?></td>
          <td><?=$laguages[$default_lang]['tyre_speed_index_thead'];?></td>
          <td><?=$laguages[$default_lang]['tyre_dot_thead'];?></td>
          <td><?=$laguages[$default_lang]['tyre_grapple_depth_thead'];?></td>
          <td><?=$laguages[$default_lang]['tyre_defects_thead'];?></td>
          <td><?=$laguages[$default_lang]['tyre_rim_thead'];?></td>
        </tr>
      </thead>
      <tbody>
<?php
      $key = 0;
      $pritablecontent = "";
      while($storage_details_row = mysqli_fetch_assoc($result_storage_details)) {
        //echo"<pre>";print_r($storage_details_row);
          
        $tyre_position_code = $storage_details_row['tyre_position_code'];
        $tyre_make = $storage_details_row['tyre_make'];
        $tyre_model = $storage_details_row['tyre_model'];
        $tyre_season_code = $storage_details_row['tyre_season_code'];
        $tyre_season = $laguages[$default_lang][$tyre_season_code];
        $tyre_width = $storage_details_row['tyre_width'];
        $tyre_ratio = $storage_details_row['tyre_ratio'];
        $tyre_diameter = $storage_details_row['tyre_diameter'];
        $tyre_load_index = $storage_details_row['tyre_load_index'];
        $tyre_speed_index = $storage_details_row['tyre_speed_index'];
        $tyre_dot = $storage_details_row['tyre_dot'];
        $tyre_grapple_depth = $storage_details_row['tyre_grapple_depth'];
        $tyre_defects = $storage_details_row['tyre_defects'];
        $tyre_has_rim_text = ($storage_details_row['tyre_has_rim'] == 0) ? $laguages[$default_lang]['no'] : $laguages[$default_lang]['yes'];
        $tyre_rim_note = $storage_details_row['tyre_rim_note'];
        $class = ((($key % 2) == 1) ? " even" : " odd");

        echo "<tr class='row_over$class'>";
        echo "<td>".$laguages[$default_lang][$tyre_position_code]."</td>";
        echo "<td>$tyre_make</td>";
        echo "<td>$tyre_model</td>";
        echo "<td>$tyre_season</td>";
        echo "<td>$tyre_width</td>";
        echo "<td>$tyre_ratio</td>";
        echo "<td>$tyre_diameter</td>";
        echo "<td>$tyre_load_index</td>";
        echo "<td>$tyre_speed_index</td>";
        echo "<td>$tyre_dot</td>";
        echo "<td>$tyre_grapple_depth mm</td>";
        echo "<td>$tyre_defects</td>";
        echo "<td>$tyre_has_rim_text</td>";
        echo "</tr>";
        
        $pritablecontent .= "<tr><td>".$laguages[$default_lang][$tyre_position_code]."</td>";
        $pritablecontent .= "<td>$tyre_make</td><td>$tyre_model</td><td>$tyre_season</td>";
        $pritablecontent .= "<td>$tyre_width/".$tyre_ratio."R$tyre_diameter</td>";
        $pritablecontent .= "<td>$tyre_dot</td><td>$tyre_load_index</td><td>$tyre_speed_index</td>";
        $pritablecontent .= "<td>$tyre_grapple_depth mm</td><td>$tyre_defects</td><td>$tyre_has_rim_text</td><td>$tyre_rim_note</td></tr>";

        $key++;
      }
?>
      </tbody>
    </table>
    <div class="printable_area_02_<?php echo $tyre_storage_id; ?>" style="display: none;">
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
          <?=$pritablecontent;?>
        </tbody>
      </table>
    </div>
<?php
    } // if(mysqli_num_rows($result_storage_details) > 0)
?>
    </div>
<?php
  $key_1++;
  $rows_count++;
  } // while($protocols_row)
      
  // if the results are more then $page_offset make pagination
  if(isset($page_count)) {
    echo "<tr><td>";
    echo "<div class=\"pagination pagination-centered\"><ul>";
    while($current_page <= $page_count) {
      if($current_page == 1) {
        $li_current = ' class="active"'; 
      }
      else {
        $li_current = "";
      }

      echo "<li$li_current><a style='font-size:90%;padding: 0 6px;' data=\"$current_page\">$current_page</a></li>";
      $current_page++;
    }
    echo "</ul></div>";
    echo "</td></tr>";
  }

  $div_class = 1;
  $rows_count = 0;
} // if(mysqli_num_rows($result_protocols) > 0)
?>
</div>
<div class="clearfix"></div>
<div class="search">
  <h3><?=$laguages[$default_lang]['search'];?></h3>
  <table>
    <tbody>
      <tr>
        <td width="9"><input type="text" id="search_tyre_storage_id"></td>
        <td width="7%" style="background: #006DCC !important;color: #fff;"><input type="text" id="search_vehicle_plate"></td>
        <td width="10%">
          <select id="search_warehouse_id">
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
        <td width="13%">
          <select id="search_employer_id">
            <option value="0"></option>
<?php
            $query = "SELECT `users`.`user_id`, `users`.`user_username`, `users`.`user_is_active`, `users`.`user_is_ip_in_use`, 
                            `users`.`user_firstname`,`users`.`user_lastname`, `users`.`warehouse_id`
                      FROM `users`
                      WHERE `users`.`user_type_id` = '1' AND `users`.`user_is_active` = '1'
                      ORDER BY `users`.`user_firstname` ASC";
            //echo $query;
            $users_result = mysqli_query($db_link, $query);
            if (!$users_result) echo mysqli_error($db_link);
            if(mysqli_num_rows($users_result) > 0) {
              $key = 0;
              while ($user_details = mysqli_fetch_assoc($users_result)) {
                
                $user_id = $user_details['user_id'];
                $user_firstname = $user_details['user_firstname'];
                $user_lastname = $user_details['user_lastname'];

                echo "<option value='$user_id'>$user_firstname $user_lastname</option>";
              }
            }
?>
          </select>
        </td>
        <td width="13%">
          
        </td>
        <td width="10%">
          <select id="search_vehicle_make" onChange="LoadVehicleModelsForMakeInSelect()">
            <option value="0"></option>
<?php
            $query = "SELECT `vehicles_makes`.* FROM `vehicles_makes` ORDER BY `vehicle_make` ASC";
            $result = mysqli_query($db_link, $query);
            if(mysqli_num_rows($result) > 0) {
              $key = 0;
              while($vehicles_makes = mysqli_fetch_assoc($result)) {

                $vehicle_make_id = $vehicles_makes['vehicle_make_id'];
                $vehicle_make = $vehicles_makes['vehicle_make'];

                echo "<option value='$vehicle_make_id'>$vehicle_make</option>";
              }
            }
?>
          </select>
        </td>
        <td width="10%">
          <select id="search_vehicle_model">
            <option value="0"><?=$laguages[$default_lang]['choose_vehicle_make_first'];?></option>
          </select>
        </td>
        <td width="10%"><input type="text" id="search_tyre_storage_datein" class="datepicker"></td>
        <td width="5%"><button class="button" onClick="SearchProtocol()"><?=$laguages[$default_lang]['btn_search'];?></button></td>
        <td width="13%"></td>
      </tr>
    </tbody>
  </table>
</div>
<p>&nbsp;</p>
<div id="protocol_details">
  
</div>
<script type="text/javascript">
  $(document).ready(function() {
    $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
    $(".pagination a").click(function() {
      if($(this).parent().hasClass("active")) {
        // do nothing
      }
      else {
        var tr_class = $(this).attr("data");
        $(".pagination li").removeClass("active");
        $(this).parent().addClass("active");
        $("div.tyre_storage").hide();
        $("div."+tr_class).show();
      }
    });
    $(".show_tyres_details").click(function() {
      var tyre_storage_id = $(this).attr("data-id");
      if($(this).hasClass("active")) {
        $(this).html("+");
        $(this).removeClass("active");
        $(".row_over_"+tyre_storage_id).removeClass("row_over_edit");
        $(".details_"+tyre_storage_id).slideUp();
      }
      else {
        $(this).html("-");
        $(this).addClass("active");
        $(".row_over_"+tyre_storage_id).addClass("row_over_edit");
        $(".details_"+tyre_storage_id).slideDown();
      }
    });
  });
</script>