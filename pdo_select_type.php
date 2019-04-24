<?php 
session_start();
include("db.php");
$verbose_description = '';
if(isset($_POST["typeid_in"])){
    try{
        $stmt=$connection->prepare("SELECT TYPE_DESCRIPTION
        FROM INV_TYPE
        WHERE TYPE_ID = :id");
        $stmt->bindValue(':id', $_POST["typeid_in"], PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $verbose_description = $rows[0]["TYPE_DESCRIPTION"];

        
    } catch(PDOException $ex) {
        echo $ex->getMessage();
    }


}else{
    $verbose_description = "invalid selection";
}

echo json_encode($verbose_description);

?>