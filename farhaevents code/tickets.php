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
$sql = "SELECT r.*, e.dateEvent, e.timeEvent, ev.eventTitle, ev.eventDescription, ev.TariffNormal, ev.TariffReduit, s.NumSalle,
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
    <link rel="stylesheet" href="tickets-style.css?v=2">
</head>
<body>
    <header>
        <a href="profile.php"><h1>Your Tickets</h1></a>
    </header>
    
    <main class="tickets">
        <h2><?= $eventInfo['eventTitle'] ?></h2>
        <p class="event-info">
            Date: <?= date('l d F Y à H\hi', strtotime($eventInfo['dateEvent'] . ' ' . $eventInfo['timeEvent'])) ?><br>
            Venue: Centre Culturel Farha, Tanger<br>
            Reservation ID: #<?= $resaId ?>
        </p>
        
        <div class="ticket-list">
            <?php foreach ($tickets as $ticket): ?>
                <div class="ticket">
                    <div class="ticket-left">
                        <p class="ticket-id">NUMÉRO DE TICKET<br>#<?= $ticket['billetId'] ?></p>
                    </div>
                    <div class="ticket-main">
                        <div class="ticket-header">
                            <h2>ASSOCIATION FARHA</h2>
                        </div>
                        <h3 class="event-title"><?= $eventInfo['eventTitle'] ?></h3>
                        <p class="event-date">
                            <?= date('l d F Y à H\hi', strtotime($eventInfo['dateEvent'] . ' ' . $eventInfo['timeEvent'])) ?>
                        </p>
                        <p class="event-address">Centre Culturel Farha, Tanger</p>
                        <div class="ticket-details">
                            <p>Tarif: MAD <?= $ticket['typeBillet'] == 'Normal' ? $eventInfo['TariffNormal'] : $eventInfo['TariffReduit'] ?>,00</p>
                            <p>Type: Tarif <?= $ticket['typeBillet'] == 'Normal' ? 'Normal' : 'Réduit' ?></p>
                        </div>
                    </div>
                    <div class="ticket-right">
                        <div class="barcode">
                            <!-- Placeholder for barcode; in a real app, you'd generate a real barcode -->
                            <div class="barcode-lines"></div>
                        </div>
                        <div class="seat-info">
                            <div class="seat-detail">
                                <h3>SALLE</h3>
                                <h2><?= sprintf("%02d", $eventInfo['NumSalle']) ?></h2>
                            </div>
                            <div class="seat-detail">
                                <h3>PLACE</h3>
                                <h2><?= $ticket['placeNum'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <a href="profile.php" class="back-btn">Back to Profile</a>
    </main>
</body>
</html>