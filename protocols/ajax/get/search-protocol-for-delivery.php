<?php
  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
//  echo"<pre>";print_r($_POST);exit;
//  $tyre_storage_id = "";
//  $vehicle_plate = "";
//  $warehouse_id = "";
//  $user_id = "";
//  $vehicle_make_id = "";
//  $vehicle_model_id = "";
//  tyre_storage_date = "";
  
  if(isset($_POST['tyre_storage_id'])) {
    $tyre_storage_id = $_POST['tyre_storage_id'];
  }
  if(isset($_POST['vehicle_plate'])) {
    $vehicle_plate = $_POST['vehicle_plate'];
  }
  if(isset($_POST['warehouse_id'])) {
    $warehouse_id = $_POST['warehouse_id'];
  }
  if(isset($_POST['employer_id'])) {
    $employer_id = $_POST['employer_id'];
  }
  if(isset($_POST['vehicle_make_id'])) {
    $vehicle_make_id = $_POST['vehicle_make_id'];
  }
  if(isset($_POST['vehicle_model_id'])) {
    $vehicle_model_id = $_POST['vehicle_model_id'];
  }
  if(isset($_POST['tyre_storage_date'])) {
    $tyre_storage_date = $_POST['tyre_storage_date'];
  }
  
  $current_date = date("Y-m-d");
  $expired_date = new DateTime($current_date);
  $expired_date->sub(new DateInterval('P8M'));
  $expired_date = $expired_date->format('Y-m-d');
  
  $where = false;
  $and = false;
  
  $query_protocols = "SELECT `tyres_storages`.*, `warehouses`.`warehouse_name`, `vehicles_models`.`vehicle_model`, `vehicles_makes`.`vehicle_make`,
                    `warehouse_workers`.`user_firstname`, `warehouse_workers`.`user_lastname`, 
                    `clients`.`user_firstname` as client_firstname, `clients`.`user_lastname` as client_lastname
            FROM `tyres_storages`
            INNER JOIN `warehouses` ON `warehouses`.`warehouse_id` = `tyres_storages`.`warehouse_id`
            INNER JOIN `vehicles_models` ON `vehicles_models`.`vehicle_model_id` = `tyres_storages`.`vehicle_model_id`
            INNER JOIN `vehicles_makes` ON `vehicles_makes`.`vehicle_make_id` = `vehicles_models`.`vehicle_make_id`
            INNER JOIN `users` as `warehouse_workers` ON `warehouse_workers`.`user_id` = `tyres_storages`.`user_id`
            INNER JOIN `users` as `clients` ON `clients`.`user_id` = `tyres_storages`.`client_id` 
            WHERE `tyres_storages`.`tyre_storage_id` IS NOT NULL";
  if(!empty($tyre_storage_id)) {
    $query_protocols .= " AND (";
    $query_protocols .= " `tyres_storages`.`tyre_storage_id` = '$tyre_storage_id'";
    $and = true;
  }
  if(!empty($vehicle_plate)) {
    $query_protocols .= ($and) ? " AND" : " AND (";
    $query_protocols .= " `tyres_storages`.`vehicle_plate` LIKE '%$vehicle_plate%'";
    $and = true;
  }
  if(!empty($warehouse_id)) {
    $query_protocols .= ($and) ? " AND" : " AND (";
    $query_protocols .= " `warehouses`.`warehouse_id` = '$warehouse_id'";
    $and = true;
  }
  if(!empty($employer_id)) {
    $query_protocols .= ($and) ? " AND" : " AND (";
    $query_protocols .= " `warehouse_workers`.`user_id` = '$employer_id'";
    $and = true;
  }
  if(!empty($vehicle_make_id)) {
    $query_protocols .= ($and) ? " AND" : " AND (";
    $query_protocols .= " `vehicles_makes`.`vehicle_make_id` = '$vehicle_make_id'";
    $and = true;
  }
  if(!empty($vehicle_model_id)) {
    $query_protocols .= ($and) ? " AND" : " AND (";
    $query_protocols .= " `vehicles_models`.`vehicle_model_id` = '$vehicle_model_id'";
    $and = true;
  }
  if(!empty($tyre_storage_date)) {
    $query_protocols .= ($and) ? " AND" : " AND (";
    $query_protocols .= " `tyres_storages`.`tyre_storage_date` = '$tyre_storage_date'";
    $and = true;
  }
  $query_protocols .= ($and) ? ")" : "";
  //echo $query_protocols;exit;
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
      <td width="10%"><?=$laguages[$default_lang]['reception_protocol_thead'];?></td>
      <td width="10%"><?=$laguages[$default_lang]['vehicle_plate_thead'];?></td>
      <td width="10%"><?=$laguages[$default_lang]['warehouse_name_thead'];?></td>
      <td width="13%"><?=$laguages[$default_lang]['warehouse_employer_name_thead'];?></td>
      <td width="13%"><?=$laguages[$default_lang]['client_name_thead'];?></td>
      <td width="10%"><?=$laguages[$default_lang]['vehicle_make_thead'];?></td>
      <td width="10%"><?=$laguages[$default_lang]['vehicle_model_thead'];?></td>
      <td width="10%"><?=$laguages[$default_lang]['warehouse_storage_date_thead'];?></td>
      <td width="5%"><?=$laguages[$default_lang]['details_thead'];?></td>
      <td width="9%"><?=$laguages[$default_lang]['edit_thead'];?></td>
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
    $warehouse_name = $protocols_row['warehouse_name'];
    $vehicle_make = $protocols_row['vehicle_make'];
    $vehicle_model = $protocols_row['vehicle_model'];
    $vehicle_plate = $protocols_row['vehicle_plate'];
    $user_firstname = $protocols_row['user_firstname'];
    $user_lastname = $protocols_row['user_lastname'];
    $client_firstname = $protocols_row['client_firstname'];
    $client_lastname = $protocols_row['client_lastname'];
    $tyre_storage_date = $protocols_row['tyre_storage_date'];
    $class = ((($key_1 % 2) == 1) ? " even" : " odd");

