<?php
include_once("../db.php");
$grantAccess = false;
//sanitize the email first
//$email =  filter_var($_POST['emailUser'], FILTER_SANITIZE_EMAIL);  


$salt = 'FED';

if($_POST['CREDENTIAL_NEEDED']=="NEW"){
    $sql_search = "SELECT COUNT(*) AS 'COUNTER'
                    FROM CREDENTIALS
                    WHERE lower(USER_NAME) = lower(:USER)  ";

    $lv_new_user = $_POST['emailUser'];
    $lv_new_pass = $_POST['passwordUser'];
    $newUserSelectStmt = $connection->prepare($sql_search);
    if(false===$newUserSelectStmt){
        die('bind_param() failed: ' . htmlspecialchars($userPassStmt->error));
    }
    $newUserCheckPrepper = $newUserSelectStmt->execute(
        array(
            'USER' => $lv_new_user
        )
    );
    if ( false===$newUserCheckPrepper){
        die('prepare() failed: ' . htmlspecialchars($newUserCheckPrepper->error));
    }
    $newUserCheckResult = $newUserSelectStmt->fetch();

    if($newUserCheckResult['COUNTER'] == 0){
        //THIS IS FOR NEW RECIPIENTS ONLY.
        $lv_role = 3;
        $sqlNewUser = "INSERT INTO CREDENTIALS( USER_NAME, PASSWORD, ROLEID)
                                    VALUES(:NEW_USER, AES_ENCRYPT(:NEW_PASS, :SALT), :NEW_ROLE)";
        
        $newUserInsertStmt = $connection->prepare($sqlNewUser);
        if(false===$newUserInsertStmt){
            die('prepare() failed: ' . htmlspecialchars($connection->errorInfo()));
           }
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
        if($newUserInsertStmt->rowCount()){
            $dataOut = [
                'USERTAKEN' => false,
                'GRANT_ACCESS' => true,
                'ROLEID' => "3",
                'SQL' => $sqlNewUser, 
            ];
            echo json_encode($dataOut);
        }else{
            die("Significant error in the new user Insert Routine, contact DBA and WebMaster for details.");
        }
    }else {
    $dataOut = [
                 'USERTAKEN' => true,
                ];
    echo json_encode($dataOut);
    }

}else{
    if($_POST['TRIES']<=4){
        /*If less than 5 access attempts (counting zero) use the data entered in to the form*/


        $lv_user = $_POST['emailUser'];  //lv_ is Dave Babler's way of designating local variables.
        $lv_pass = $_POST['passwordUser'];
    }else {
        //Use the override user to get into the database.
        $lv_user = '';
        $lv_pass = '0v3rid3';
    }

    $userPassStmt = $connection->prepare("SELECT ROLEID 
    FROM CREDENTIALS
    WHERE USER_NAME = :USER 
        AND PASSWORD = AES_ENCRYPT(:PASS, :SALT) ");

    if ( false===$userPassStmt ) {
        die('execute/pdo bind() failed: ' . htmlspecialchars($connection->errorInfo()));
    }

    $statementPrepper1 = $userPassStmt->execute(
        array(
        'USER' => $lv_user, 
        'PASS' => $lv_pass, 
        'SALT' => $salt
        )
    );

    if ( false===$statementPrepper1 ){
        die('bind_param() failed: ' . htmlspecialchars($connection->errorInfo()));
    }

    $userPassResult = $userPassStmt->fetch();

    if($userPassResult['ROLEID'] > 0 && $userPassResult['ROLEID'] <> false){
        //if credentials good let the AJAX call grant access 
        $grantAccess = true;
        $dataOut = [
            'ROLEID' => $userPassResult['ROLEID'], 
            'GRANT_ACCESS' => $grantAccess, 
            ];
        
        echo json_encode($dataOut);
    }else{
        $dataOut = [
            'ROLEID' => 0, 
            'GRANT_ACCESS' => $grantAccess,
            'USERTAKEN' => false,
        ];
        //note we have to include a false flag for USERTAKEN or you won't escape the first logic check on return
        echo json_encode($dataOut);
    }

}



?>
