<?php 
session_start();
$host = 'localhost';
$dbname = 'farhaevents';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e) {
    echo "erreur conenction " . $e->getMessage();
}
?>