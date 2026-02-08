<?php

session_start();

include('repository/userRepository.php');

$error = '';

// If the user is already connected -> go on user page 
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header("Location: userAccount.php");
    exit();
}


if (!empty($_POST)) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $user = getUserByEmail($email);
    
    // Check if user password is correct & if user  exists (adress email)
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['loggedIn'] = true;
        $_SESSION['user'] = $user;
        header("Location: userAccount.php");
        exit();
    } 
    
    else {
            $error = 'Email or password is incorrect.';
    }
}

$layoutTitle = '3WA Tasks - Login';
$template = 'login.phtml';
include 'layout.phtml';