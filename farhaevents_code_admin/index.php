<?php 
require "config.php";

$qury="select ev.eventTitle ,ed.dateEvent , ed.editionId , s.capSalle,
  (select count(*) from billet b inner join reservation r
  on b.idReservation= r.idReservation where r.editionId = ed.editionId) as tickets_sold
  from evenement ev inner join edition ed on ev.eventId =ed.eventId
  inner JOIN salle s ON ed.NumSalle = s.NumSalle
  ";


  $stmt=$pdo->prepare($qury);
  $stmt->execute();
  $result=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #e8f5e9; 
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            color: #2e7d32; 
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #2e7d32;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #4caf50; 
            color: white;
        }

        tr {
            background-color: #f1f8f5;
        }

        tr:hover {
            background-color: #c8e6c9;
        }

        a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        button {
            background-color: #2e7d32; 
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1b5e20;
        }
    </style>
</head>
<body>
    <header>
        <h1>Dashborade Admin</h1>
    </header>
    <main>
        <div class="tablue">
            <table>
                <tr>
                    <th>Edition titre</th>
                    <th>date</th>
                    <th>statut</th>
                    <th>nombre ticket</th>
                    <th>action</th>
                </tr>
                <?php foreach($result as $row):?>
                <tr>
                    <td><?= $row['eventTitle']?></td>
                    <td><?= $row['dateEvent']?></td>
                    <td><?= ($row['dateEvent']>date("Y-m-d"))? "en cours":"termine" ?></td>
                    <td><?=$row['tickets_sold']."/".$row['capSalle']?></td>
                    <td><button> <a href="../farhaevents_code/detail.php?editionId=<?= $row['editionId'] ?>" target="_blank">detaile</a> </button></td>
                <tr>
                <?php endforeach; ?>
            </table>
        </div>
    </main>
</body>
</html>