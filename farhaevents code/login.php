<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    try {
        $sql = "SELECT * FROM Utilisateur WHERE mailUser = :email AND motPasse = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':password' => $password
        ]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $_SESSION['user'] = $user;
            header("Location: index.php"); // Redirect to a welcome page
            exit();
        } else {
            $error = "Email ou mot de passe incorrect";
        }
    } catch (PDOException $e) {
        $error = "Erreur: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        form {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 350px;
        }

        h2 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }

        div {
            margin-bottom: 1.2rem;
        }

        label {
            display: block;
            color: #388e3c;
            font-weight: 600;
            margin-bottom: 0.4rem;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.7rem;
            border: 1px solid #c8e6c9;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #4caf50;
            box-shadow: 0 0 4px rgba(76, 175, 80, 0.3);
        }

        button {
            margin-bottom:0.5em;
            width: 100%;
            padding: 0.8rem;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: #388e3c;
        }

        p[style="color:red"] {
            background-color: #f8d7da;
            padding: 0.5rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Connexion</h2>
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Mot de passe:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Se connecter</button>
        <a href="register.php"><button style="width:30%" type="button">S'inscrire</button></a> 
        <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    </form>
</body>
</html>