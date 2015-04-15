<?php

/*  $referroom = 'R7';
  $startdate = '2015-01-26';
  $enddate = '2015-03-29';
*/

include('../include/date_str.php');

if(!isset($pdo)) {  include('../include/dbconnectPDO.php'); }

$sql = "SELECT `DefinedAt`, `IntervalInSeconds`, `WeeksToCount` 
          FROM `convexdefinitions` ORDER BY 1 DESC LIMIT 1";
foreach ($pdo->query($sql, PDO::FETCH_ASSOC) as $row) {
  $IntervalInSeconds = $row['IntervalInSeconds'];
}




  $sql =   " SELECT `RoomId`, `WeekDay`, `partOfDay`, `MinOccupants`, `MaxOccupants`, `AvgOccupants` 
    FROM `convex` 
    WHERE RoomId = :roomid
    ORDER BY WeekDay, partOfDay";




  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':roomid', $referroom, PDO::PARAM_STR);

  $stmt-> execute();

  $lastSun = new DateTime();
  $d_ts = new DateTime();
  $lastSun = $lastSun->setTimestamp(strtotime('Last Sunday', $lastSun->getTimestamp()));
  $lastSunTimestamp = $lastSun->getTimestamp();

  $rn = 0;
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //var_dump($result);
    $rn++;
    $history[$rn]['WeekDay'] = $result['WeekDay'];
   $history[$rn]['partOfDay'] = $result['partOfDay'];

    $t_ts = $lastSunTimestamp + (24*60*60)*($result['WeekDay']-1)+$result['partOfDay']*$IntervalInSeconds;
    $d_ts->setTimestamp($t_ts);

    $history[$rn]['date'] = $d_ts->format('Y-m-d H:i:s');
    $history[$rn]['value'] = $result['AvgOccupants'];
    $history[$rn]['fromValue'] = $result['MinOccupants'];
    $history[$rn]['toValue'] = $result['MaxOccupants'];

  }
  //phpinfo(INFO_VARIABLES);
  //var_dump($history);

  $stmt = NULL;
  $pdo=NULL;



?>