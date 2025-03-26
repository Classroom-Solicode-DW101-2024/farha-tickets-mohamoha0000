<?php 
require "config.php";
if (!isset($_SESSION['user'])){
    header("location:login.php");
}
$categories=GetEventType();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$date_start = isset($_GET['date_start']) ? $_GET['date_start'] : '';
$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$sql = "SELECT e.*, ev.*,
        s.capSalle, 
        (SELECT COUNT(*) FROM reservation r 
         JOIN billet b ON r.idReservation = b.idReservation 
         WHERE r.editionId = e.editionId) as tickets_sold
        FROM edition e
        JOIN evenement ev ON e.eventId = ev.eventId
        JOIN salle s ON e.NumSalle = s.NumSalle
        WHERE e.dateEvent >= CURDATE()";

if(isset($_GET["reset"])){
    header("location:index.php");
}
$params = [];
if ($search) {
    $sql .= " AND ev.eventTitle LIKE :search";
    $params[':search'] = "%$search%";
}

if ($date_start && $date_end) {
    $sql .= " AND e.dateEvent BETWEEN :date_start AND :date_end";
    $params[':date_start'] = $date_start;
    $params[':date_end'] = $date_end;
}

if ($category) {
    $sql .= " AND ev.eventType = :category";
    $params[':category'] = $category;
}

$sql .= " ORDER BY e.dateEvent ASC";

$stmt=$pdo->prepare($sql);
$stmt->execute($params);
$result=$stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css?v=0">
</head>
<body>
    <header>
        <a href="index.php"><h1>Événements à venir</h1></a>
        <div>
            <a href="profile.php"><h3 style="color:white;"><?=$_SESSION['user']['prenomUser']." ".$_SESSION['user']['nomUser']?></h3></a> 
            <a href="profile.php"><img src="img/user.png" alt="userinfo"></a>
        </div>
    </header>
    <div class="search-filter">
        <form method="GET">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Rechercher par titre">
            
            <input type="date" name="date_start" value="<?php echo htmlspecialchars($date_start); ?>">
            <input type="date" name="date_end" value="<?php echo htmlspecialchars($date_end); ?>">
            
            <select name="category">
                <option value="">Toutes catégories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat['eventType']);  ?>"
                    <?php echo $category === $cat['eventType'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['eventType']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit">Filtrer</button>
            <button name="reset" type="submit">rest</button>
        </form>
        </div>
    <main>
        <?php foreach ($result as $value):?>
            <div class="event">
                <img src="<?= $value['image']; ?>" alt="">
                <h2><?= $value['eventTitle']; ?></h2>
                <p><?= $value['dateEvent'].$value['timeEvent']; ?></p>
                <p><?= $value['eventType'] ?></p>
                <p><?= $value['TariffNormal'].$value['TriffReduit']; ?></p>
                <?php
                 $available = $value['capSalle'] - $value['tickets_sold'];
                 if ($available > 0):
                 ?>
                 <a href="detail.php?editionId=<?= $value['editionId'] ?>"><button>J'achète</button></a>
                <?php else: ?>
                   <button disabled style="background-color: red;">Guichet fermé</button>
                <?php endif; ?>
            </div>
        <?php endforeach;?>
    </main>
    <footer class="cinema-footer">
        <div class="footer-content">
            <h2>Cinéma Événement</h2>
            <p>Profitez des meilleures projections et événements exclusifs.</p>
            <p>Contact : <a href="tel:+212600000000">+212 600 000 000</a></p>
            <p>&copy; 2025 Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>