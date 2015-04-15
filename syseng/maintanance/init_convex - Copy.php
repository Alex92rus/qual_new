
<?php

// Create empty convex records for every interval and day of week 

// Connect to MySQL
$username = "root";  // enter your username for mysql
$password = "";  // enter your password for mysql
$servername = "localhost";      // this is usually "localhost" unless your database resides on a different server
$dbname = "qualoccadteehtjv" ;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
 // Check connection
if ($conn->connect_errno) {
     die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT Id, IntervalInSeconds, WeeksToCount FROM `convexdefinitions` ORDER BY Id DESC LIMIT 1";
$result = $conn->query($sql);
//var_dump( $conn );
if ( $result->num_rows > 0) {
    // Lead out people from every room
    $row = $result->fetch_row();
    $IntervalInSeconds = $row[1]; 
    $WeeksToCount = $row[2];
} else {

	die("No Convex definitions");
} 


$empty = 0;

$stmt = $conn->prepare("INSERT INTO convex VALUES (?, ?, ?, ?, ?, ?)");
//var_dump($stmt);
$stmt->bind_param("siiiii", $RoomId, $dow, $Interval, $empty, $empty, $empty );

$sql = "SELECT Room1Id as RoomId FROM `buildingplan` UNION DISTINCT SELECT Room2Id as RoomId FROM `buildingplan`";
$result = $conn->query($sql);

while($row = $result->fetch_row() ) {
	$RoomId = $row[0];
	echo "RoomId: ".$RoomId."\n";
	if( $RoomId == "0000000000") {
		continue;
	}
	for ($dow=1; $dow<=7; $dow++) {
		for ($Interval = 0; $Interval < 24*60*60/$IntervalInSeconds; $Interval++) {
			$stmt->execute();
		}
	}

}


?>