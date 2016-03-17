<?php
  include_once("../config.php");
  
  $db_link = DB_OpenI();
  
  //check_for_csrf();
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<style>
body {
  width: 98.5%;
  padding:0 0.5%;
  font-family: Arial, sans-serif;	
  font-size:70%;
  line-height: 16px;
  text-align: left;
  color:#333;
  background-color: #fff;
}
h1 {
  margin: 30px 0 20px;
  font-size:20px;
}
ol {
  margin:0;
  padding: 0;
  list-style-position: inside;
}
ol li {
  font-size:16px;
  font-weight: bold;
}
h2 {
  margin: 0 0 8px;
  font-size:16px;
}
h3 {
  margin: 10px 0 8px;
  font-size:15px;
}
p {
  margin:0 0 8px;
}
small {
  font-size:8px;
}
sup {
  position: relative;
  top: -3px;
  font-size: 70%;
}
.btn {
  margin-bottom: 1px;
  padding: 5px 10px !important;
  font-weight: bold;
  text-align: center;
  background: #fffbe2; /* Old browsers */
  background: -moz-linear-gradient(top,  #fffbe2 0%, #fffffd 23%, #fff697 85%, #fffef4 100%); /* FF3.6+ */
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fffbe2), color-stop(23%,#fffffd), color-stop(85%,#fff697), color-stop(100%,#fffef4)); /* Chrome,Safari4+ */
  background: -webkit-linear-gradient(top,  #fffbe2 0%,#fffffd 23%,#fff697 85%,#fffef4 100%); /* Chrome10+,Safari5.1+ */
  background: -o-linear-gradient(top,  #fffbe2 0%,#fffffd 23%,#fff697 85%,#fffef4 100%); /* Opera 11.10+ */
  background: -ms-linear-gradient(top,  #fffbe2 0%,#fffffd 23%,#fff697 85%,#fffef4 100%); /* IE10+ */
  background: linear-gradient(to bottom,  #fffbe2 0%,#fffffd 23%,#fff697 85%,#fffef4 100%); /* W3C */
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fffbe2', endColorstr='#fffef4',GradientType=0 ); /* IE6-9 */
  border: 1px solid #a8a8a7;
  border-radius: 10px;
  -moz-border-radius: 10px;
  -o-border-radius: 10px;
  -webkit-border-radius: 10px;
}
table {
  width: 100%;
  margin: 0 0 8px;
  border-collapse: collapse;
}
table thead td {
  font-size: 12px;
}
table td {
  padding: 0.5%;
  vertical-align: top;
  border: 1px solid #000;
}
</style>
</head>
<body>
<?php
  
  $printContents = $_GET['printContents'];
  echo $printContents;
?>
<script type="text/javascript">
  window.print();
  //window.close();
</script>
</body>
</html>