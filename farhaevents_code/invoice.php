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

// Get invoice details
$sql = "SELECT r.*, e.dateEvent, ev.eventTitle, ev.TariffNormal, ev.TariffReduit,
        u.nomUser, u.prenomUser, u.mailUser,
        (r.qteBilletsNormal * ev.TariffNormal + r.qteBilletsReduit * ev.TariffReduit) as total
        FROM Reservation r
        JOIN Edition e ON r.editionId = e.editionId
        JOIN Evenement ev ON e.eventId = ev.eventId
        JOIN Utilisateur u ON r.idUser = u.idUser
        WHERE r.idReservation = :resaId AND r.idUser = :userId";
$stmt = $pdo->prepare($sql);
$stmt->execute([':resaId' => $resaId, ':userId' => $userId]);
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$invoice) {
    header("location:profile.php");
}

$purchaseDate = date('Y-m-d'); // You might want to add a purchase date field to Reservation table
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= $resaId ?></title>
    <link rel="stylesheet" href="invoice-style.css?v=1">
</head>
<body>
    <header>
        <a href="profile.php"><h1>Invoice</h1></a>
    </header>
    
    <main class="invoice">
        <div class="invoice-header">
            <h2>Invoice #<?= $resaId ?></h2>
            <p>Date: <?= $purchaseDate ?></p>
        </div>
        
        <div class="user-info">
            <h3>Customer Information</h3>
            <p><?= $invoice['prenomUser'] . " " . $invoice['nomUser'] ?></p>
            <p><?= $invoice['mailUser'] ?></p>
        </div>
        
        <div class="event-info">
            <h3>Event Details</h3>
            <p>Event: <?= $invoice['eventTitle'] ?></p>
            <p>Date: <?= $invoice['dateEvent'] ?></p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($invoice['qteBilletsNormal'] > 0): ?>
                <tr>
                    <td>Normal Ticket</td>
                    <td><?= $invoice['qteBilletsNormal'] ?></td>
                    <td><?= $invoice['TariffNormal'] ?>€</td>
                    <td><?= $invoice['qteBilletsNormal'] * $invoice['TariffNormal'] ?>€</td>
                </tr>
                <?php endif; ?>
                <?php if ($invoice['qteBilletsReduit'] > 0): ?>
                <tr>
                    <td>Reduced Ticket</td>
                    <td><?= $invoice['qteBilletsReduit'] ?></td>
                    <td><?= $invoice['TariffReduit'] ?>€</td>
                    <td><?= $invoice['qteBilletsReduit'] * $invoice['TariffReduit'] ?>€</td>
                </tr>
                <?php endif; ?>
                <tr class="total">
                    <td colspan="3">Total</td>
                    <td><?= $invoice['total'] ?>€</td>
                </tr>
            </tbody>
        </table>
        
        <a href="profile.php" class="back-btn">Back to Profile</a>
    </main>
</body>
</html>