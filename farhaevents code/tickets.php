<?php
require "config.php";
if (!isset($_SESSION['user'])) {
    header("location:login.php");
}

if (!isset($_GET['resaId'])) {
    header("location:profile.php");
}

$resaId = $_GET['resaId'];
$userId = $_SESSION['user']['idUser'];

// Verify reservation belongs to user and get ticket details
$sql = "SELECT r.*, e.dateEvent, e.timeEvent, ev.eventTitle, ev.eventDescription, s.NumSalle,
        b.billetId, b.typeBillet, b.placeNum
        FROM Reservation r
        JOIN Edition e ON r.editionId = e.editionId
        JOIN Evenement ev ON e.eventId = ev.eventId
        JOIN Salle s ON e.NumSalle = s.NumSalle
        JOIN Billet b ON r.idReservation = b.idReservation
        WHERE r.idReservation = :resaId AND r.idUser = :userId";
$stmt = $pdo->prepare($sql);
$stmt->execute([':resaId' => $resaId, ':userId' => $userId]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($tickets)) {
    header("location:profile.php");
}

$eventInfo = $tickets[0]; // Event details are same for all tickets in reservation
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Tickets</title>
    <link rel="stylesheet" href="tickets-style.css?v=1">
</head>
<body>
    <header>
        <a href="profile.php"><h1>Your Tickets</h1></a>
    </header>
    
    <main class="tickets">
        <h2><?= $eventInfo['eventTitle'] ?></h2>
        <p class="event-info">
            Date: <?= $eventInfo['dateEvent'] . " " . $eventInfo['timeEvent'] ?><br>
            Venue: Hall <?= $eventInfo['NumSalle'] ?><br>
            Reservation ID: #<?= $resaId ?>
        </p>
        
        <div class="ticket-list">
            <?php foreach ($tickets as $ticket): ?>
                <div class="ticket">
                    <h3>Ticket #<?= $ticket['billetId'] ?></h3>
                    <p>Type: <?= $ticket['typeBillet'] ?></p>
                    <p>Seat Number: <?= $ticket['placeNum'] ?></p>
                    <div class="barcode"><?= $ticket['billetId'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <a href="profile.php" class="back-btn">Back to Profile</a>
    </main>
</body>
</html>