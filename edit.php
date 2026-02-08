<?php
require_once 'repository/connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';
$task = null;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$task_id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $_SESSION['user_id']]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$task || $task === false) {
        $error = "Tâche introuvable ou accès non autorisé";
        include 'edit.phtml';
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $urgent = isset($_POST['urgent']) ? 1 : 0;
        $important = isset($_POST['important']) ? 1 : 0;
        
        if (empty($title)) {
            $error = "Le titre est obligatoire";
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, urgent = ?, important = ? WHERE id = ? AND user_id = ?");
                
                if ($stmt->execute([$title, $description, $urgent, $important, $task_id, $_SESSION['user_id']])) {
                    $success = "Tâche modifiée avec succès";
                    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
                    $stmt->execute([$task_id, $_SESSION['user_id']]);
                    $task = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $error = "Erreur lors de la modification de la tâche";
                }
            } catch (PDOException $e) {
                $error = "Erreur lors de la modification de la tâche";
            }
        }
    }
} catch (PDOException $e) {
    header('Location: user.php');
    exit;
}

$layoutTitle = '3WA Tasks - Edit';
$template = 'edit.phtml';
include 'layout.phtml';
?>
