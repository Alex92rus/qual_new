<?php    

  // http://servername/add_data_wifi.php?RoomID=R3&DeviceID=W1&MeasuredAtTime=2014-04-01 18:30:00&NumberOfDevices=3 
	
	// Connect to MySQL
  include("include/dbconnect.php");

  $roomID = strip_tags($_GET["RoomID"]);
  $deviceID = strip_tags($_GET["DeviceID"]);
  $measuredAtTime = strip_tags( $_GET["MeasuredAtTime"]);
  $numberOfDevices = strip_tags($_GET["NumberOfDevices"]);


  if( !strcasecmp ( trim(strtr($measuredAtTime,'{}[]()/?;:',"          ")), 'NOW' ) ) {
      $timestamp = date('Y-m-d H:i:s');
  }

  $validStatement = true;

  if (!is_numeric($numberOfDevices)) {
     $validStatement = false;
     error_log("transition is NOT numeric: ".$_SERVER['PHP_SELF']." RoomID:".$roomID." numberOfDevices: ".$numberOfDevices);
  }

  $date = date_parse($measuredAtTime);
  if ( $date['error_count'] <> 0 ) {
      $validStatement = false;
      error_log("Invalid date ".$_SERVER['PHP_SELF']." roomID:".$roomID." measuredAtTime: ".$measuredAtTime);
  }
 
  $roomSQL = "SELECT * FROM currentstate WHERE RoomID ='".$roomID."'";
  if( !($result = $pdbh->query($roomSQL))  || $result->num_rows == 0 ) {
      $validStatement = false;
      error_log("Invalid roomID:".$roomID."Time: ".date('Y-m-n H:i:s'));
 }


  if ($validStatement) {
    // Prepare the SQL statement
    $SQL = "INSERT INTO `check_wifi` (RoomID, Device_ID, MeasuredAtTime, NumberOfDevices) 
  					VALUES ('".$roomID."','".$deviceID."', '".$measuredAtTime."', '".$numberOfDevices."')";     

    // Execute SQL statement 
    if (!$pdbh->query($SQL) ) {
            echo $SQL."<br>";
            echo "Insert failed... "."<br>";
    } 
  }
  
  $pdbh->close();
?>
