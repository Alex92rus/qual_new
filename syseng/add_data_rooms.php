<?php
    
	// http://serverbase/add_data_rooms.php?RoomId=R1&DeviceId=P1&HasPresence=1&Confidence=99&MeasuredAtTime=2014-04-01%2018:00:00 
	
	
	// Connect to MySQL
    include("include/dbconnect.php");

	  $roomID = strip_tags($_GET["RoomId"]);
	  $deviceID = strip_tags( $_GET["DeviceId"]);
	  $hasPresence = strip_tags($_GET["HasPresence"]);
	  $confidence = strip_tags( $_GET["Confidence"]);
	  $measuredAtTime = strip_tags($_GET["MeasuredAtTime"]);

    if( !strcasecmp ( trim(strtr($measuredAtTime,'{}[]()/?;:',"          ")), 'NOW' ) ) {
        $timestamp = date('Y-m-d H:i:s');
    }

    $validStatement = true;

    if (!is_numeric($hasPresence)) {
   		 $validStatement = false;
    	 error_log("transition is NOT numeric: ".$_SERVER['PHP_SELF']." RoomID:".$roomID."Time: ".date('Y-m-n H:i:s'));
    }

    $date = date_parse($measuredAtTime);
    if ( $date['error_count'] <> 0 ) {
   		 	$validStatement = false;
    		error_log("Invalid date ".$_SERVER['PHP_SELF']." roomID:".$roomID."Time: ".date('Y-m-n H:i:s'));
    }
    //Check for confidence
    if ($confidence > 100 or $confidence < 0)  {
        $validStatement = false;
        error_log("Invalid confidence ".$_SERVER['PHP_SELF']." roomID:".$roomID."confidence: ".$confidence);
    }

    $roomSQL = "SELECT * FROM currentstate WHERE RoomID ='".$roomID."'";
    if( !($result = $pdbh->query($roomSQL))  || $result->num_rows == 0 ) {
        $validStatement = false;
        error_log("Invalid roomID:".$roomID."Time: ".date('Y-m-n H:i:s'));
   }

    if (  $validStatement ):
		    // Prepare the SQL statement
		    $SQL = "INSERT INTO checkpresence (RoomId, DeviceId, HasPresence, Confidence, MeasuredAtTime ) 
								VALUES ('".$roomID."', '".$deviceID."', '".$hasPresence."', '".$confidence."', '".$measuredAtTime."')";     

		    // Execute SQL statement 
		    if (!$pdbh->query($SQL) ) {
		            echo $SQL."<br>";
		            echo "Insert failed... "."<br>";
		    }
		endif;

    $pdbh->close();
?>
