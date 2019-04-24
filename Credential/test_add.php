<?php
include_once("../db.php");
$newUserInsertStmt = '';
$newUserInsertPrepper = '';
//THIS IS FOR NEW RECIPIENTS ONLY.
$lv_role = 3;
$salt = 'FED';
$lv_new_user = "Dave@isasddddftesting.org";
$lv_new_pass = "asdf";

$sqlNewUser = "INSERT INTO CREDENTIALS( USER_NAME, PASSWORD, ROLEID)
                            VALUES(:NEW_USER, AES_ENCRYPT(:NEW_PASS, :SALT), :NEW_ROLE)";

echo "sqlNewUser<br><hr>";
print_r($sqlNewUser);


$newUserInsertStmt = $credCon->prepare($sqlNewUser);
if(false===$newUserInsertStmt){
    die('prepare() failed: ' . htmlspecialchars($newUserInsertStmt->error));
   }
echo "newUserInsertStmt<br><hr>";
print_r($newUserInsertStmt);
   
$newUserInsertPrepper = $newUserInsertStmt->execute(
    array(
        'NEW_USER' => $lv_new_user,
        'NEW_PASS' => $lv_new_pass,
        'SALT' => $salt,
        'NEW_ROLE' => $lv_role
    )
    );
if(false===$newUserInsertPrepper){
    die('execute/pdo bind() failed: ' . htmlspecialchars($newUserInsertPrepper->error));
}

echo "newUserInsertStmt2<br><hr>";
print_r($newUserInsertStmt);
echo "athing<br><hr>";
if($newUserInsertStmt->rowCount()){
    $dataOut = [
        'USERTAKEN' => false,
        'GRANT_ACCESS' => true,
        'ROLEID' => $lv_role,
        'SQL' => $sqlNewUser, 
    ];
  $athing =   json_encode($dataOut);
}
print_r($athing);

$newUserInsertStmt = '';
$newUserInsertPrepper = '';
   

?>