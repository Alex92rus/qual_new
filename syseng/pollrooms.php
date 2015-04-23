<?php
//header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");

	//Connect to database
    include("include/dbconnect.php");
    
    $SQL = "SELECT a.RoomID, a.NumberOfPeople, c.Crowded, a.Confidence
            FROM  currentstate a, occupancylevels c
            WHERE a.RoomID = c.RoomID";

    if (isset($_GET["floor"])) {
        $floor = $pdbh->real_escape_string($_GET["floor"]);
        $SQL = "SELECT a.RoomID, a.NumberOfPeople, c.Crowded, a.Confidence
                 FROM  currentstate a, roomparameters b, occupancylevels c
                 WHERE a.RoomID = b.RoomID
                 AND a.RoomID = c.RoomID
                 AND b.RParamValue = '$floor' ";
    } 
    if (isset($_GET["room"])) {
        $room = $pdbh->real_escape_string($_GET["room"]);
        $SQL = " SELECT a.RoomID, a.NumberOfPeople, c.Crowded, a.Confidence
                 FROM  currentstate a, occupancylevels c
                 WHERE a.RoomID = c.RoomID
                 AND a.RoomID = '$room'";
    } 
   $SQL .= " ORDER BY CAST(SUBSTRING(a.RoomID,2) AS SIGNED)";
   
   $roomInfo =  $pdbh->query($SQL);
   $JSON = "[";
   while ($row = $roomInfo->fetch_array(MYSQLI_ASSOC)) {
      if ($JSON != "[") {
        $JSON .=", ";
      }
      $JSON .= '{"RoomID":"' . $row["RoomID"] . '",';
      $JSON .= '"NumberOfPeople":"'. $row["NumberOfPeople"] . '",';
      $JSON .= '"Confidence":"'. $row["Confidence"] . '",';
      $JSON .= '"crowded":"'. $row["Crowded"] . '"}';
    }
    $JSON .="]";

    $pdbh->close();

    echo($JSON);
?>