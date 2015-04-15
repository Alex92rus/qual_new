<?php

if(!isset( $history)) {
  include('../include/get_history_room.php');
}

// Print out rows
$MAXROWS = 30;

$prefix = '';
echo "[\n";
for( $nr=1; $nr<=$rn;$nr++ ) {
  echo $prefix . " {\n";
  echo '  "nr": ' . $nr .',' . "\n";
  echo '  "transition": ' . $history[$nr]['transition'] . ',' . "\n";
  echo '  "event_time": ' . $history[$nr]['event_time'] .  "\n";
  echo " }";
  $prefix = ",\n";

  if($nr==$MAXROWS) { break;}
}
echo "\n]";



?>