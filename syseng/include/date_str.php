<?php


 if(isset($startdate)) { 
    if (!($ds = DateTime::createFromFormat('Y-m-d H:i:s', $startdate))) {
          if( !($ds = DateTime::createFromFormat('Y-m-d', $startdate))) {
            $ds = DateTime::createFromFormat('Y-m-d', '1970-01-01');
          }
          
    }

    $ds_str = $ds->format('Y-m-d');
  } // Should BE YYYY-mm-dd hh:ii:ss

 if(isset($enddate)) { 
    if (!($de = DateTime::createFromFormat('Y-m-d H:i:s', $enddate))) {
          if( !($de = DateTime::createFromFormat('Y-m-d', $enddate))) {
            $de = DateTime::createFromFormat('Y-m-d', '1970-01-01');
          }
          
    }

    $de_str = $de->format('Y-m-d');
  } // Should BE YYYY-mm-dd hh:ii:ss


?>