<?php

$host="Localhost";
$bdname="farhaevents";
$username="root";
$password="root";

try{
    $pdo = new PDO ("mysql:host=$host;dbname=$bdname;charset=UTF8", $username, $password);
    $pdo ->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
}
catch(PDOexception $e){
    die("Error:".$e->getMessage());
}
?>