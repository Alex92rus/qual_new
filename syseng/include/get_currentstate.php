<?php

include_once('dbconnectPDO.php');

$sql_state = "SELECT c.RoomId, c.NumberOfPeople, b.Confidence, b.passed FROM currentstate as c, 
  ( SELECT agreg.RoomId, IF(SUM(ABS(agreg.transition))<>0, 
    CAST(SUM(ABS(agreg.transition)*agreg.Confidence)/SUM(ABS(agreg.transition)) as DECIMAL(5,2)), agreg.Confidence) as Confidence, 
    SUM(ABS(agreg.transition)) as passed FROM ( SELECT r3.`RoomId`, r3.`event_time`, r3.`transition`, r3.`Confidence` 
         FROM `roommovements` r3 WHERE r3.event_time >= 
            (SELECT MAX(r2.`EventTime`) FROM `roomoccupancy` r2 WHERE r2.`RoomID` = r3.`RoomID` ) 
              UNION ALL SELECT `RoomID`,`EventTime`, `NumberofPeople`, `Confidence` FROM `roomoccupancy` r1 
              WHERE r1.`EventTime` = (SELECT MAX(r2.`EventTime`) FROM `roomoccupancy` r2 
                WHERE r1.`RoomID` = r2.`RoomID` ) ) as agreg GROUP BY RoomId ) as b WHERE c.roomId = b.roomId ORDER BY 1";


$rn = 0;
$totOcc = 0;
foreach ($pdo->query($sql_state, PDO::FETCH_ASSOC) as $row) {
  // each $row = an associative array representing one row from the database query
  // the 'ISO2' column will be the array key
  // the 'country_name' column will be the array value
  if($row['RoomId']=='0000000000'){
    continue;
  }


  $rn++;
  $rooms[$rn]['RoomId'] = $row['RoomId'];
  $rooms[$rn]['NumberOfPeople'] = $row['NumberOfPeople'];
  $rooms[$rn]['Confidence'] = $row['Confidence'];
  $rooms[$rn]['passed'] = $row['passed'];

  $totOcc += $row['NumberOfPeople'];

}
//var_dump($rooms);
// check to see if submit button was pressed
  //phpinfo(INFO_VARIABLES);
  // show wait cycle

// closes the database connection
$dbo = NULL;
  
$CalculatedOn = new DateTime();


?>