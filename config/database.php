<?php 
include "../public/wp-config.php";

$conn = new mysqli(DB_HOST, DB_USER , DB_PASSWORD , DB_NAME);

if($conn->connect_error)
  die('Connection Failed');
echo 'Connected'
?>