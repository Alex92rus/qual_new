<?php
// Building Web Forms: Capturing <form> Data

 include('include/dbconnectPDO.php');

// check to see if data has been set
if (isset($_POST['data'])) {
	// extract $_POST data into a variable
	$data = $_POST['data'];

    if( !strcasecmp ( trim(strtr($data['MeasuredAtTime'],'{}[]()/?;:',"          ")), 'NOW' ) ) {
        $data['MeasuredAtTime'] = date('Y-m-d H:i:s');
    }

	// dump data for demo purposes
	//echo '<br />POST data<hr />', var_dump($_POST['data']);

	// sanatize data
	foreach ($data as $key => $value) {
		$data[$key] = strip_tags($value);
	}


    $validStatement = true;

    if (!is_numeric( $data['HasPresence'] )) {
   		 $validStatement = false;
    	 error_log("HasPresence is NOT numeric: ".$_SERVER['PHP_SELF']." ".print_r($data));
    }

    $date = date_parse($data['MeasuredAtTime']);
    if ( $date['error_count'] <> 0 ) {
   		 	$validStatement = false;
    		error_log("Invalid date ".$_SERVER['PHP_SELF']." ".print_r($data));
    }
    //Check for confidence
    if ($data['Confidence'] > 100 or $data['Confidence']  < 0)  {
        $validStatement = false;
        error_log("Invalid confidence ".$_SERVER['PHP_SELF']." ".print_r($data));
    }

    $roomSQL = "SELECT COUNT(*) as num_rows FROM currentstate WHERE RoomID ='".$data['RoomId']."'";
    if( !($stmt = $pdo->query($roomSQL)) || !($row=$stmt->fetch())  || $row['num_rows'] == 0 ) {
        $validStatement = false;
        error_log("Invalid roomID:".$data['RoomId'].$_SERVER['PHP_SELF']." ".print_r($data));
   }
	// build SQL statement
	// note that labels in "VALUES" match $_POST field names coming from the form
    // Prepare the SQL statement
   if ($validStatement) {
	    $sql = "INSERT INTO checkpresence( `RoomId`, `DeviceId`, `HasPresence`, `Confidence`, `MeasuredAtTime`, `EnteredOn`) ".
	           " VALUES ( :RoomId, :DeviceId, :HasPresence, :Confidence, :MeasuredAtTime, NOW())";   
		// prepare
		$stmt = $pdo->prepare($sql);

		// execute
		$result = $stmt->execute( $data );
		/*	echo '<br />RESULT: ', $result;*/
	} else {
		echo '<br />'."!Errors in fields!".'<br />';
	}

	// closes the database connection
	$pdo = NULL;
	
}	else {
	die( "No valid input");
}
