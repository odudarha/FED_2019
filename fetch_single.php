<?php
include('db.php');
include('function.php');
if(isset($_POST["user_id"]))
{
  $output = array();
  $statement = $connection->prepare(
    "SELECT * FROM INVENTORY 
    WHERE UPC = '".$_POST["user_id"]."' 
    LIMIT 1"
  );
  $statement->execute();
  $result = $statement->fetchAll();
  foreach($result as $row)
  {
    $output["description"] = $row["DESCRIPTION"];
    $output["quantity"] = $row["QUANTITY"];
    $output["upc"] = str_pad($row["UPC"],12,"0",STR_PAD_LEFT);
    $output["item_image"]=$row["IMAGE"];
    $output["food_id"]=$row["TYPE_ID"];
    if($row["IMAGE"] != '')
    {
      $output['user_image'] = '<img src="'.$row["IMAGE"].'" class="img-thumbnail" width="50" height="35" float="center"/><input type="hidden" name="hidden_user_image" value="'.$row["IMAGE"].'" />';
    }
    else
    {
      $output['user_image'] = '<input type="hidden" name="hidden_user_image" value="" />';
    }
  }
  echo json_encode($output);
}
?>