
<?php

$dimension = 0; // to keep rows nr in BFC plan
$plan = Array();

include('../include/dbconnect.php');


$sql = "SELECT f.RuleId, f.RoomID, f.MaxOcupancy, f.MinOcupancy, f.MustBeForced, f.FromTime, f.ToTime, c.NumberOfPeople,  SUM(co.Occupants) as Occupants, c.Confidence".
		  " FROM forcedrules f INNER JOIN currentstate c ON f.RoomID = c.RoomID". 
		  "	  LEFT JOIN camera_occupants co ON co.RoomID = f.RoomID AND ( TO_SECONDS(co.EnteredOn) BETWEEN TO_SECONDS(f.FromTime) AND TO_SECONDS(f.ToTime) )".
		  " WHERE (TO_SECONDS(TIME(NOW())) BETWEEN TO_SECONDS(FromTime) AND TO_SECONDS(ToTime)".
		  "  AND DayOfWeek = DAYOFWEEK(NOW()))".
		  "  AND f.MustBeForced".
		  "  AND c.NumberOfPeople > f.MaxOcupancy ".
          " GROUP BY f.RuleId";

//echo $sql;
$result = mysqli_query($pdbh, $sql);

if (mysqli_num_rows($result) > 0) {
    // Lead out people from every room
    while($row = mysqli_fetch_assoc($result)) {


    	if( $row["Occupants"] == 0 OR $row["MaxOcupancy"] > 0) {
			get_out($row["RoomID"], $row["NumberOfPeople"], $row["MaxOcupancy"], $row["Confidence"]);
		} else {
			echo "There is peoples in room: ".$row["RoomID"];
		}


    }
} else {
    //echo "0 results";
}

mysqli_close($pdbh);



function get_out( $iRoomID, $iNumberOfPeople, $MaxOcupancy, $Confidence) {
	$peopleToExtract = $iNumberOfPeople - $MaxOcupancy;
	traverseBFS(  $iRoomID, $peopleToExtract, $Confidence );
}




function  traverseBFS( $roomToClean, $pOut, $Confidence ) {

	GLOBAL $pdbh;
	GLOBAL $dimension;	// plan BFC rows count
	GLOBAL $plan;

	// First init plan array
	init_plan( $roomToClean );

	$trace = array();	// to be filled with path out
						// Structure:
						// 1st DoorID => direction (+/-1)
	                    // 2nd DoorID => direction (+/-1)
	                    //.....
	$steps = calc_path_out( $trace, $roomToClean, "0000000000" );	// "0000000000" stands for outer space

 	//return;

	if ($steps >= 0) {
		trace_out( $trace, $steps, $pOut, $Confidence );
	} else {
	 echo "NO WAY!";
	 die();
	}

	unset( $trace );

	restore_plan($roomToClean);	// empty entered and parent to

	// for lsat two days. If not found - try without this constrain
	// We'll use Global Array for building map

}

function calc_path_out( &$trace, $roomToClean, $targetRoom ) {
	GLOBAL $dimension;
	GLOBAL $plan;

	$success = false;
	$neighbours = Array();
	$stepNr = 0;


	// fill starting room neighbours doors
	$idx=0;
	for ($i = 0; $i<$dimension; $i++ ) {
		if ($plan[$i]["Room1ID"] == $roomToClean OR $plan[$i]["Room2ID"] == $roomToClean ) {
			array_push($neighbours, $i);
			$plan[$i]["Visited"]++;
			$plan[$i]["Parent"] = 0;
			$idx++;
		}
	}
	// next
	while ( !$success) {
			$neighbours = next_circle( $neighbours, $success, $targetRoom);

			if (count($neighbours) == 0) {

				echo "no trace to out";
				die();
			}
	}

	// Last (exit) door is in the last element of $neighbours
	$i = end($neighbours);
	$stepNr = 0;
	$RoomTo = $targetRoom;
	do {

		$trace[$stepNr]["Id"] = $i;
		if ($plan[$i]["Room1ID"] == $RoomTo) {
			$Direction = -1;
			$RoomTo = $plan[$i]["Room2ID"];
		} else {
			$Direction = 1;
			$RoomTo = $plan[$i]["Room1ID"];		
		}
		$trace[$stepNr]["Direction"] = $Direction;


		$i = $plan[$i]["Parent"];
		$stepNr++;

	} while ( $plan[$i]["Parent"] <> 0 ) ;

	// Add start door...
	$trace[$stepNr]["Id"] = $i;
	$trace[$stepNr]["Direction"] = $plan[$i]["Room1ID"] == $roomToClean?1:-1;

	array_reverse($trace, false);

	return $stepNr;
}

