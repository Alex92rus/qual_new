<?php

include('../include/date_str.php');

if(!isset($pdo)) {  include('../include/dbconnectPDO.php'); }
  $sql =   " SELECT r3.`RoomId`, r3.`event_time`, r3.`transition`, r3.`Confidence` 
    FROM `roommovements` r3
    WHERE
     r3.RoomId = :roomid AND
     r3.event_time  >= :startdate
      AND r3.event_time < :enddate
    ORDER BY event_time ASC
    LIMIT 500";


/*  $referroom = 'R7';
  $startdate = '2015-02-15';
  $enddate = '2015-03-15';*/

  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':roomid', $referroom, PDO::PARAM_STR);
  $stmt->bindValue(':startdate', $ds_str, PDO::PARAM_STR);
  $stmt->bindValue(':enddate', $de_str, PDO::PARAM_STR);


  $stmt-> execute();

  $rn = 0;
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //var_dump($result);
    $rn++;

    $history[$rn]['transition'] = (int)$result['transition'];
    $history[$rn]['event_time'] = $result['event_time'];
    $history[$rn]['Confidence'] = $result['Confidence'];

  }
  //phpinfo(INFO_VARIABLES);
  //var_dump($history);

  $stmt = NULL;
  $pdo=NULL;



?>