<?php

include('../include/date_str.php');

if(!isset($pdo)) {  include('../include/dbconnectPDO.php'); }


  $sql =   "Select DoorId, SUM(abs(transition)) as transition ".
    " FROM b42snapshot WHERE event_time  >= :ds_str and event_time <= :de_str  GROUP BY DoorId ".
    " UNION ".
    " Select DoorId, SUM(abs(transition)) as transition ". 
    " FROM correcterr WHERE event_time >= :ds_str and event_time <= :de_str  GROUP BY DoorId ".
    "  ORDER by transition DESC ";


/*  $referdoor = 'D1';
  $startdate = '2015-02-15';
  $enddate = '2015-03-15';*/

  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':ds_str', $ds_str, PDO::PARAM_STR);
  $stmt->bindValue(':de_str', $de_str, PDO::PARAM_STR);


  $stmt-> execute();

  $total = 0;
  $rn = 0;
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //var_dump($result);
    $rn++;

    $history[$rn]['doorId'] = $result['DoorId'];
    $history[$rn]['transition'] = (int)$result['transition'];


    $total += (int)$result['transition'];
  
}  

  //phpinfo(INFO_VARIABLES);
  //var_dump($history);

  $stmt = NULL;
  $pdo=NULL;



?>