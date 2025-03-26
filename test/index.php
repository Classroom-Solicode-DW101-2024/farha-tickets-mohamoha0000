<?php
require 'config.php';
if(isset($_POST['submit'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $hpassword=password_hash($password , PASSWORD_DEFAULT);
    $idUser=substr(uniqid(),-4);
    if(!empty($firstname) && !empty($lastname ) && !empty($email) && !empty($password) && !empty($confirm_password) && $password==$confirm_password && filter_var($email, FILTER_VALIDATE_EMAIL)){
        $check_email=$pdo->prepare("Select * from utilisateur where mailUser= :email");
        $check_email->bindParam( ':email',$email);
        $check_email->execute();
        if($check_email->rowCount()==0){
        $insert_client=$pdo->prepare("insert into utilisateur(idUser,nomUser,prenomUser,mailUser,motPasse) values(:idUser,:nomUser,:prenomUser,:mailUser,:motPasse)");
        $insert_client->bindParam(':idUser',$idUser) ;
        $insert_client->bindParam(':prenomUser',$firstname) ;
        $insert_client->bindParam(':nomUser',$lastname) ;
        $insert_client->bindParam(':mailUser',$email);
        $insert_client->bindParam(':motPasse',$hpassword);
        $insert_client->execute();
        }
    }
};



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="register.php" method="POST" class="auth-form">
        <div class="form-group">
            <label for="firstname">First Name</label>
            <input type="text" name="firstname" id="firstname" required>
        </div>
        <div class="form-group">
            <label for="lastname">Last Name</label>
            <input type="text" name="lastname" id="lastname" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>
        <button type="submit" name="submit">Register</button>
    </form>
</body>
</html>