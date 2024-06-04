<?php
$host = 'localhost';
$username = 'root';
$password = '1234';
$dbname = 'fichajes_app';
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
