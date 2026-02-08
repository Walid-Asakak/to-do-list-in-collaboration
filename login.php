<?php
require_once 'repository/connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
    $requete->execute([$username]);
    $user = $requete->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location:');
        exit;
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect";
    }
}
$layoutTitle = '3WA Tasks - Login';
$template = 'login.phtml';
include 'layout.phtml';
?>
