<?php
session_start();
include('db.php');
include('function.php');
if(isset($_POST["operation"]))
{
	if($_POST["operation"] == "Add")
	{
        $output = array();
        $output["upc_exists"] = does_upc_exist($_POST["user_id"]);
        $output["valid_upc"] = is_upc_valid($_POST["user_id"]);
        if ($output["upc_exists"]!=0){
            echo 'UPC already exist in database.';
        }
        if ($output["valid_upc"]!=1){
            echo 'Entered data is not a valid UPC.';
        }
        if ($output["upc_exists"]==0 && $output["valid_upc"]==1){
            $statement = $connection->prepare("
			INSERT INTO INVENTORY (UPC, DESCRIPTION, QUANTITY, IMAGE, TYPE_ID) 
			VALUES (:UPC, :description, :quantity, :image, :type_id)
            ");
            $result = $statement->execute(
                array(
                    ':description'	=>	$_POST["description"],
				    ':quantity'	    =>	$_POST["quantity"],
				    ':image'		=>	$_POST["image_location"],
                    ':UPC'			=>	$_POST["upc"],
                    ':type_id'      =>  $_POST["foodtype"]
                )
            );
            if(!empty($result)){
                echo 'Data Inserted.';
            } 
        }
        else{
            echo ' Data not Inserted';
        }
	}

	if($_POST["operation"] == "Edit")
	{
        $stmt = $connection->prepare(
            "SELECT QUANTITY 
            FROM INVENTORY 
            WHERE UPC = :UPC
            LIMIT 1"
        );
        $stmt->execute(['UPC' => $_POST["user_id"]]);
        $qty = $stmt->fetch();
        $_SESSION['OLDQTY'] = $qty['QUANTITY'];
     
		$statement = $connection->prepare(
			"UPDATE INVENTORY 
			SET DESCRIPTION = :description, QUANTITY = :quantity, IMAGE = :image, TYPE_ID = :type_id  WHERE UPC = :UPC
			");
		$result = $statement->execute(
			array(
				':description'	=>	$_POST["description"],
				':quantity'	    =>	$_POST["quantity"],
				':image'		=>	$_POST["image_location"],
				':UPC'			=>	$_POST["user_id"],
                ':type_id'      =>  $_POST["foodtype"]
			)
		);
		if(!empty($result))
		{
            //QUESTION 01: this session destroy may not be needed, comment out a later date and try it out
            session_destroy();
		}
    }
 
}

?>