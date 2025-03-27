<?php
require 'config.php'; 

$erreurs=[];
if(isset($_POST["connecter"])){
    $mail =$_POST["mail"];
    $motPasse =$_POST["motPasse"];
    if(empty($mail)){
        $erreurs['email'] = "Veuillez saisir votre adresse mail";
    }
     if(empty($motPasse)){
        $erreurs['motPasse'] = "Veuillez saisir votre mot de passe";
    }
    if(empty($erreurs)){
        $req = $pdo->prepare("SELECT * FROM utilisateur WHERE mailUser = ? AND motPasse = ?");
        $req->execute([$mail, $motPasse]);
        $resultat =$req->fetchAll(PDO::FETCH_ASSOC);
    if(count($resultat)>0){
        header("Location:home.php");
    }else{
       $erreurs['userExiste'] = "Email ou  mot de passe incorrect";
    }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if(!empty($erreurs)):
         foreach($erreurs as $erreur=>$ere):?>
           <p><?=$ere?></p>      
     <?php endforeach; 
      endif; ?>
<form method="POST" action="">
        <div class="form-group">
            <label for="mail">Email :</label>
            <input type="email" id="mail" name="mail" value="" >
        </div>
        <div class="form-group">
            <label for="motPasse">Mot de passe :</label>
            <input type="password" id="motPasse" name="motPasse" >
        </div>
        <button type="submit" name="connecter">Se connecter</button>
    </form>
    <div class="register-link">
        <p>Pas de compte ? <a href="register.php">Inscrivez-vous</a></p>
    </div>
    
</body>
</html>