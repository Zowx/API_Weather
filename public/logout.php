<!-- /**
 * Démarre une session et détruit toutes les données de session.
 * Redirige ensuite vers la page de connexion.
 */ -->

<?php
session_start();
 
$_SESSION = array();
 
session_destroy();
 
header("location: login.php");
exit;
?>