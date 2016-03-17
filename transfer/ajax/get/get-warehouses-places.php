<?php

  include_once("../../../config.php");
  
  $db_link = DB_OpenI();
  
  check_for_csrf();
  
  //print_r($_POST);EXIT;
  if(isset($_POST['warehouse_type_id'])) {
    $warehouse_type_id = $_POST['warehouse_type_id'];
  }
?>
  <table class="no_margin">
    <thead>
      <tr><td><?=$laguages[$default_lang]['choose_warehouse_thead'];?></td></tr>
    </thead>
  </table>
  <div id="choose_warehouse">
    <table>
      <tbody>
<?php
      $query = "SELECT `warehouses`.* 
                FROM `warehouses` 
                WHERE `warehouse_type_id` = '$warehouse_type_id'
                ORDER BY `warehouses`.`warehouse_name` ASC";
      $result = mysqli_query($db_link, $query);
      if(mysqli_num_rows($result) > 0) {
        while($warehouses = mysqli_fetch_assoc($result)) {

          $warehouse_id = $warehouses['warehouse_id'];
          $warehouse_name = stripcslashes($warehouses['warehouse_name']);

          echo "<tr><td><a data-id='$warehouse_id'>$warehouse_name</a></td></tr>";
        }
      }
      else {   
?>
        <tr><?=$laguages[$default_lang]['no_warehouse_yet'];?></tr>
<?php    
      }
?>
      </tbody>
    </table>
  </div>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#choose_warehouse a").click(function() {
        $("#choose_warehouse td").removeClass("selected_warehouse")
        $(this).parent().addClass("selected_warehouse");
        GetWarehousesPlacesWithTyresStorage();
      });
    });
  </script>
<?php
  DB_CloseI($db_link);
?>