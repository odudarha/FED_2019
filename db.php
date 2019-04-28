<?php
try {
  $username = 'yourusername';
  $password = 'yourpassword';
  $connection = new PDO( 'mysql:host=mysql.yaacotu.com;dbname=fed_db_yourname', $username, $password);
  $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
}
catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>
