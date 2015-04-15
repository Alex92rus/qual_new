<?php

if(!isset( $history)) {
  include('../include/get_convex_weekly.php');
}

// Print out rows
$MAXROWS = 9000;

$prefix = '';
echo "[\n";
for( $nr=1; $nr<=$rn;$nr++ ) {
  echo $prefix . " {\n";
  echo '  "date": "' . str_replace("", "T", $history[$nr]['date']).'",' . "\n";
  echo '  "value": ' . $history[$nr]['value'] . ',' . "\n";
  echo '  "fromValue": ' . $history[$nr]['fromValue'] . ',' . "\n";
  echo '  "toValue": ' . $history[$nr]['toValue'] .  "\n";
  echo " }";
  $prefix = ",\n";

  if($nr==$MAXROWS) { break;}
}
echo "\n]";




?>