<?php
session_start();

include 'repository/userRepository.php';

$error = '';

if (!empty($_POST)) {

    $db = connectToDataBase();

    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Regex to validate the email and password
    $regexEmail = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    $regexPassword = '/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{12,}$/';

    if (!preg_match($regexEmail, $email)) {
        $error = "Your email address is invalid.";
    }
    elseif (!preg_match($regexPassword, $password)) {
        $error = "Your password must contain at least 12 characters, an uppercase letter, a number, and a special character.";
    }
    elseif (getUserbyEmail($email)) {
        $error = "This email is already used.";
    } 
    else {
        $passwordHached = password_hash($password, PASSWORD_DEFAULT);

        $user = [
            'username' => $username,
            'email' => $email
        ];

        $userId = insertUser($user, $passwordHached);

        if ($userId) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;

            // Redirect to login page
            header('Location: userAccount.php');
            exit();
        } 
        else {
            $error = 'An error occurred while creating your account. Please try again.';
        }
    }
}

// Integration of the layout and template:
$layoutTitle = '3WA Tasks - Register';
$template = 'register.phtml';
include 'layout.phtml';
