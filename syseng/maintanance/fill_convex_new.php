
<?php
ini_set('max_execution_time', 800);

$aa = new datetime('now');
echo '<br />'.$aa->format('H:i:s');

// Connect to MySQL
include( '../include/dbconnectPDO.php');

$pdo->exec('DELETE FROM `convex_date` WHERE 1');
$pdo->exec('DELETE FROM `convex` WHERE 1');


// get base params from config table
$sql = "SELECT Id, IntervalInSeconds, WeeksToCount FROM `convexdefinitions` ORDER BY Id DESC LIMIT 1";
$stmt = $pdo->prepare($sql);
foreach ($pdo->query($sql, PDO::FETCH_ASSOC) as $row) {
	$IntervalInSeconds = $row['IntervalInSeconds']; 
    $WeeksToCount = $row['WeeksToCount'];
} 

// Init convex array

$convex = array();	//	declare
//emptyConvex( $convex, $WeeksToCount, $IntervalInSeconds );	// fill with 0

for( $day = 1; $day <= 7*$WeeksToCount; $day++) {
	//$arr[$day]['EventTime'] = new DateTime();
	for ($partOfDay = 1; $partOfDay <= 24*60*60/$IntervalInSeconds; $partOfDay++) {
		$convex[$day][$partOfDay]['Min'] = 0;
		$convex[$day][$partOfDay]['Max'] = 0;
		$convex[$day][$partOfDay]['Avg'] = 0;
		$convex[$day][$partOfDay]['nrEvents'] = 0;	// Number of records
		$convex[$day][$partOfDay]['nOcc'] = 0;		// Number of occupants
//									
	}

}


//!!!!!!!!!!!!!!!!!
$WeeksToCount--;

//Room count:
$roomCount = 0;
$tmp = new DateTime;
$span = $WeeksToCount * 7;
$tmp->modify('-'.$span.' day');
$startEventTime = $tmp->format('Y-m-d')." 00:00:00";
foreach ($pdo->query('SELECT RoomId FROM currentstate', PDO::FETCH_ASSOC) as $row) {
	$RoomId = $row['RoomId'];

	$base_rec[$RoomId]['Min'] = 0;
	$base_rec[$RoomId]['Max'] = 0;
	$base_rec[$RoomId]['Avg'] = 0;
	$base_rec[$RoomId]['nrEvents'] = 0;
	$base_rec[$RoomId]['nOcc'] = 0;

	$roomCount++;

}


unset($base_rec);
// find start values 
$sql = ' SELECT `RoomId`, `NumberofPeople`, `EventTime` FROM `roomoccupancy` '.
			' WHERE RoomId<>"0000000000" AND DATEDIFF( NOW(), `EventTime` ) = '.$WeeksToCount.'*7  ORDER BY 3 DESC, 1 ASC LIMIT '.$roomCount;
foreach ($pdo->query($sql, PDO::FETCH_ASSOC) as $row) {
	$RoomId = $row['RoomId'];
	$startEventTime = $row['EventTime']	;
	
	$base_rec[$RoomId]['Min'] = $row['NumberofPeople'];
	$base_rec[$RoomId]['Max'] = $row['NumberofPeople'];
	$base_rec[$RoomId]['Avg'] = $row['NumberofPeople'];
	$base_rec[$RoomId]['nrEvents'] = 1;
	$base_rec[$RoomId]['nOcc'] = $row['NumberofPeople'];
		
}


$rec_cnt = 0;

