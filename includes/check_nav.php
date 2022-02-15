<?php
session_start();

if($_SESSION['currentuser'] != ""){
    echo $_SESSION['currentuser'];
}
?>