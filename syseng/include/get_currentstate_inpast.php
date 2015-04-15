<?php

if(!isset($pdo)) { include('../include/dbconnectPDO.php'); }

include('../include/date_str.php');

//var_dump($ds_str);

$sql_state = "SELECT  agreg.RoomId, SUM(transition) as NumberOfPeople,  
      CAST( IF(SUM(ABS(agreg.transition))<>0,(SUM(ABS(agreg.transition)*agreg.Confidence)/SUM(ABS(agreg.transition))), agreg.Confidence) as DECIMAL(5,2) )  as Confidence FROM
  ( 
    SELECT r3.`RoomId`, r3.`event_time`, r3.`transition`, r3.`Confidence`
    FROM `roommovements` r3
    WHERE
     r3.event_time  >= (SELECT MAX(r2.`EventTime`) FROM `roomoccupancy` r2 WHERE r2.`RoomID` = r3.`RoomID` AND r2.EventTime <= '".$ds_str."' )
     AND r3.event_time <= '".$ds_str."'
    UNION ALL
    SELECT `RoomID`,`EventTime`, `NumberofPeople`,r1.`Confidence` FROM `roomoccupancy` r1
     WHERE r1.`EventTime` = (SELECT MAX(r2.`EventTime`) FROM `roomoccupancy` r2 WHERE r1.`RoomID` = r2.`RoomID` AND r2.EventTime <= '".$ds_str."' )
   ) as agreg 
  GROUP BY RoomId ";


$stmt = $pdo->prepare($sql_state);
$stmt->execute();

$rn = 0;
$totOcc_in_past = 0;
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

  $totOcc_in_past += $row['NumberOfPeople'];

}
//var_dump($rooms);
// check to see if submit button was pressed
  //phpinfo(INFO_VARIABLES);
  // show wait cycle

// closes the database connection
$pdo = NULL;
  
$CalculatedOn = new DateTime();


?>