foreach ($base_rec as $RoomId => $inArray) {

    $convex[1][1]['Min'] = $base_rec[$RoomId]['Min'];
    $convex[1][1]['Max'] = $base_rec[$RoomId]['Max'];
    $convex[1][1]['Avg'] = $base_rec[$RoomId]['Avg'];
    $convex[1][1]['nrEvents'] = $base_rec[$RoomId]['nrEvents'];
    $convex[1][1]['nOcc'] = $base_rec[$RoomId]['nOcc'];    

    $nOcc = $base_rec[$RoomId]['nOcc'];

    $lastDayWritten = 1;	// for backtrack convex filling
    $lastPeriod = 1;
    $currOcc = $base_rec[$RoomId]['Avg'];
    $lastOcc = $currOcc;

  $sql = 'SELECT `event_time`, `transition`, DATEDIFF( event_time, "'.$startEventTime.'" ) as daydiff FROM roommovements '.
		' WHERE RoomId = "'.$RoomId.'" AND event_time > "'.$startEventTime.'"'.
		' ORDER BY 1 ASC' ;


    foreach ($pdo->query($sql, PDO::FETCH_ASSOC) as $row) {
    	$event_time = $row['event_time'];

    	$transition = (int)$row['transition'];
    	$day = (int) $row['daydiff']+1;
    	$partOfDay = cvx_i($event_time);

    	$currOcc += $transition;

    	
    	// Set Min/Max 
    	if($convex[$day][$partOfDay]['Min'] > $currOcc || ($convex[$day][$partOfDay]['nrEvents'] == 0) ) {
    		$convex[$day][$partOfDay]['Min'] = $currOcc;
    	}
    	if($convex[$day][$partOfDay]['Max'] < $currOcc || ($convex[$day][$partOfDay]['nrEvents'] == 0) ) {
    		$convex[$day][$partOfDay]['Max'] = $currOcc;
    	}   	

    	// Avg
    	if(($convex[$day][$partOfDay]['nrEvents'] == 0) ) {
    		$convex[$day][$partOfDay]['Avg'] = $currOcc;
    	}  else {
    		$convex[$day][$partOfDay]['Avg'] = ($convex[$day][$partOfDay]['Avg']*$convex[$day][$partOfDay]['nrEvents'] + $currOcc)/
    													($convex[$day][$partOfDay]['nrEvents']+1);
    	}

    	// Add 1 to nrEvents
    	$convex[$day][$partOfDay]['nrEvents']++;

    	// set current occupants
    	    	// Add 1 to nrEvents
    	$convex[$day][$partOfDay]['nOcc'] = $currOcc ;


    	// Fill back cells with no change
    	if(!($lastDayWritten==$day && $partOfDay==$lastPeriod)) {
	    	$tpartOfDay = $lastPeriod;
	    	$tDay = $lastDayWritten;
			while(true) {

				$tpartOfDay++; 
				if($tpartOfDay > 24*60*60/$IntervalInSeconds)	{
					$tpartOfDay = 1;
					$tDay++;
				}
				if( $tDay>$day || ($tDay==$day && $tpartOfDay >= $partOfDay) ) {
					break;
				}

			    $convex[$tDay][$tpartOfDay]['Min'] = $lastOcc;
			    $convex[$tDay][$tpartOfDay]['Max'] = $lastOcc;
			    $convex[$tDay][$tpartOfDay]['Avg'] = $lastOcc;
			    $convex[$tDay][$tpartOfDay]['nOcc'] = $lastOcc;    

			}
		}


	    $lastDayWritten = $day;	// for backtrack convex filling
	    $lastPeriod = $partOfDay;
	    $lastOcc = $currOcc;

    }

    //var_dump($convex);
    echo '<br />', $RoomId;
    write_cvx_date( $convex, $RoomId );
    echo ' date ready';
    write_cvx( $convex, $RoomId );
    echo ' Week ready';


}



//////////////////////////////////////////////////////////////////
//    Agregate in convex 
/////////////////////////////////////////////////////////////////
function write_cvx_date(&$convex, $RoomId ) {
	GLOBAL $pdo;
// Assumesome global params
	GLOBAL $WeeksToCount;
	GLOBAL $IntervalInSeconds;
	GLOBAL $startEventTime;

	$dt_start = DateTime::createfromformat('Y-m-d H:i:s', $startEventTime);
	$dt_start_stamp = $dt_start->getTimestamp();

	$sql = "INSERT INTO convex_date ( RoomId, WeekDay, partOfDay, NumberofPeople, MinOccupants, MaxOccupants, AvgOccupants, cvx_date ) ".
	       " VALUES ( :RoomId, :WeekDay, :partOfDay, :NumberofPeople, :MinOccupants, :MaxOccupants, :AvgOccupants, :cvx_date )";   
	// prepare
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':RoomId', $RoomId, PDO::PARAM_STR);
	$stmt->bindParam(':WeekDay', $day, PDO::PARAM_INT);
	$stmt->bindParam(':partOfDay', $partOfDay, PDO::PARAM_INT);
	$stmt->bindParam(':NumberofPeople', $currOcc, PDO::PARAM_INT);
	$stmt->bindParam(':MinOccupants', $MinOccupants, PDO::PARAM_INT);
	$stmt->bindParam(':MaxOccupants', $MaxOccupants, PDO::PARAM_INT);
	$stmt->bindParam(':AvgOccupants', $AvgOccupants, PDO::PARAM_STR);
	$stmt->bindParam(':cvx_date', $cvx_date, PDO::PARAM_STR);

	// execute
	for( $day = 1; $day <= 7*$WeeksToCount; $day++) {
		//$arr[$day]['EventTime'] = new DateTime();
		for ($partOfDay = 1; $partOfDay <= 24*60*60/$IntervalInSeconds; $partOfDay++) {

			$MinOccupants =  $convex[$day][$partOfDay]['Min'] ;
			$MaxOccupants = $convex[$day][$partOfDay]['Max'] ;
			$AvgOccupants = (string) ( $convex[$day][$partOfDay]['Avg'] ) ;
			$currOcc = $convex[$day][$partOfDay]['nOcc'];

			$sec_to_add = (24*60*60)*($day-1) + $IntervalInSeconds * $partOfDay;
			$dt_start->setTimestamp($dt_start_stamp + $sec_to_add) ;
			$cvx_date = $dt_start->format('Y-m-d H:i:s');

			$result = $stmt->execute(  );
		}
	}
}

