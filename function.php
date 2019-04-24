<?php

function get_total_all_records()
{
	include('db.php');
	$statement = $connection->prepare("SELECT * FROM INVENTORY");
	$statement->execute();
	$result = $statement->fetchAll();
	return $statement->rowCount();
}

function is_upc_valid($numberUPC){
    $upc = strval($numberUPC);
    $result = 0;
    if(!(isset($upc[11]))){
        $result = 0;
    }
    else{
        $odd_num=$even_num =0;
        for($i =1; $i<12;++$i){
            if ($i % 2 == 0){
                $even_num += $upc[$i-1];
            }
            else{
                $odd_num += $upc[$i-1];
            }
        }
        $total_sum=$even_num+(3*$odd_num);
        $modulo = $total_sum%10;

        if($modulo==0){
            $check_digit = 0;
        }
        else{
            $check_digit=10-$modulo;
        }
        if($check_digit==$upc[11]){
            $result = 1;
        }
        else 
            $result=0;
    }
    return $result;
}
function does_upc_exist($numberUPC){
    include ('db.php');
    $bool_value = 1;
    $statement = $connection->prepare("SELECT * FROM INVENTORY WHERE UPC = :UPC");
    $statement->execute(
        array(
            ':UPC'  => $numberUPC
        )
                       );
    $result = $statement -> fetchAll();
    $number_rows = $statement ->rowCount();
    if ($number_rows==0){
        $bool_value = 0;
    }
    else{
        $bool_value = 1;
    }
    return $bool_value;
}

//https://stackoverflow.com/questions/23740548/how-to-pass-variables-and-data-from-php-to-javascript
?>