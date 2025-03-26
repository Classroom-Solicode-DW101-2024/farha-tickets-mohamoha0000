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

function GetEventType(){
    global $pdo;
    $catStmt = $pdo->query("SELECT DISTINCT eventType FROM evenement");
    $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
    return $categories;
}

function getLastIdClient() {
    global $pdo;
    $sql = "SELECT MAX(idUser) AS maxId FROM utilisateur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result= $stmt->fetch(PDO::FETCH_ASSOC);
    if(empty($result['maxId'])) {
        $MaxId = 0;
    } else {
        $MaxId = $result['maxId'];
    }
    return $MaxId;
}
?>