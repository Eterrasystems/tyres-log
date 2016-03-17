<!--begin of left_col-->
<div id="left_col">
<?php
  $db_link = DB_OpenI();
?>
  <table class="no_margin">
    <thead>
      <tr><td><?=$laguages[$default_lang]['choose_warehouse_type_thead'];?></td></tr>
    </thead>
  </table>
  <div id="choose_warehouse_type">
    <table>
      <tbody>
<?php
      $query = "SELECT `warehouse_type_id`, `warehouse_type_name` FROM `warehouses_types` ORDER BY `warehouse_type_name` ASC";
      $result = mysqli_query($db_link, $query);
      if(mysqli_num_rows($result) > 0) {
        while($warehouses_types = mysqli_fetch_assoc($result)) {

          $warehouse_type_id = $warehouses_types['warehouse_type_id'];
          $warehouse_type_name = stripslashes($warehouses_types['warehouse_type_name']);

          echo "<tr><td><a data-id='$warehouse_type_id'>$warehouse_type_name</a></td></tr>";
        }
      }
      else {   
?>
        <tr><?=$laguages[$default_lang]['no_warehouses_types_yet'];?></tr>
<?php    
      }
?>
      </tbody>
    </table>
  </div>
  
  <div id="warehouses_list">
    
  </div>
  
</div>
<!--end of left_col-->

<div id="right_col">

  <div id="warehouses_storages">

  </div>
  
</div>
<div class="clearfix"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $("#choose_warehouse_type a").click(function() {
      $("#choose_warehouse_type td").removeClass("selected_warehouse_type")
      $(this).parent().addClass("selected_warehouse_type");
      $("#warehouses_storages").html("");
      GetWarehousesPlacesForStorage();
    });
  });
</script>