<?php
require "config.php";
if (!isset($_SESSION['user'])) {
    header("location:login.php");
}

if (!isset($_GET['editionId'])) {
    header("location:index.php");
}

$editionId = $_GET['editionId'];
$sql = "SELECT e.*, ev.*, s.capSalle,
        (SELECT COUNT(*) FROM reservation r 
         JOIN billet b ON r.idReservation = b.idReservation 
         WHERE r.editionId = e.editionId) as tickets_sold
        FROM edition e
        JOIN evenement ev ON e.eventId = ev.eventId
        JOIN salle s ON e.NumSalle = s.NumSalle
        WHERE e.editionId = :editionId";
$stmt = $pdo->prepare($sql);
$stmt->execute([':editionId' => $editionId]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header("location:index.php");
}

$tickets_available = $event['capSalle'] - $event['tickets_sold'];

if (isset($_POST['submit'])) {
    $normalQty = (int)$_POST['normalQty'];
    $reducedQty = (int)$_POST['reducedQty'];
    $totalTickets = $normalQty + $reducedQty;
    
    if ($totalTickets <= $tickets_available && $totalTickets > 0) {
        // Create reservation
        $resaStmt = $pdo->prepare("INSERT INTO Reservation (qteBilletsNormal, qteBilletsReduit, editionId, idUser) 
            VALUES (:normal, :reduced, :editionId, :userId)");
        $resaStmt->execute([
            ':normal' => $normalQty,
            ':reduced' => $reducedQty,
            ':editionId' => $editionId,
            ':userId' => $_SESSION['user']['idUser']
        ]);
        
        $reservationId = $pdo->lastInsertId();
        
        // Assign seat numbers sequentially
        $lastSeat = $event['tickets_sold'];
        for ($i = 0; $i < $normalQty; $i++) {
            $seatNum = $lastSeat + $i + 1;
            $pdo->prepare("INSERT INTO Billet (billetId, typeBillet, placeNum, idReservation) 
                VALUES (CONCAT('B', :resaId, 'N', :seat), 'Normal', :seat, :resaId)")
                ->execute([':resaId' => $reservationId, ':seat' => $seatNum]);
        }
        for ($i = 0; $i < $reducedQty; $i++) {
            $seatNum = $lastSeat + $normalQty + $i + 1;
            $pdo->prepare("INSERT INTO Billet (billetId, typeBillet, placeNum, idReservation) 
                VALUES (CONCAT('B', :resaId, 'R', :seat), 'Reduit', :seat, :resaId)")
                ->execute([':resaId' => $reservationId, ':seat' => $seatNum]);
        }
        
        $message = "Purchase confirmed! Tickets reserved successfully.";
    } else {
        $error = "Not enough tickets available!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Event Details</title>
    <link rel="stylesheet" href="detail-style.css?v=1">
</head>
<body>
    <header>
        <a href="index.php"><h1>Event Details</h1></a>
    </header>
    
    <main class="event-details">
        <img src="<?= $event['image'] ?>" alt="<?= $event['eventTitle'] ?>">
        <h2><?= $event['eventTitle'] ?></h2>
        <p class="date"><?= $event['dateEvent'] . " " . $event['timeEvent'] ?></p>
        <p class="description"><?= $event['eventDescription'] ?></p>
        <p>Tickets available: <?= $tickets_available ?>/<?= $event['capSalle'] ?></p>
        
        <form method="POST" class="ticket-form">
            <div>
                <label>Normal Tickets (<?= $event['TariffNormal'] ?>€):</label>
                <input type="number" name="normalQty" min="0" max="<?= $tickets_available ?>" value="0">
            </div>
            <div>
                <label>Reduced Tickets (<?= $event['TariffReduit'] ?>€):</label>
                <input type="number" name="reducedQty" min="0" max="<?= $tickets_available ?>" value="0">
            </div>
            <button type="submit" name="submit">Validate</button>
        </form>
        
        <?php if (isset($message)): ?>
            <div class="success">
                <p><?= $message ?></p>
                <a href="profile.php">View Tickets & Invoice</a>
            </div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
    </main>
</body>
</html>