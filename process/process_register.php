<!-- /**
 * Ce script permet de traiter les données envoyées par le formulaire d'inscription et de les insérer dans la base de données.
 * Si l'inscription réussit, l'utilisateur est redirigé vers la page d'accueil.
 * Si l'inscription échoue, l'utilisateur est redirigé vers la page d'inscription avec un message d'erreur.
 */ -->
<?php
session_start();
ob_start();

require_once "../includes/db_connect.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Vérifier si le nom d'utilisateur est vide
    if(empty(trim($_POST["username"]))){
        $username_err = "Veuillez entrer un nom d'utilisateur.";
    } else{
        $sql = "SELECT id FROM users WHERE username = :username";

        // Vérifier si le nom d'utilisateur est déjà pris
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            
            $param_username = trim($_POST["username"]);

            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "Ce nom d'utilisateur est déjà pris.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Quelque chose s'est mal passé. Veuillez réessayer plus tard.";
            }
        }
    }

    // Vérifier si le mot de passe est vide
    if(empty(trim($_POST["password"]))){
        $password_err = "Veuillez entrer un mot de passe.";  
    // Vérifier si le mot de passe a au moins 6 caractères   
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Le mot de passe doit comporter au moins 6 caractères.";
    // Si tout va bien, on stocke le mot de passe dans la variable $password
    } else{
        $password = trim($_POST["password"]);
    }

    // Vérifier si le champ de confirmation du mot de passe est vide
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Veuillez confirmer le mot de passe.";
    // Vérifier si le mot de passe et sa confirmation correspondent
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Le mot de passe ne correspond pas.";
        }
    }
    // Vérifier les erreurs de saisie avant de les insérer dans la base de données
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        $sql = "INSERT INTO users (username, hashed_password) VALUES (:username, :password)";
        
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $param_password, PDO::PARAM_STR);
            
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Crée un hash de mot de passe
            
            if($stmt->execute()){
                // Récupérer l'id et l'username de l'utilisateur inscrit
                $id = $pdo->lastInsertId();
                $username = $_POST['username'];

                // Définir les variables de session
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;

                
                header("location: weather.php");
                exit;
            } else{
                echo "Quelque chose s'est mal passé. Veuillez réessayer plus tard.";
            }
        }
    } else{ // Si l'inscription échoue, on redirige l'utilisateur vers la page d'inscription avec un message d'erreur
        $_SESSION['register_error'] = 'Il y a eu des erreurs lors de votre inscription. Veuillez réessayer.';
        header('Location: register.php');
        exit();
    }
}
ob_end_flush();
?>