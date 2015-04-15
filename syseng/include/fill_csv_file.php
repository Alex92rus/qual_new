<?php 
ini_set('max_execution_time', 2500);

include("../include/dbconnect.php");

$records = 10000; 

$meanInterval = (int)(10*(7-2)*(24)*60*60 / $records);	// mean intervall in secconds


// read doors from buildingplan into array
$SQL = "SELECT  doorId, room1ID, room2ID FROM buildingplan ORDER BY 1";
if ( !$result = $pdbh->query($SQL) ) {
	echo $SQL."<br />";
	exit;
}

$doorArr = array();
$idx = 0;
while($row = $result->fetch_row() ) {
	$doorArr[$idx]['doorId'] = $row[0];
	$doorArr[$idx]['room1Id'] = $row[1];
	$doorArr[$idx]['room2Id'] = $row[2];
	$doorArr[$idx]['confidence'] = (string)(rand(8500, 9900)/100);
	$idx++;
}

//var_dump($doorArr);


$SQL = "SELECT  RoomID, NumberOfPeople FROM currentstate ORDER BY 1";
if ( !$result = $pdbh->query($SQL) ) {
	echo $SQL."<br />";
	exit;
}

$roomArr = array();
while($row = $result->fetch_row() ) {
	$roomArr[$row[0]] = (int)$row[1];
}
//var_dump($roomArr);
// dump data for demo purposes
//echo '<br />POST data<hr />', var_dump($data);


$data = array( );
$endMoment = new DateTime();

$event_time = new DateTime();
if($event_time->format("G") > 16) {
	$event_time->SetTime(8, 45);
}

$event_time->sub(new DateInterval('P70D'));
//var_dump($startMoment);
while(true) {
	while (true) {
		$cIdx = rand( 0, $idx-1 ); 
		//var_dump($cIdx);
		$doorId = $doorArr[$cIdx]['doorId'];
		$rl = $doorArr[$cIdx]['room1Id'];
		$rr	= $doorArr[$cIdx]['room2Id'];
		$occR1 = $rl=='0000000000'?($event_time->format('G')>16?0:rand(3,6)):$roomArr[$rl];
		$occR2 = $roomArr[$rr];

		if($occR1==$occR2 && $occR1==0) {

			continue;	// both rooms are empty, so find other door. Random error could be emulated
		}

		if( $occR1 > $occR2 ) {
			$trans = rand( 1, $occR1 );
		} else {
			$trans = -rand( 1, $occR2 );
		}
		$roomArr[$rl] -= $trans;
		$roomArr[$rr] += $trans;

		//echo "NR:", $records, " Lroom: ", $rl, " Num: ", $roomArr[$rl], " Rroom :",$rr," Num: ", $roomArr[$rl], " trans: ",$trans, "<br />";

		$timespan = rand( 1, $meanInterval); 

		$event_time->modify('+'.$timespan.' seconds');

		//Deal with night hours
		if((int)$event_time->format("G") * 60 + rand(0,60) > 19*60) {
			$timespan = 10*60+rand(0,60);
			$event_time->modify('+'.$timespan.' minutes');
		}

		//var_dump($event_time);


		$dow = JDDayOfWeek(cal_to_jd( CAL_GREGORIAN, $event_time->format('m'), $event_time->format('d'), $event_time->format('Y')));

		if($dow == 6) $event_time->add(new DateInterval('P1D'));
		if($dow == 0) $event_time->add(new DateInterval('P1D'));


		$data['doorId'] = $doorId;
		$data['transition'] = $trans;
		$data['confidence'] = $doorArr[$cIdx]['confidence'];
		$data['event_time'] = $event_time->format("Y-m-d H:i:s");

		post_data( $data );

		break;

	}


	if( --$records == 0  ) break;

	if( $event_time > $endMoment ) break;

	if( $records%100 == 0) {
		echo "<br /".$records;
	}


}


function post_data( &$data ){

	$url = 'http://localhost/qualocc/syseng0411/syseng/add_data_array.php';

	$fields = array(

						"data[doorId]" => $data['doorId'],	//DoorID
						"data[transition]" => $data['transition'],	// Transition
						"data[confidence]" => $data['confidence'],	// Confidence				
						"data[event_time]" => $data['event_time']	// MeasuredAtTime
					

				);

			//echo '<br />PRE data<hr />', var_dump($fields), '<br /> +++++++++++++++++ <br />';

	$fields_string =  http_build_query( $fields ) ;

	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL,$url);
	//curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
	//echo $fields_string;
	//execute post
	$result = curl_exec($ch);	
	curl_close($ch);	// free url resources (ch also). Could slow down execution to emulate reality

	return 0;
}

