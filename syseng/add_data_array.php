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

	// build SQL statement
	// note that labels in "VALUES" match $_POST field names coming from the form
    // Prepare the SQL statement
    $sql = "INSERT INTO b42Snapshot( doorId, transition, confidence, event_time) ".
           " VALUES ( :doorId, :transition, :confidence, :event_time)";   
	// prepare
	$stmt = $pdo->prepare($sql);

	// execute
	$result = $stmt->execute( $data );
	/*	echo '<br />RESULT: ', $result;*/
 //while (!($pdo->query("LOCK TABLE currentstate")) ) {}  // semaphore to ensure serial writing on multiple requests
    $sql = "CALL updatecurrentstate( '".$data["doorId"]."', '".$data["transition"]."','".$data["confidence"]."', '".$data["event_time"]."' )";
    if( !$pdo->query($sql) ) {
        echo $sql."<br />";
        echo "2nd CALL failed... "."<br />";
    }
  //$pdo->query("UNLOCK TABLES");



	// closes the database connection
	$pdo = NULL;
	
}	else {
	die( "No valid input");
}
