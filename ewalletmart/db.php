<?php
// db.php
$host = '127.0.0.1';
$db   = 'ewalletmart_db';
$user = 'root';      // default XAMPP MySQL user
$pass = '';          // default XAMPP MySQL password (often empty)
$charset = 'utf8mb4';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    die("DB connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset($charset);
