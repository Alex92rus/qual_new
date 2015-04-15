
  <?php
  

ini_set('max_execution_time', 800);


include('dbconnectPDO.php');


$sql_mvmnt = "SELECT min( date(event_time) ) as startfrom FROM roommovements";
foreach ($pdo->query($sql_mvmnt, PDO::FETCH_ASSOC) as $row) {
   //var_dump($row);
  $startfrom = $row['startfrom'];
}
var_dump($startfrom);
$ds = DateTime::createfromformat('Y-m-d', $startfrom);
$ds->setTime(09, 12);
$lastdate = new datetime();

//$ds = DateTime::createfromformat('Y-m-d', '2015-01-28');
$dateto = $ds->format('Y-m-d');

while ( $ds->modify('+1 day') < $lastdate ) {
  $dateto = $ds->format('Y-m-d');

  $sql="INSERT INTO `roomoccupancy`( `RoomID`, `NumberofPeople`, `Confidence`, `EventTime`) 
  -- For generating roomoccupancy table from
  SELECT a.RoomId, 
    ( SELECT SUM(b.Transition) FROM `roommovements` as b 
	  WHERE a.RoomId = b.RoomId AND DATE(b.`event_time`) < DATE('".$dateto."') ) as bpassed ,
  
    ( SELECT SUM(ABS(c.Transition)*c.Confidence)/SUM(ABS(c.Transition)) FROM `roommovements` as c 
	  WHERE a.RoomId = c.RoomId 
	   AND DATE(c.`event_time`) < DATE('".$dateto."') ) as cpassed,
	'".$dateto."'
  FROM `currentstate` as a  GROUP BY 1, 4 ORDER BY  4 ASC, 1 ASC";
  
  
	  $pdo->exec($sql);


  echo "<br />", var_dump($ds);


} 
