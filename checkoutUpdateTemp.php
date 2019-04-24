<?php
session_start();
include('db.php');
include('function.php');
/*WARNING WARNING WARNING
THIS SCRIPT WILL BE FOLDED INTO "checkoutDBLogic.php"*/

if(!$_SESSION['POST']) $_SESSION['POST'] = array();

foreach ($_POST as $key => $value) {
    $_SESSION['POST'][$key] = $value;
}

echo json_encode($_SESSION['POST']);








?>