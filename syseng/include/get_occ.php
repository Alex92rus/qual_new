<?php

if(!isset($pdo)) {
  include('dbconnectPDO.php');
}

$sql = "SELECT sum(NumberOfPeople) as totOcc
 FROM currentstate WHERE RoomId <> '0000000000'
";


foreach ($pdo->query($sql, PDO::FETCH_ASSOC) as $row) {
	$totOcc = $row['totOcc'];
}

  
$CalculatedOn = new DateTime();


?>