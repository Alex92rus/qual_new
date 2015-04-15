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
    $sql = "INSERT INTO `check_wifi` (RoomID, Device_ID, MeasuredAtTime, NumberOfDevices) ".
           " VALUES ( :roomID, :deviceID, :measuredAtTime, :numberOfDevices)";  

	// prepare
	$stmt = $pdo->prepare($sql);

	// execute
	$result = $stmt->execute( $data );

	// closes the database connection
	$pdo = NULL;
	
}	else {
	die( "No valid input");
}