$aa = new datetime('now');
echo '<br />'.$aa->format('H:i:s');

//////////////////////////////////////////////////////////////////
//    Agregate in convex 
/////////////////////////////////////////////////////////////////
function write_cvx(&$convex, $RoomId ) {
	GLOBAL $pdo;
// Assumesome global params
	GLOBAL $WeeksToCount;
	GLOBAL $IntervalInSeconds;


	$cvx = array();
	for( $day = 1; $day <= 7*$WeeksToCount; $day++) {
		//$arr[$day]['EventTime'] = new DateTime();
		for ($partOfDay = 1; $partOfDay <= 24*60*60/$IntervalInSeconds; $partOfDay++) {
			if(!isset($cvx[$day][$partOfDay])) {
				$cvx[$day][$partOfDay]['Min'] = $convex[$day][$partOfDay]['Min'];
				$cvx[$day][$partOfDay]['Max'] = $convex[$day][$partOfDay]['Max'];
				$cvx[$day][$partOfDay]['Avg'] = $convex[$day][$partOfDay]['Avg'];
			} else {

				$cvx[$day][$partOfDay]['Min'] = min( $convex[$day][$partOfDay]['Min'], $cvx[$day][$partOfDay]['Min'] );
				$cvx[$day][$partOfDay]['Max'] = max( $convex[$day][$partOfDay]['Max'], $cvx[$day][$partOfDay]['Max'] );
				$cvx[$day][$partOfDay]['Avg'] = ($cvx[$day][$partOfDay]['Avg']*($day%7) + $convex[$day][$partOfDay]['Avg'] )/($day%7+1);
			}

							
		}

	} 


	$sql = "INSERT INTO convex ( RoomId, WeekDay, partOfDay, MinOccupants, MaxOccupants, AvgOccupants ) ".
	       " VALUES ( :RoomId, :WeekDay, :partOfDay, :MinOccupants, :MaxOccupants, :AvgOccupants )";   
	// prepare
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':RoomId', $RoomId, PDO::PARAM_STR);
	$stmt->bindParam(':WeekDay', $day, PDO::PARAM_INT);
	$stmt->bindParam(':partOfDay', $partOfDay, PDO::PARAM_INT);
	$stmt->bindParam(':MinOccupants', $MinOccupants, PDO::PARAM_INT);
	$stmt->bindParam(':MaxOccupants', $MaxOccupants, PDO::PARAM_INT);
	$stmt->bindParam(':AvgOccupants', $AvgOccupants, PDO::PARAM_STR);

	// execute
	for( $day = 1; $day <= 7; $day++) {
		//$arr[$day]['EventTime'] = new DateTime();
		for ($partOfDay = 1; $partOfDay <= 24*60*60/$IntervalInSeconds; $partOfDay++) {
			$MinOccupants =  $cvx[$day][$partOfDay]['Min'] ;
			$MaxOccupants = $cvx[$day][$partOfDay]['Max'] ;
			$AvgOccupants = (string) ( $cvx[$day][$partOfDay]['Avg'] ) ;

			$result = $stmt->execute(  );
		}
	}
}

//////////////////////////////////////////////////////////////////
//     
/////////////////////////////////////////////////////////////////
function cvx_i( $event_time ) {
	// Assume $IntervalInSeconds exists as a global
	GLOBAL $IntervalInSeconds ;

	$tm1 = datetime::createfromformat('Y-m-d H:i:s', $event_time ) ;
	$tm2 = datetime::createfromformat('Y-m-d H:i:s',$tm1->format('Y-m-d')." 00:00:00");

	return (int) ( ($tm1->getTimestamp() -  $tm2->getTimestamp())%(24*60*60) / $IntervalInSeconds  )+1;

}



//////////////////////////////////////////////////////////////////
//    Agregate in convex 
/////////////////////////////////////////////////////////////////
function emptyConvex( &$arr, $WeeksToCount, $IntervalInSeconds ) {

	for( $day = 1; $day <= 7*$WeeksToCount; $day++) {
		//$arr[$day]['EventTime'] = new DateTime();
		for ($partOfDay = 1; $partOfDay <= 24*60*60/$IntervalInSeconds; $partOfDay++) {
			$arr[$day][$partOfDay]['Min'] = 0;
			$arr[$day][$partOfDay]['Max'] = 0;
			$arr[$day][$partOfDay]['Avg'] = 0;
			$arr[$day][$partOfDay]['nrEvents'] = 0;	// Number of records
			$arr[$day][$partOfDay]['nOcc'] = 0;		// Number of occupants
//			$arr[$day][$partOfDay]['Min'] = 0;						
		}

	}
}


?>