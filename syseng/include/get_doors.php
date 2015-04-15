<?php

if(!isset($pdo)) {
  include('dbconnectPDO.php');
}

$sql_state = "SELECT DISTINCT doorId
 FROM buildingplan
  GROUP BY 1
  ORDER BY 1";


$rNum = 0;

foreach ($pdo->query($sql_state, PDO::FETCH_ASSOC) as $row) {
  // each $row = an associative array representing one row from the database query
  // the 'ISO2' column will be the array key
  // the 'country_name' column will be the array value

  $rNum++;
  $doorList[$rNum]['doorId'] = $row['doorId'];


}

// closes the database connection
$pdo = NULL;
  


?>