<?php
// Building Web Forms: Capturing <form> Data

 include('include/dbconnectPDO.php');

// check to see if data has been set
if (isset($_POST['data'])) {
	// extract $_POST data into a variable
	$data = $_POST['data'];


	// dump data for demo purposes
	//echo '<br />POST data<hr />', var_dump($_POST['data']);

	// sanatize data
	foreach ($data as $key => $value) {
		$data[$key] = strip_tags($value);
	}

    if( !strcasecmp ( trim(strtr($data['event_time'],'{}[]()/?;:',"          ")), 'NOW' ) ) {
        $data['event_time'] = date('Y-m-d H:i:s');
    }


    $validStatement = true;

    if (!is_numeric( $data['transition'] )) {
   		 $validStatement = false;
    	 error_log("transition is NOT numeric: ".$_SERVER['PHP_SELF']." ".print_r($data));
    }

    //Check for confidence
    if ($data['confidence'] > 100 or $data['confidence']  < 0)  {
        $validStatement = false;
        error_log("Invalid confidence ".$_SERVER['PHP_SELF']." ".print_r($data));
    }

    $date = date_parse($data['event_time']);
    if ( $date['error_count'] <> 0 ) {
   		 	$validStatement = false;
    		error_log("Invalid date ".$_SERVER['PHP_SELF']." ".print_r($data));
    }


    $doorSQL = "SELECT COUNT(*) as num_rows FROM buildingplan WHERE doorId ='".$data['doorId']."'";
    if( !($stmt = $pdo->query($doorSQL)) || !($row=$stmt->fetch())  || $row['num_rows'] == 0 ) {
        $validStatement = false;
        error_log("Invalid doorId:".$data['doorId'].$_SERVER['PHP_SELF']." ".print_r($data));
   }


	// build SQL statement
	// note that labels in "VALUES" match $_POST field names coming from the form
    // Prepare the SQL statement
    if ($validStatement) {
	    $sql = "INSERT INTO b42Snapshot( doorId, transition, confidence, server_time, event_time) ".
	           " VALUES ( :doorId, :transition, :confidence, :event_time, NOW())";   
		// prepare
		$stmt = $pdo->prepare($sql);

		// execute
		$result = $stmt->execute( $data );
		/*	echo '<br />RESULT: ', $result;*/
	 //while (!($pdo->query("LOCK TABLE currentstate")) ) { usleep(10000);}  // semaphore to ensure serial writing on multiple requests
	    $sql = "CALL updatecurrentstate( '".$data["doorId"]."', '".$data["transition"]."','".$data["confidence"]."', '".$data["event_time"]."' )";
	    if( !$pdo->query($sql) ) {
	        echo $sql."<br />";
	        echo "2nd CALL failed... "."<br />";
	    }
	  //$pdo->query("UNLOCK TABLES");
	} else {
		echo '<br />'."!Errors in fields!".'<br />';
	}
	// closes the database connection
	$pdo = NULL;
	
}	else {
	die( "No valid input");
}