?>
  <div class="tyre_storage protocols_row row_over_<?php echo $tyre_storage_id;?><?php echo "$class $div_class";?>" <?php echo $tr_visibility;?>>
    <table>
      <tbody>
        <tr>
          <td width="10%"><?php echo $tyre_storage_id;?></td>
          <td width="10%" style="background: #006DCC !important;color: #fff;"><?php echo $vehicle_plate;?></td>
          <td width="10%"><?php echo $warehouse_name;?></td>
          <td width="13%"><?php echo "$user_firstname $user_lastname";?></td>
          <td width="13%"><?php echo "$client_firstname $client_lastname";?></td>
          <td width="10%"><?php echo $vehicle_make;?></td>
          <td width="10%"><?php echo $vehicle_model;?></td>
          <td width="10%"><?php echo $tyre_storage_date;?></td>
          <td width="5%"><button class="button show_tyres_details" data-id="<?php echo $tyre_storage_id;?>">+</button></td>
          <td width="5%"><button class="button" onClick="PrintProtocolById('<?php echo $tyre_storage_id;?>')"><?=$laguages[$default_lang]['btn_print'];?></button></td>
          <td width="5%"><button class="button" onClick="GetProtocolDetailsForDelivery('<?php echo $tyre_storage_id;?>')"><?=$laguages[$default_lang]['btn_edit'];?></button></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="tyre_storage details_<?php echo $tyre_storage_id; ?>" style="display: none;">
<?php
  $query_storage_details = "SELECT `tyres_storages_details`.`tyre_dot`, `tyres_storages_details`.`tyre_grapple_depth`, `tyres_storages_details`.`tyre_defects`, 
                                    `tyres_storages_details`.`tyre_has_rim`,`tyres_position`.`tyre_position_code`, `tyres_makes`.`tyre_make`,
                                    `tyres_models`.`tyre_model`, `tyres_seasons`.`tyre_season`, `tyres_width`.`tyre_width`, `tyres_ratio`.`tyre_ratio`, 
                                    `tyres_diameter`.`tyre_diameter`, `tyres_load_index`.`tyre_load_index`, `tyres_speed_index`.`tyre_speed_index`
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
      while($storage_details_row = mysqli_fetch_assoc($result_storage_details)) {
        //echo"<pre>";print_r($storage_details_row);
          
        $tyre_position_code = $storage_details_row['tyre_position_code'];
        $tyre_make = $storage_details_row['tyre_make'];
        $tyre_model = $storage_details_row['tyre_model'];
        $tyre_season = $storage_details_row['tyre_season'];
        $tyre_width = $storage_details_row['tyre_width'];
        $tyre_ratio = $storage_details_row['tyre_ratio'];
        $tyre_diameter = $storage_details_row['tyre_diameter'];
        $tyre_load_index = $storage_details_row['tyre_load_index'];
        $tyre_speed_index = $storage_details_row['tyre_speed_index'];
        $tyre_dot = $storage_details_row['tyre_dot'];
        $tyre_grapple_depth = $storage_details_row['tyre_grapple_depth'];
        $tyre_defects = $storage_details_row['tyre_defects'];
        $tyre_has_rim_text = ($storage_details_row['tyre_has_rim'] == 0) ? "Не" : "Да";
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

        $key++;
      }
?>
      </tbody>
    </table>
<?php
    } // if(mysqli_num_rows($result_storage_details) > 0)
?>
    </div>
<?php
  $key_1++;
  $rows_count++;
  } // while($protocols_row)
      
  // if there are more then 30 records make pagination
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
?>
<script type="text/javascript">
  $(document).ready(function() {
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
<?php
} // if(mysqli_num_rows($result_protocols) > 0)
else {
  echo $laguages[$default_lang]['protocols_search_no_result'];
}
?>