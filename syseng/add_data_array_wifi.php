<?php
// Building Web Forms: Capturing <form> Data

 include('include/dbconnectPDO.php');

// check to see if data has been set
if (isset($_POST['data'])) {
	// extract $_POST data into a variable
	$data = $_POST['data'];

	// sanatize data
	foreach ($data as $key => $value) {
		$data[$key] = strip_tags($value);
	}

    if( !strcasecmp ( trim(strtr($data['measuredAtTime'],'{}[]()/?;:',"          ")), 'NOW' ) ) {
        $data['measuredAtTime'] = date('Y-m-d H:i:s');
    }


    $validStatement = true;

    if (!is_numeric( $data['numberOfDevices'] )) {
   		 $validStatement = false;
    	 error_log("numberOfDevices is NOT numeric: ".$_SERVER['PHP_SELF']." ".print_r($data));
    }

    $date = date_parse($data['measuredAtTime']);
    if ( $date['error_count'] <> 0 ) {
   		 	$validStatement = false;
    		error_log("Invalid date ".$_SERVER['PHP_SELF']." ".print_r($data));
    }


    $roomSQL = "SELECT COUNT(*) as num_rows FROM currentstate WHERE RoomID ='".$data['roomId']."'";
    if( !($stmt = $pdo->query($roomSQL)) || !($row=$stmt->fetch())  || $row['num_rows'] == 0 ) {
        $validStatement = false;
        error_log("Invalid roomID:".$data['roomId'].$_SERVER['PHP_SELF']." ".print_r($data));
   }



	// build SQL statement
	// note that labels in "VALUES" match $_POST field names coming from the form
    // Prepare the SQL statement
    if ($validStatement) {
	    $sql = "INSERT INTO `check_wifi` (`RoomID`, `Device_ID`, `MeasuredAtTime`, `NumberOfDevices`) ".
	           " VALUES ( :roomId, :deviceId, :measuredAtTime, :numberOfDevices)";  

		// prepare
		$stmt = $pdo->prepare($sql);

		// execute
		$result = $stmt->execute( $data );
	} else {
		echo '<br />'."!Errors in fields!".'<br />';
	}

	// closes the database connection
	$pdo = NULL;
	
}	else {
	die( "No valid input");
}
