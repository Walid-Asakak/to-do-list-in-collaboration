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
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Ce nom d'utilisateur existe déjà";
        } 
        else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Cet email est déjà utilisé";
            } 
            else {
                try {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                    
                    if ($stmt->execute([$username, $email, $hashedPassword])) {
                        $_SESSION['user_id'] = $pdo->lastInsertId();
                        $_SESSION['username'] = $username;
                        header('Location: user.php');
                        exit;
                    } else {
                        $error = "Erreur lors de la création du compte";
                    }
                } catch (PDOException $e) {
                    $error = "Erreur lors de la création du compte : " . $e->getMessage();
                }
            }
        }
    }
}

$layoutTitle = '3WA Tasks - Register';
$template = 'register.phtml';
include 'layout.phtml';
?>
