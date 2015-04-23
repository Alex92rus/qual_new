<?php

include_once('dbconnectPDO.php');

$sql_state = "SELECT RoomId, SUM(NumberOfPeople) as NumberOfPeople, 
                     IF(SUM(ABS(agreg.NumberOfPeople))<>0,CAST(SUM(ABS(agreg.NumberOfPeople)*agreg.Confidence)/SUM(ABS(agreg.NumberOfPeople)) as DECIMAL(5,2)), agreg.Confidence) as Confidence, 
                     SUM(ABS(agreg.NumberOfPeople)) as passed 
                FROM
                ( SELECT r3.`RoomId`, r3.`transition` as NumberOfPeople, r3.`Confidence` 
                       FROM `roommovements` r3 WHERE r3.event_time > 
                          (SELECT MAX(r2.`EventTime`) FROM `lastroomoccupancy` r2  ) 
                UNION ALL 
                  SELECT `RoomID`, `NumberofPeople`, `Confidence` FROM `lastroomoccupancy` r1 
                               ) as agreg GROUP BY 1 ORDER BY CAST(SUBSTR(RoomId, 2) AS UNSIGNED);";


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