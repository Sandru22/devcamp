<?php

// MODIFICARE PE GITHUB
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = 'localhost';
$username = 'root';
$password = 'root';
$dbname='devcamp';

$conn = new mysqli($servername,$username,$password,$dbname);

if($conn->connect_error){
    die("Conexiune a esuat". $conn->connect_error);
}

$conn->set_charset("utf8");
?>
