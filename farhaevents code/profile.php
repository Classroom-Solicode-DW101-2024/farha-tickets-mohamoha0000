<?php
require "config.php";
if (!isset($_SESSION['user'])) {
    header("location:login.php");
}

$userId = $_SESSION['user']['idUser'];

// Update user info
if (isset($_POST['update'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    
    $stmt = $pdo->prepare("UPDATE Utilisateur SET nomUser = :nom, prenomUser = :prenom, mailUser = :email WHERE idUser = :id");
    $stmt->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':email' => $email,
        ':id' => $userId
    ]);
    $_SESSION['user']['nomUser'] = $nom;
    $_SESSION['user']['prenomUser'] = $prenom;
    $_SESSION['user']['mailUser'] = $email;
}

// Get reservations
$resaStmt = $pdo->prepare("SELECT r.*, e.dateEvent, ev.eventTitle,
    (r.qteBilletsNormal * ev.TariffNormal + r.qteBilletsReduit * ev.TariffReduit) as total
    FROM Reservation r
    JOIN Edition e ON r.editionId = e.editionId
    JOIN Evenement ev ON e.eventId = ev.eventId
    WHERE r.idUser = :userId
    ORDER BY r.idReservation DESC");
$resaStmt->execute([':userId' => $userId]);
$reservations = $resaStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile-style.css?v=1">
</head>
<body>
    <header>
        <a href="index.php"><h1>User Profile</h1></a>
    </header>
    
    <main class="profile">
        <section class="user-info">
            <h2>Personal Information</h2>
            <form method="POST">
                <div>
                    <label>Last Name:</label>
                    <input type="text" name="nom" value="<?= $_SESSION['user']['nomUser'] ?>" required>
                </div>
                <div>
                    <label>First Name:</label>
                    <input type="text" name="prenom" value="<?= $_SESSION['user']['prenomUser'] ?>" required>
                </div>
                <div>
                    <label>Email:</label>
                    <input type="email" name="email" value="<?= $_SESSION['user']['mailUser'] ?>" required>
                </div>
                <button type="submit" name="update">Update</button>
            </form>
        </section>
        
        <section class="purchases">
            <h2>Purchase History</h2>
            <table>
                <thead>
                    <tr>
                        <th>Invoice Ref</th>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $resa): ?>
                        <tr>
                            <td>#<?= $resa['idReservation'] ?></td>
                            <td><?= $resa['eventTitle'] ?></td>
                            <td><?= $resa['dateEvent'] ?></td>
                            <td><?= $resa['total'] ?>€</td>
                            <td>
                                <a href="tickets.php?resaId=<?= $resa['idReservation'] ?>">View Tickets</a>
                                <a href="invoice.php?resaId=<?= $resa['idReservation'] ?>">View Invoice</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>