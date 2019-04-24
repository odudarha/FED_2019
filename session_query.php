<?php
session_start();
include('db.php');
include('function.php');

echo "<h1>".$_SESSION['QUERY']."</h1>";

?>