<?php 

if(!isset($_SESSION['id']) || !isset($_SESSION['numeroSession']) || $_SESSION['numeroSession']!=session_id()) {

    header("Location: accueil.php");
    exit();
}
?>