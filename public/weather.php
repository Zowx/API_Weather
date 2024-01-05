<!-- /**
 * Fichier de la page d'affichage de la météo.
 * 
 * Ce fichier contient la page d'affichage de la météo pour une ville sélectionnée.
 * Il inclut les fichiers session_manager.php, header.php et footer.php.
 * 
 * La page contient un formulaire permettant de sélectionner une ville parmi une liste déroulante.
 * Une fois une ville sélectionnée, les informations météorologiques pour cette ville sont affichées.
 * Les informations affichées sont la température, la description et la vitesse du vent.
 * 
 * La page contient également une carte affichant la position de la ville sélectionnée.
 * 
 * Enfin, la page affiche l'heure actuelle et un bouton de déconnexion.
 */ -->

<?php
include '../includes/session_manager.php';
include '../includes/header.php';
?>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <label for="villeSelect">Sélectionnez une ville :</label>
                <select class="form-control" id="villeSelect">
                    <option value="">Sélectionnez une ville</option>
                    <option value="paris">Paris</option>
                    <option value="lyon">Lyon</option> 
                    <option value="marseille">Marseille</option>
                    <option value="montpellier">Montpellier</option>
                </select>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6 mx-auto">
                <div id="weatherInfo" class="d-none">
                    <div class="weather-info" id="temperatureInfo">
                        <i class="fas fa-thermometer-half"></i> Température :
                    </div>
                    <div class="weather-info" id="descriptionInfo">
                        Description :
                    </div>
                    <div class="weather-info" id="windInfo">
                        <i class="fas fa-wind"></i> Vitesse du vent :
                    </div>
                </div>
                
                <div id="map"></div>
            </div>
        </div>
    </div>

    <div class="current-time" id="currentHour"></div>

    <a href="logout.php" class="btn btn-primary button-deconnexion">Déconnexion</a>

    <div class="token-info">
        Votre jeton d'accès : <?php echo $_COOKIE['token']; ?>
    </div>

<?php include '../includes/footer.php'; ?>
