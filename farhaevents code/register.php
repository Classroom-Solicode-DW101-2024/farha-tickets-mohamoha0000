<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    try {
        // Vérifier si l'email existe déjà
        $sql = "SELECT COUNT(*) FROM Utilisateur WHERE mailUser = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Cet email est déjà utilisé";
        }
        
        
        if (!isset($error)) {
            // Générer un nouvel ID
            $newId = getLastIdClient()+1;
            
            // Insérer le nouvel utilisateur
            $sql = "INSERT INTO Utilisateur (idUser, nomUser, prenomUser, mailUser, motPasse) 
                    VALUES (:id, :nom, :prenom, :email, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id' => $newId,
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':password' => $password
            ]);
            
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        $error = "Erreur: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f8e9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        form {
            background-color: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        h2 {
            text-align: center;
            color: #1b5e20;
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        div {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            color: #2e7d32;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #c8e6c9;
            border-radius: 6px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #66bb6a;
            box-shadow: 0 0 5px rgba(102, 187, 106, 0.3);
        }

        button {
            margin-bottom:0.5em;
            width: 100%;
            padding: 0.9rem;
            background-color: #66bb6a;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: #4caf50;
        }

        p[style="color:red"] {
            background-color: #ffebee;
            padding: 0.6rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Inscription</h2>
        <div>
            <label>Nom:</label>
            <input type="text" name="nom" required>
        </div>
        <div>
            <label>Prénom:</label>
            <input type="text" name="prenom" required>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Mot de passe:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">S'inscrire</button>
        <a href="login.php"><button style="width:30%" type="button">Se connecter</button></a> 
        <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    </form>
</body>
</html>