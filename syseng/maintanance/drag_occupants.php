
<?php

$dimension = 0; // to keep rows nr in BFC plan
$plan = Array();

include('../include/dbconnect.php');


$sql = "SELECT c.RoomID, c.NumberOfPeople,  SUM(co.Occupants) as Occupants".
		  " FROM currentstate c ". 
		  "	  LEFT JOIN camera_occupants co ON co.RoomID = c.RoomID ".
		  " WHERE c.NumberOfPeople = 0 AND co.Occupants > 0 ".
          " GROUP BY 1";

//echo $sql;
$result = mysqli_query($pdbh, $sql);

if (mysqli_num_rows($result) > 0) {
    // Drag in people to every room
    while($row = mysqli_fetch_assoc($result)) {
		get_in($row["RoomID"], $row["Occupants"]);
    }
} else {
    //echo "0 results";
}

mysqli_close($pdbh);



function get_in( $iRoomID, $Occupants) {
	for ($i=1; $i<=$Occupants; $i++) {
		traverseBFS(  $iRoomID, 1 );
	}
}




function  traverseBFS( $roomToClean, $pIn ) {

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
	$steps = calc_path_in( $trace, $roomToClean, "0000000000" );	// "0000000000" stands for outer space

 	//return;
 	var_dump($trace);

	if ($steps >= 0) {
		trace_in( $trace, $steps, $pIn );
	} else {
	 echo "NO WAY!";
	 die();
	}

	unset( $trace );

	restore_plan($roomToClean);	// empty entered and parent to

	// for last two days. If not found - try without this constrain
	// We'll use Global Array for building map

}

function calc_path_in( &$trace, $roomToClean, $targetRoom ) {
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

				echo "no trace from out";
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

				//check for people to drag
			
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

function trace_in( $trace, $stepscount, $peoples ) {
	GLOBAL $dimension;
	GLOBAL $plan;
	GLOBAL $pdbh;


		echo "<br /> plan/steps ".$stepscount." <br />";
		var_dump($plan);
		echo "<br />===================<br />";

	// prepare and bind
	$stmt = $pdbh->prepare("INSERT INTO correcterr (DoorID, Transition, Confidence) VALUES (?, ?, ?)");
	$stmt->bind_param("sii", $DoorID, $Transition, $Confidence );
	// prepare and bind
	$stmt_rooms_1 = $pdbh->prepare("UPDATE currentstate SET NumberOfPeople = NumberOfPeople - ?  WHERE RoomID=?");
	$stmt_rooms_1->bind_param("is", $Transition, $Room1ID );
	// prepare and bind
	$stmt_rooms_2 = $pdbh->prepare("UPDATE currentstate SET NumberOfPeople = NumberOfPeople + ?  WHERE RoomID=?");
	$stmt_rooms_2->bind_param("is", $Transition, $Room2ID );

	for ($i = 0; $i<=$stepscount; $i++  ) {

		$idx = $trace[ $i ]["Id"];
		$Transition = -1 * $trace[$i]["Direction"] * $peoples;
		$DoorID = $plan[$idx]["DoorID"];
		$Room1ID = $plan[$idx]["Room1ID"];
		$Room2ID = $plan[$idx]["Room2ID"];
		echo "<br />Step: ",$idx, "<br />";
/*		var_dump($plan[$idx]);
		echo "<br />Transition ",$Transition, "<br />";*/


		$Confidence = 0;

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


	// create plan from buildingplan - ORDERED BY DOOR probability transitions from last DAY
	$sql = "SELECT DISTINCT b.DoorID, b.Room1ID, b.Room2ID, COALESCE( SUM(Confidence)/COUNT(*), 100) as conf ".
   			" FROM buildingplan b LEFT JOIN b42snapshot s ON s.DoorID=b.DoorId AND TIMESTAMPDIFF(DAY, event_time, NOW()) < 2". 
   			" GROUP BY 1".
   			" ORDER BY conf ASC	";

//   	echo $sql;
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


