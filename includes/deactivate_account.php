<?php
    require 'bdd.php';
    $userId = $_GET['id'];

    session_start();
    session_destroy();
    session_unset();

    $conn = new MyDatabase();
    $reactivate = $conn->deactivate_user($userId);
?>