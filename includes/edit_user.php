<?php
require "bdd.php";

if(isset($_POST['submit'])) {

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $mail = $_POST['mail'];
    $city = $_POST['city'];
    $userId = $_POST['userId'];
    $old_password = $_POST['oldpassword'];
    $password = $_POST['password'];

    $data = ['firstname'=>$firstname, 'lastname'=>$lastname, 'mail'=>$mail, 'city'=>$city];
    if ($password != ''){
        $pw = password_hash($password, PASSWORD_BCRYPT);
    }
    else{
        $pw = '';
    }
    
    $conn = new MyDatabase();
    $execute = $conn->edit_user($data, $userId, $old_password, $pw);
}

?>