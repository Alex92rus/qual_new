<?php

/*  $referroom = 'R7';
  $startdate = '2015-01-26';
  $enddate = '2015-03-29';
*/

include('../include/date_str.php');

if(!isset($pdo)) {  include('../include/dbconnectPDO.php'); }


  $sql =   " SELECT `RoomId`, `WeekDay`, `partOfDay`, `NumberofPeople`, `MinOccupants`, `MaxOccupants`, `AvgOccupants`, `cvx_date` 
    FROM `convex_date` 
    WHERE
     RoomId = :roomid AND
     cvx_date  >= :startdate
      AND cvx_date < :enddate
    ORDER BY cvx_date ASC";




  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':roomid', $referroom, PDO::PARAM_STR);
  $stmt->bindValue(':startdate', $ds_str, PDO::PARAM_STR);
  $stmt->bindValue(':enddate', $de_str, PDO::PARAM_STR);


  $stmt-> execute();

  $rn = 0;
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //var_dump($result);
    $rn++;

    $history[$rn]['date'] = $result['cvx_date'];
    $history[$rn]['value'] = $result['AvgOccupants'];
    $history[$rn]['fromValue'] = $result['MinOccupants'];
    $history[$rn]['toValue'] = $result['MaxOccupants'];

  }
  //phpinfo(INFO_VARIABLES);
  //var_dump($history);

  $stmt = NULL;
  $pdo=NULL;



?>