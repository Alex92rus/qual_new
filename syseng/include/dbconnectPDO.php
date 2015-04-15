<?php


/*		$user = "bd22a66eb6f092"; //mysql username
		$pass = "e94206e8"; // password for mysql
		$host = "eu-cdbr-azure-west-a.cloudapp.net"; // server on which it resides*/

	// database username
	$user = 'bd22a66eb6f092';
	// database password
	$pass = 'e94206e8';
	// data source = mysql driver, localhost, database = class
	$dsn = 'mysql:host=eu-cdbr-azure-west-a.cloudapp.net;dbname=qualoccadteehtjv';

/*	// database username
	$user = 'root';
	// database password
	$pass = '';
	// data source = mysql driver, localhost, database = class
	$dsn = 'mysql:host=localhost;dbname=qualoccadteehtjv';*/

	// create PDO object and activate an error mode of warning
	try {
		$pdo = new PDO($dsn, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	} catch (PDOException $e) {
    	echo 'Connection failed: ' . $e->getMessage();
    	exit();
	}


?>
