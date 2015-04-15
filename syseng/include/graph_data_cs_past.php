<?php

if(!isset($rooms)) {
  include('../include/get_currentstate_inpast.php');
}

// Print out rows
$prefix = '';
echo "[\n";
for( $nr=1; $nr<=$rn;$nr++ ) {
  echo $prefix . " {\n";
  echo '  "nr": "' . $nr .' '. $rooms[$nr]['RoomId'] . '",' . "\n";
  echo '  "RoomId": "' . $rooms[$nr]['RoomId'] . '",' . "\n";
  echo '  "NumberOfPeople": ' . $rooms[$nr]['NumberOfPeople'] . ',' . "\n";
  echo '  "Confidence": ' . $rooms[$nr]['Confidence'] . '' . "\n";
  echo " }";
  $prefix = ",\n";
}
echo "\n]";



?>