<?php

if(!isset($rooms)) {
  include('../include/get_history_error_door_usage.php');
}

// Print out rows
$prefix = '';
echo "[\n";
for( $nr=1; $nr<=$rn;$nr++ ) {
  echo $prefix . " {\n";
  echo '  "nr": "' . $nr .' '.  $history[$nr]['doorId'] . '",' . "\n";
  echo '  "doorId": "' .  $history[$nr]['doorId'] . '",' . "\n";
  echo '  "corrections": ' . $history[$nr]['transition'].  "\n";
  echo " }";
  $prefix = ",\n";
}
echo "\n]";



?>