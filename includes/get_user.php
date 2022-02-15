<?php
require 'bdd.php';
session_start();

$userId = $_GET['id'];
$conn = new MyDatabase();
$checkuser = $conn->get_user_data($userId);
?>