<?php
 
/*
 * All database connection variables
 */

$mysql_host = "localhost";
$mysql_database = "test_db";
$mysql_user = "root";
$mysql_password = "";

define('DB_USER', $mysql_user); // db user
define('DB_PASSWORD', $mysql_password); // db password (mention your db password here)
define('DB_DATABASE', $mysql_database); // database name
define('DB_SERVER', $mysql_host); // db server / db host

/*
 * Google API Key
 */

define("GOOGLE_API_KEY", "AIzaSyAm3P9UiRpsNTx7w4YYdPsKDRj2FqOdpMw"); // Place your Google API Key - for GCM

date_default_timezone_set("Asia/Kolkata");

?>