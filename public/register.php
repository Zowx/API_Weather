<!-- /**
 * Page d'inscription utilisateur.
 * Cette page permet à un utilisateur de s'inscrire en fournissant un nom d'utilisateur et un mot de passe.
 * Si l'inscription échoue, un message d'erreur est affiché.
 * Si l'utilisateur a déjà un compte, il peut se connecter en cliquant sur le lien fourni.
 */ -->

<?php
include '../includes/header.php'; 
?>

<body class="background-image">
    <div class="container form-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Inscription</h2>

                <?php
                if (isset($_SESSION['register_error'])) {
                    echo '<p class="error" style="color: red; font-size: 20px;">' . $_SESSION['register_error'] . '</p>';
                    unset($_SESSION['register_error']);
                }
                ?>

                <form action="../process/process_register.php" method="post">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmer le mot de passe</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Inscription" class="btn btn-primary">
                    </div>
                </form>
                <p>Vous avez déjà un compte ? <a href="login.php">Connectez-vous</a>.</p>
            </div>
        </div>
    </div>
</body>
</html>