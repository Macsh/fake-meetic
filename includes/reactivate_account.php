<?php
    require 'bdd.php';
    $userId = $_GET['id'];

    session_start();
    $_SESSION['currentuser'] = $userId;

    $conn = new MyDatabase();
    $deactivate = $conn->reactivate_user($userId);
?>