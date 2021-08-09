<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_storage = "localhost";
$database_storage = "storage-demo";
$username_storage = "storage-demo";
$password_storage = "storage-demo";
$storage = new MySQLi($hostname_storage, $username_storage, $password_storage, $database_storage);
$storage->set_charset("utf8");

if ($storage->connect_error) {
	trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
}
?>