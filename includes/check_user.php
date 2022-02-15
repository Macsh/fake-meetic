<?php

require 'bdd.php';
session_start();


if(isset($_POST['submit'])) {
    $mail = $_POST['mail'];
    $pw = $_POST['password'];


    $conn = new MyDatabase();
    $checkuser = $conn->do_user_exists($mail, $pw);
}

?>