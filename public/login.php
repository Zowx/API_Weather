<!-- /**
 * Fichier de connexion
 * 
 * Ce fichier permet à l'utilisateur de se connecter à l'application.
 * Il contient un formulaire de connexion qui envoie les données à ../process/process_login.php
 * Si l'utilisateur a déjà essayé de se connecter sans succès, un délai est imposé avant de pouvoir réessayer.
 * Si une erreur est survenue lors de la tentative de connexion précédente, un message d'erreur est affiché.
 * Si l'utilisateur n'a pas encore de compte, il peut s'inscrire en cliquant sur le lien fourni.
 */ -->

<?php
include '../includes/header.php'; 
?>

<body class="background-image">
    <div class="container form-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Connexion</h2>
                
                <?php
                if (isset($_COOKIE['error'])) {
                    echo '<p class="error" style="color: red; font-size: 20px;">' . $_COOKIE['error'] . '</p>';
                }
                ?>
                <form action="../process/process_login.php" method="post">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <p id="countdown"></p>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" id="submit" value="Connexion" class="btn btn-primary">
                    </div>
                </form>
                <p>Vous n'avez pas de compte ? <a href="register.php">Inscrivez-vous</a>.</p>
            </div>
        </div>
    </div>
<script>
    var loginAttempts = <?php echo isset($_SESSION["login_attempts"]) ? $_SESSION["login_attempts"] : 0; ?>;
    var lastAttemptTime = <?php echo isset($_SESSION["last_attempt_time"]) ? $_SESSION["last_attempt_time"] : 0; ?>;
</script>
<script src="js/loginDelay.js"></script>
</body>
</html>