<?php

include("include/dbconnect.php");

$SQL = "SELECT * FROM buildingplan";
$result = $pdbh->query($SQL);

$pdbh->close();

$JSON = "[";
while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
	if ($JSON != "[") {
		$JSON .= ",";
	}
	$JSON .= '{'. '"DoorID":"' .$row["DoorID"].'",';
	$JSON .= '"Room1ID":"'.$row["Room1ID"].'",';
	$JSON .= '"Room2ID":"'.$row["Room2ID"].'"';
	$JSON .= '}';
}
$JSON .= "]";

echo ($JSON);
?>