function next_circle( $neighbours, &$success, $targetRoom  ) {
	GLOBAL $dimension;
	GLOBAL $plan;

	$success = false;

	$newneighbours = Array();
	$steps = 0;
	$dim = 0;

	// select doors in room
	foreach ($neighbours as $Id ) {
	 	$roomToClean = $plan[$Id]["Room1ID"];
	 	for ($i = 0; $i<$dimension; $i++ ) {
			if( $plan[$i]["Visited"] == 0 AND ($plan[$i]["Room1ID"] == $roomToClean OR $plan[$i]["Room2ID"] == $roomToClean )) {
				array_push($newneighbours, $i) ;
				$plan[$i]["Visited"] ++;
				$plan[$i]["Parent"] = $Id;
			
				if ($plan[$i]["Room1ID"] == $targetRoom OR $plan[$i]["Room2ID"] == $targetRoom  ) {
					$success = true;
					return $newneighbours;
				}
			}
		}
	 	$roomToClean = $plan[$Id]["Room2ID"];
	 	for ($i = 0; $i<$dimension; $i++ ) {
			if( $plan[$i]["Visited"] == 0 AND ($plan[$i]["Room1ID"] == $roomToClean OR $plan[$i]["Room2ID"] == $roomToClean )) {
				array_push($newneighbours, $i) ;
				$plan[$i]["Visited"] ++;
				$plan[$i]["Parent"] = $Id;
			
				if ($plan[$i]["Room1ID"] == $targetRoom OR $plan[$i]["Room2ID"] == $targetRoom  ) {
					$success = true;
					return $newneighbours;
				}
			}
		}

	}


	return $newneighbours;

}


function restore_plan( $roomToClean ) {
	init_plan( $roomToClean );

}

function trace_out( $trace, $stepscount, $peoples, $Confidence ) {
	GLOBAL $dimension;
	GLOBAL $plan;
	GLOBAL $pdbh;

	// prepare and bind
	$stmt = $pdbh->prepare("INSERT INTO correcterr (DoorID, Transition, Confidence) VALUES (?, ?, ?)");
	$stmt->bind_param("sid", $DoorID, $Transition, $Confidence );
	// prepare and bind
	$stmt_rooms_1 = $pdbh->prepare("UPDATE currentstate SET NumberOfPeople = NumberOfPeople - ?  WHERE RoomID=?");
	$stmt_rooms_1->bind_param("is", $Transition, $Room1ID );
	// prepare and bind
	$stmt_rooms_2 = $pdbh->prepare("UPDATE currentstate SET NumberOfPeople = NumberOfPeople + ?  WHERE RoomID=?");
	$stmt_rooms_2->bind_param("is", $Transition, $Room2ID );

	for ($i = $stepscount; $i>=0; $i--  ) {

		$idx = $trace[ $i ]["Id"];
		$Transition = $trace[$i]["Direction"] * $peoples;
		$DoorID = $plan[$idx]["DoorID"];
		$Room1ID = $plan[$idx]["Room1ID"];
		$Room2ID = $plan[$idx]["Room2ID"];

		//$Confidence = 0;

		$stmt->execute();
		$stmt_rooms_1->execute();
		$stmt_rooms_2->execute();
	}
}

// fil in global array path for exit of the room roomToClean
function  init_plan( $roomToClean ) {

	GLOBAL $pdbh;
	GLOBAL $dimension;	// plan BFC rows count
	GLOBAL $plan;


    $done = false;
    $i = 0;


		// create plan from buildingplan

	$sql = "SELECT  DISTINCT DoorID, Room1ID, Room2ID from buildingplan";	// here shoul be added order by 

    $result = mysqli_query($pdbh, $sql);
    $dimension = mysqli_num_rows($result) ;
		// init array from building plan without ordering
    if ($dimension > 0) {
  	  // 
   		while($row = mysqli_fetch_assoc($result) ) {
   			$plan[$i]["DoorID"] = $row["DoorID"];
   			$plan[$i]["Room1ID"] = $row["Room1ID"];
   			$plan[$i]["Room2ID"] = $row["Room2ID"];   			
   			$plan[$i]["Visited"] = 0;   			
   			$plan[$i]["Parent"] = 0;	// just to be same size

   			$i++;
   		}
   	}

}


?>


