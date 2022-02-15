<?php
require "bdd.php";

if(isset($_POST['submit'])) {

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $birthdate = $_POST['birthdate'];
    $mail = $_POST['mail'];
    $gender = $_POST['gender'];
    $hobbies = $_POST['hobbies'];
    $city = $_POST['city'];
    $password = $_POST['password'];
    $passwordconfirm = $_POST['passwordconfirm'];

    $now = date("Y-m-d");
    $diff = date_diff(date_create($birthdate), date_create($now));
    $age = $diff->format('%y');

    if($firstname == ""){
        echo "Vous n'avez pas renseigné votre prénom";
    }
    elseif($lastname == ""){
        echo "Vous n'avez pas renseigné votre nom";
    }
    elseif($birthdate == ""){
        echo "Vous n'avez pas renseigné votre date de naissance";
    }
    elseif($mail == ""){
        echo "Vous n'avez pas renseigné votre mail";
    }
    elseif($gender == ""){
        echo "Vous n'avez pas renseigné votre genre";
    }
    elseif($hobbies == ""){
        echo "Renseignez au moins un hobby";
    }
    elseif($city == ""){
        echo "Vous n'avez pas renseigné votre ville";
    }
    elseif($password == ""){
        echo "Vous n'avez pas renseigné votre mot de passe";
    }
    elseif($passwordconfirm == ""){
        echo "Veuillez confirmer votre mot de passe";
    }
    elseif($age < 18){
        echo "Vous n'avez pas l'age requis";
    }
    elseif($password != $passwordconfirm){
        echo "Les mots de passe ne correspondent pas";
    }
    else {
        $data = ['firstname'=>$firstname, 'lastname'=>$lastname, 'birthdate'=>$birthdate, 'mail'=>$mail, 'gender'=>$gender, 'city'=>$city];
        $pw = password_hash($password, PASSWORD_BCRYPT);
        
        $conn = new MyDatabase();
        $execute = $conn->add_user_to_db($data, $hobbies, $pw);
    }
}
?>