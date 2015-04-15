<?php

if(!isset($pdo)) {
  include('dbconnectPDO.php');
}

$sql_state = "SELECT RoomId, NumberOfPeople
 FROM currentstate
  GROUP BY RoomId
  ORDER BY 1";


$rNum = 0;
$totOcc = 0;
foreach ($pdo->query($sql_state, PDO::FETCH_ASSOC) as $row) {
  // each $row = an associative array representing one row from the database query
  // the 'ISO2' column will be the array key
  // the 'country_name' column will be the array value
  if($row['RoomId']=='0000000000'){
    continue;
  }


  $rNum++;
  $roomList[$rNum]['RoomId'] = $row['RoomId'];
  $roomList[$rNum]['NumberOfPeople'] = $row['NumberOfPeople'];
  $totOcc += $row['NumberOfPeople'];

}
//var_dump($rooms);
// check to see if submit button was pressed
  //phpinfo(INFO_VARIABLES);
  // show wait cycle


$CalculatedOn = new DateTime();


?>