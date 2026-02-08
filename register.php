<?php

require_once 'repository/connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        $error = "Format d'email invalide";
    }
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $error = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial";
    }
    else {
        $requete = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $requete->execute([$username]);
        if ($requete->fetch()) {
            $error = "Ce nom d'utilisateur existe déjà";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $requete = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            
            if ($requete->execute([$username, $email, $hashedPassword])) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                header('Location: edit.phtml');
                exit;
            } else {
                $error = "Erreur lors de la création du compte";
            }
        }
    }
}
$layoutTitle = '3WA Tasks - Signup';
$template = 'register.phtml';
include 'layout.phtml';
?>
