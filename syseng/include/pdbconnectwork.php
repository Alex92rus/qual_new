<?php
/*
$MyUsername = "bd22a66eb6f092"; //mysql username
$MyPassword = "e94206e8"; // password for mysql
$MyHostname = "eu-cdbr-azure-west-a.cloudapp.net"; // server on which it resides
*/

$MyUsername = "root"; //mysql username
$MyPassword = ""; // password for mysql
$MyHostname = "localhost:3306"; // server on which it resides

$pdbh = new mysqli($MyHostname, $MyUsername, $MyPassword, "qualoccADTEehTJv");

if ($pdbh->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
?>