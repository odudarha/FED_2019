<?php
include('db.php');
include('function.php');
$query = '';
$output = array();
$query .= "SELECT * FROM `INVENTORY` INNER JOIN `INV_TYPE` ON `INV_TYPE`.`TYPE_ID` =`INVENTORY`.`TYPE_ID`";
$query .= "WHERE" ;
if (isset($_POST["is_category"])){
    $query .= "`INVENTORY`.`TYPE_ID` = '".$_POST["is_category"]."' AND ";
}
if(isset($_POST["search"]["value"]))
{
	$query .= '( INVENTORY.DESCRIPTION LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR INV_TYPE.TYPE_DESCRIPTION LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR UPC LIKE "% '.$_POST["search"]["value"].' %" )';
}
if(isset($_POST["order"]))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY UPC DESC ';
}
$query1 = '';
if($_POST["length"] != -1)
{
	$query1 .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
//$filtered_rows = (($connection->prepare($query))->execute())->rowCount();

$statement = $connection->prepare($query);
$statement->execute();
$filtered_rows = $statement->rowCount();
//$result = $statement->fetchAll();
$statement1 = $connection -> prepare($query.$query1);
$statement1->execute();
$result = $statement1->fetchAll();
$data = array();
foreach($result as $row)
{
	$image = '';
	if($row["IMAGE"] != '')
	{
		$image = '<img src="'.$row["IMAGE"].'" class="img-thumbnail" style ="display: block; margin-left: auto; margin-right: auto; width: 100px; height: 100px; object-fit: scale-down;" />';
	}
	else
	{
		$image = '';
	}

	$sub_array = array();
	$sub_array[] = $image;
    $sub_array[] = str_pad($row["UPC"],12,"0",STR_PAD_LEFT);
	$sub_array[] = $row["DESCRIPTION"];
    $sub_array[] = $row["TYPE_DESCRIPTION"];//$food_description;    
	$sub_array[] = $row["QUANTITY"];
	$sub_array[] = '<button type="button" name="update" id="'.$row["UPC"].'" class="btn btn-outline-warning update">Update</button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["UPC"].'" class="btn btn-outline-danger delete">Delete</button>';
	$data[] = $sub_array;
}
$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"		=> 	get_total_all_records(),
	"recordsFiltered"	=>	$filtered_rows,
	"data"				=>	$data
);
echo json_encode($output);
?>