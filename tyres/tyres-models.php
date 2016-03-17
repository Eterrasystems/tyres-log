<!--begin of left_col-->
<div id="left_col">
<?php
  $db_link = DB_OpenI();
?>
  <table class="no_margin">
    <thead>
      <tr><td><?=$laguages[$default_lang]['choose_tyre_make_thead'];?></td></tr>
    </thead>
  </table>
  <div id="choose_tyre_make">
    <table>
      <tbody>
<?php
      $query = "SELECT `tyre_make_id`,`tyre_make` FROM `tyres_makes` ORDER BY `tyre_make` ASC";
      $result = mysqli_query($db_link, $query);
      if(mysqli_num_rows($result) > 0) {
        $key = 0;
        while($vehicles_makes = mysqli_fetch_assoc($result)) {

          $tyre_make_id = $vehicles_makes['tyre_make_id'];
          $tyre_make = $vehicles_makes['tyre_make'];

          echo "<tr><td><a data-id='$tyre_make_id'>$tyre_make</a></td></tr>";
        }
      }
      else {   
?>
        <tr><?php echo NO_TYRE_MAKES_YET; ?></tr>
<?php    
      }
?>
      </tbody>
    </table>
  </div>
  
</div>
<!--end of left_col-->

<div id="right_col">

  <div id="tyre_models_list">

  </div>

</div>
<div class="clearfix"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $("#choose_tyre_make a").click(function() {
      $("#choose_tyre_make td").removeClass("selected_tyre_make")
      $(this).parent().addClass("selected_tyre_make");
      GetTyreModelsForMake();
    });
  });
</script>