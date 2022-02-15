<?php
require 'bdd.php';
session_start();

if(isset($_POST['submit'])) {

    $gender = $_POST['gender'];
    $city = $_POST['city'];
    $hobbiesArr = $_POST['hobbies'];
    $age = $_POST['age'];
    $ids = $_POST['id'];
    $userId = "AND user.active = 1";

    if(str_contains($city, ' ')){
        $cities = explode(" ", $city);
        $tempCity = implode("%' OR city LIKE '%", $cities);
        $city = "AND city LIKE '%".$tempCity."%'";
    }
    else{
        $tempCity = $city;
        $city = "AND city LIKE '%" .$tempCity. "%'";
    }

    if($ids == null){
        $id = "AND user.id NOT IN (0)";
    }
    elseif(is_string($ids)){
        $id = "AND user.id = $ids";
    }
    elseif(count($ids)>1){
        $idsVal = implode(', ', $ids);
        $id = "AND user.id NOT IN (" .$idsVal. ")";
    }
    else{
        $idsVal = implode('', $ids);
        $id = "AND user.id NOT IN (" .$idsVal. ")";
    }

    if($_SESSION['currentuser'] != ""){
        $userId = "AND user.active = 1 AND user.id !=" .$_SESSION['currentuser'];
    }

    if(str_contains($age, '/')){
        $ageAfter = strtok($age, '/');
        $ageAft = date('Y-m-d', strtotime($ageAfter . ' years ago'));
        $ageBefore = substr($age, strpos($age, "/") + 1);
        $ageBef = date('Y-m-d', strtotime($ageBefore . ' years ago'));
    }
    elseif(str_contains($age, '+')){
        $ageAfter = strtok($age, '+');
        $ageAft = date('Y-m-d', strtotime($ageAfter . ' years ago'));
        $ageBefore = 100;
        $ageBef = date('Y-m-d', strtotime($ageBefore . ' years ago'));
    }
    else{
        $ageAfter = 18;
        $ageAft = date('Y-m-d', strtotime($ageAfter . ' years ago'));
        $ageBefore = 100;
        $ageBef = date('Y-m-d', strtotime($ageBefore . ' years ago'));
    }

    if($hobbiesArr == null){
        $hobbies = "";
    }
    elseif(count($hobbiesArr)>1){
        $hobbiesVal = implode(', ', $hobbiesArr);
        $hobbies = "AND hobbies.id IN (" .$hobbiesVal. ")";
    }
    else{
        $hobbiesVal = implode('', $hobbiesArr);
        $hobbies = "AND hobbies.id IN (" .$hobbiesVal. ")";
    }

    $data = ['gender'=>$gender, 'city'=>$city, 'hobbies'=>$hobbies, 'ageBef'=>$ageBef, 'ageAft'=>$ageAft, 'id'=>$id, 'userid'=>$userId];

    $conn = new MyDatabase();
    $execute = $conn->get_members($data);
}
?>