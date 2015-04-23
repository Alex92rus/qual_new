<?php

if(!isset($rooms)) {
  include('../include/get_history_errors_door.php');
}



// Print out rows
$prefix = '';
echo "[\n";
for( $nr=1; $nr<=$rn;$nr++ ) {
  echo $prefix . " {\n";
  echo '  "nr": "' . $nr .'",' . "\n";
  echo '  "event_time": "' .  $history[$nr]['event_time'] . '",' . "\n";
  echo '  "corrections": ' . $history[$nr]['transition'].  "\n";
  echo " }";
  $prefix = ",\n";
}
echo "\n]";


?>