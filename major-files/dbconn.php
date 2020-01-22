<?php
$dbConn = new mysqli('localhost', 'twa###', 'twa###XX', 'cooper_flights###');
if($dbConn->connect_error) {
    die("failed to connect to the database: " . $dbConn->connect_error);
}
?>
