<?php

include('../include/date_str.php');

if(!isset($pdo)) {  include('../include/dbconnectPDO.php'); }


  $sql =   "Select abs(transition) as transition, event_time ".
    "FROM b42snapshot WHERE doorId = :doorId AND event_time >= :ds_str and event_time <= :de_str ".
    " UNION ".
    "Select abs(transition), event_time ". 
    "FROM correcterr WHERE doorId = :doorId AND event_time >= :ds_str and event_time <= :de_str ".
    "  ORDER by event_time ";


/*  $referdoor = 'D1';
  $startdate = '2015-02-15';
  $enddate = '2015-03-15';*/

  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':doorId', $referdoor, PDO::PARAM_STR);
  $stmt->bindValue(':ds_str', $ds_str, PDO::PARAM_STR);
  $stmt->bindValue(':de_str', $de_str, PDO::PARAM_STR);


  $stmt-> execute();

  $total = 0;
  $rn = 0;
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //var_dump($result);
    $rn++;

    $history[$rn]['transition'] = (int)$result['transition'];
    $history[$rn]['event_time'] = $result['event_time'];

    $total += (int)$result['transition'];
  
}  

  //phpinfo(INFO_VARIABLES);
  //var_dump($history);

  $stmt = NULL;
  $pdo=NULL;



?>