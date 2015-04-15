<?php


    // http://localhost/bs/dist/php/add_data.php?DoorID=D1&event_time=NOW()&transition=1&Confidence=99

    // Connect to MySQL
    include("include/dbconnect.php");

    $doorID = strip_tags($_GET["DoorID"]);
    $transition = strip_tags( $_GET["transition"]);
    $confidence = strip_tags($_GET["Confidence"]);
    $timestamp = strip_tags($_GET["event_time"]);

    // For dealing with Laszlo's http event_time, send as 'NOW();'


    if( !strcasecmp ( trim(strtr($timestamp,'{}[]()/?;:',"          ")), 'NOW' ) ) {
        $timestamp = date('Y-m-d H:i:s');
    }

    $validStatement = true;
    if (!is_numeric($transition)) {

    $validStatement = false;
    error_log("transition is NOT numeric: DoorID:".$doorID."Time: ".date('Y-m-n H:i:s'));
    }

    $date = date_parse($timestamp);
    if ( $date['error_count'] <> 0 ) {
    $validStatement = false;
    error_log("Invalid date DoorID:".$doorID."Time: ".date('Y-m-n H:i:s'));
    }
    //Check for confidence
    if ($confidence > 100 or $confidence < 0)  {
        error_log("Invalid confidence DoorID:".$doorID." confidence: ".$confidence);
        $validStatement = false;
    }

    $doorSQL = "SELECT * FROM buildingplan WHERE DoorID ='".$doorID."'";
    if( !($result = $pdbh->query($doorSQL))  || $result->num_rows == 0 ) {
        error_log("Invalid DoorID:".$doorID."Time: ".date('Y-m-n H:i:s'));
        $validStatement = false;        
    }
    if (  $validStatement ):

        // Prepare the SQL statement
        $SQL = "INSERT INTO b42Snapshot( DoorID, event_time, transition, Confidence, server_time) ".
               " VALUES ( '".$doorID."', NOW(), '".$transition."', '".$confidence."', '".$timestamp."')";     
    /*    $SQL = "INSERT INTO b42Snapshot( DoorID, event_time, transition, Confidence, server_time) ".
               " VALUES ( '".$_GET["DoorID"]."', '".$_GET["event_time"]."', '".$_GET["transition"]."', '".$_GET["Confidence"]."', NOW())";     
    */

   // while (!($pdbh->query("LOCK TABLE currentstate")) {}  // semaphore to ensure serial writing on multiple requests
        if (!$pdbh->query($SQL) ) {
                echo $SQL."<br>";
                echo "1st CALL failed... "."<br>";
        } else {
            $sql = "CALL updatecurrentstate( '".$doorID."', '".$transition."','".$confidence."', NOW() )";
            if( !$pdbh->query($sql) ) {
                echo $sql."<br>";
                echo "2nd CALL failed... "."<br>";
            }
        }
   // $pdbh->query("UNLOCK TABLES");

    endif;

    $pdbh->close();

?>