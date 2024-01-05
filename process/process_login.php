<!-- /**
 * FILEPATH: /Users/enzomorin/Documents/Cours/Bachelor Dev WEB/API/weather/process/process_login.php
 * 
 * Ce script traite les données du formulaire de connexion soumises par l'utilisateur.
 * Il vérifie les informations d'identification de l'utilisateur et définit les variables de session si la connexion est réussie.
 * Si la connexion échoue, il incrémente le compteur de tentatives de connexion et bloque l'utilisateur si le nombre de tentatives dépasse un certain seuil.
 * Il définit également des messages d'erreur et redirige l'utilisateur vers la page de connexion si nécessaire.
 */ -->

<?php
session_start();
ob_start();

require_once "../includes/db_connect.php";

$username = $password = "";
$username_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Vérifier si le nom d'utilisateur est vide
    if(empty(trim($_POST["username"]))){
        $username_err = "Veuillez entrer votre nom d'utilisateur.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Vérifier si le mot de passe est vide
    if(empty(trim($_POST["password"]))){
        $password_err = "Veuillez entrer votre mot de passe.";
        $_SESSION['error'] = $password_err;
        header('Location: ../public/login.php');
        exit();
    } else{
        $password = trim($_POST["password"]);
    }

    // Vérifier les erreurs de saisie avant de les insérer dans la base de données
    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id, username, hashed_password FROM users WHERE username = :username";

        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            
            $param_username = $username;

            if($stmt->execute()){
                if($stmt->rowCount() == 1){                    
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $hashed_password = $row["hashed_password"];

                        // Vérifier si le mot de passe correspond
                        if(password_verify($password, $hashed_password)){                            
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;  

                            // Récupérer l'utilisateur de la base de données
                            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
                            $stmt->bindParam(1, $username);
                            $stmt->execute();
                            $user = $stmt->fetch();
                            
                            // Générer un jeton d'accès unique seulement si l'utilisateur n'en a pas déjà un
                            if (empty($user['token'])) {
                                $token = bin2hex(random_bytes(16));

                                // Stocker le jeton d'accès dans la base de données
                                $stmt = $pdo->prepare('UPDATE users SET token = ? WHERE username = ?');
                                $stmt->bindParam(1, $token);
                                $stmt->bindParam(2, $username);
                                $stmt->execute();
                            } else {
                                $token = $user['token'];
                            }

                            // Stocker le jeton d'accès dans un cookie sécurisé
                            setcookie("token", $token, time() + (86400 * 30), "/", "localhost", true, true);
                            
                            // Réinitialiser le compteur de tentatives de connexion et l'heure de la dernière tentative
                            $_SESSION["login_attempts"] = 0;
                            $_SESSION["last_attempt_time"] = null;

                            // Réinitialiser le message d'erreur
                            if(isset($_COOKIE['error'])) {
                                setcookie("error", "", time() - 120, "/");
                            }

                            header("location: ../public/weather.php");
                            exit;
                        } else{
                            // Incrementer le nombre de tentatives de connexion échouées
                            $_SESSION["login_attempts"] = isset($_SESSION["login_attempts"]) ? $_SESSION["login_attempts"] + 1 : 1;

                            // Enregistrer l'heure de la dernière tentative
                            $_SESSION["last_attempt_time"] = time();

                            // Bloquer la connexion si le nombre de tentatives dépasse 5 ou 10
                            if($_SESSION["login_attempts"] > 5 && $_SESSION["login_attempts"] <= 10) {
                                if(time() - $_SESSION["last_attempt_time"] < 30) {
                                    setcookie("error", "Vous avez dépassé le nombre maximum de tentatives de connexion. Veuillez attendre 30 secondes avant de réessayer.", time() + 30, "/");
                                    header('Location: ../public/login.php');
                                    exit();
                                }
                            } else if($_SESSION["login_attempts"] > 10) {
                                if(time() - $_SESSION["last_attempt_time"] < 120) {
                                    setcookie("error", "Vous avez dépassé le nombre maximum de tentatives de connexion. Veuillez attendre 2 minutes avant de réessayer.", time() + 120, "/");
                                    header('Location: ../public/login.php');
                                    exit();
                                }
                            } 
                            
                            // Afficher un message d'erreur si le mot de passe ne correspond pas
                            setcookie("error", "Le mot de passe que vous avez entré n'était pas valide.", time() + 30, "/");
                            header('Location: ../public/login.php');
                            exit();
                        }
                    }
                }
            }
        }
    }
}