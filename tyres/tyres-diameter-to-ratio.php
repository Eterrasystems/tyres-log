<!--begin of left_col-->
<div id="left_col">
<?php
  $db_link = DB_OpenI();
?>
  <table class="no_margin">
    <thead>
      <tr><td><?=$laguages[$default_lang]['choose_tyre_width_thead'];?></td></tr>
    </thead>
  </table>
  <div id="choose_tyre_width">
    <table>
      <tbody>
<?php
      $query_tyre_width = "SELECT `tyre_width_id`,`tyre_width` FROM `tyres_width` ORDER BY `tyre_width_order` ASC";
      $result_tyre_width = mysqli_query($db_link, $query_tyre_width);
      $tyre_widths_count = mysqli_num_rows($result_tyre_width);
      if($tyre_widths_count > 0) {
        
        // if the results are more then $page_offset
        // making a pagination, finding how many pages will be needed
        $current_page = 1;
        $page_offset = 20;

        if($tyre_widths_count > $page_offset) {
          $page_count = ceil($tyre_widths_count/$page_offset);
        }
        // echo $page_count;
        $div_class = 1;
        $rows_count = 0;
      
        while($tyres_widths = mysqli_fetch_assoc($result_tyre_width)) {

          $tyre_width_id = $tyres_widths['tyre_width_id'];
          $tyre_width = $tyres_widths['tyre_width'];
          
          if($rows_count == $page_offset) {
            $rows_count = 0;
            $div_class++; 
          }
          if($div_class == 1) $tr_visibility = ""; 
          else $tr_visibility = ' style="display:none;"';
          

          echo "<tr class='tyre_width $div_class' $tr_visibility><td><a data-id='$tyre_width_id'>$tyre_width</a></td></tr>";
          $rows_count++;
        }
        
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
      }
      else {   
?>
        <tr><td><?=$laguages[$default_lang]['no_tyre_widths_yet'];?></td></tr>
<?php    
      }
?>
      </tbody>
    </table>
  </div>
    
  <div id="tyres_ratios_list">
    
  </div>
  
</div>
<!--end of left_col-->

<div id="right_col">

  <div id="tyres_diameters_checkboxes">

  </div>

</div>
<div class="clearfix"></div>
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
        $("tr.tyre_width").hide();
        $("tr."+tr_class).show();
      }
    });
    $("#choose_tyre_width .tyre_width a").click(function() {
      $("#choose_tyre_width .tyre_width td").removeClass("selected_tyre_width")
      $(this).parent().addClass("selected_tyre_width");
      GetTyreRatiosForWidth('list');
    });
  });
</script>