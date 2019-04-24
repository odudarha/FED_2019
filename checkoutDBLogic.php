<?php
session_start();
include('db.php');
include('function.php');
$queryType = $_POST['queryType'][0];
unset($cartItem);


if($queryType == 'SEARCH'){
    //begin search function
    $trimmedSearchValue = $_POST["searchData"];
    //$trimmedSearchValue = '0768280467';
    $leadingZero = 0;
    //drop leading zeros
    $trimmedSearchValue = strtolower(ltrim($trimmedSearchValue, $leadingZero));
    //$trimmedSearchValue = strtolower(preg_replace('/^0/', '', $trimmedSearchValue));  //use ONLY if normal trimmed search ends up causing issues
    $sqlSearch = "SELECT UPC, DESCRIPTION, IMAGE, TYPE_ID, QUANTITY
                    FROM INVENTORY
                    WHERE CAST(UPC AS CHAR) LIKE CONCAT('%',:SEARCHED,'%')
                    OR LOWER(DESCRIPTION) LIKE CONCAT('%',:SEARCHED,'%')
                    LIMIT 1";

    $searchStmt = $connection->prepare($sqlSearch);
        if(false===$searchStmt){
            die('Prepare Failed on var \'searchStmt\' contact DBA with the following code: ' . htmlspecialchars($searchStmt));
        }
    $searchStmtPrepper = $searchStmt->execute(
        array(
            'SEARCHED' => $trimmedSearchValue
        )
    );
        if(false===$searchStmtPrepper){
            die('Prepare Failed on var  \'searchStmtPrepper\' contact DBA with the following code: ' . htmlspecialchars($searchStmtPrepper));
        }
    $searchResult = $searchStmt->fetch();
        $cartItem = [
            'cartUPC' => $searchResult['UPC'], 
            'cartDescription' => $searchResult['DESCRIPTION'],
            'cartImage' => $searchResult['IMAGE'], 
            'cartType_ID' => $searchResult['TYPE_ID'], 
            'cartQuantity' => $searchResult['QUANTITY']

        ];

        echo json_encode($cartItem);
}elseif ($queryType === "CHECKOUT") {
    $qty = 1; //quantity will always be 1 for now, but leaving this as a var incase stakeholders want to change functionality
    $cartUPC = array();
    
    $cartItemCount = count($_POST['cartData']);
    $successArray = array();
    for($i=0; $i<$cartItemCount; $i++){
        array_push($cartUPC, $_POST['cartData'][$i]['UPC']);
    }
    for($i=0; $i<$cartItemCount; $i++){
        $sqlUpdate = " UPDATE INVENTORY
        SET QUANTITY = (QUANTITY - :QTY)
        WHERE UPC = :UPC ";

        $updateStmt = $connection->prepare($sqlUpdate);
        if(false==$updateStmt){
            die('Update failed on var \'updateStmt\' contact DBA with the following code: ' . htmlspecialchars($updateStmt));
        }
        $updateStmt->bindParam(':QTY', $qty, PDO::PARAM_INT);
        $updateStmt->bindParam(':UPC', $cartUPC[$i], PDO::PARAM_INT);
        $updateStmt->execute();
        if(!$updateStmt->rowCount()){
            $error = "Update failed on execute " . htmlspecialchars($updateStmt) . "<br>";
            echo json_encode($error);
        }
        $successMessageUPC = $cartUPC[$i]." has been updated";
        array_push($successArray, $successMessageUPC);
    }

    echo json_encode($successArray);

}



function array_push_assoc($array, $key, $value){
    $array[$key] = $value;
    return $array;
    }



?>