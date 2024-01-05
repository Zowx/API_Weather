<!-- /**
 * Ce fichier est le point d'entrée de l'application.
 * Il démarre une session et inclut les fichiers nécessaires en fonction de l'état d'authentification de l'utilisateur.
 * Si l'utilisateur est authentifié, il inclut le fichier weather.php.
 * Si l'utilisateur n'est pas authentifié, il inclut le fichier login.php.
 */ -->

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../includes/db_connect.php';

if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    include 'weather.php';
} else {
    include 'login.php';
}
?>