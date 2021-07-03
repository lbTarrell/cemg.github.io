
<?php

// Create connection
// $db = new mysqli("127.0.0.1","root","%cemg300720","ABD_210701","3306");
//$db = new mysqli("www.dshc.com.hk","root","%[]957Oo0*","ABD_210701","3306");
$db = new mysqli("localhost","root","","susan","3306");


// Check connection

if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
} 
return $db;
?>
