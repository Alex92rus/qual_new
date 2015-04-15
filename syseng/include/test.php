<?php
$timestamp='Wow();';
var_dump( trim(strtr($timestamp,'{}[]()/\?;:',"           ")));

    if( !strcasecmp ( trim(strtr($timestamp,'{}[]()/?;:',"          ")), 'NOW' ) ) {
        $timestamp = date('Y-m-d H:i:s');
        var_dump($timestamp);
    }

?>