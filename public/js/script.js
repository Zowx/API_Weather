var map;

    function initMap(latitude, longitude) {
        if (map) {
            map.remove(); // Supprime la carte précédente
        }

        map = L.map('map').setView([latitude, longitude], 12); // Initialise la carte
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);
        L.marker([latitude, longitude]).addTo(map); // Ajoute un marqueur à la carte
    }

    // Événement déclenché lorsqu'une ville est sélectionnée dans la liste
    $('#villeSelect').on('change', function() {
        var selectedVille = $(this).val();
        if (!selectedVille) return; // Si aucune ville sélectionnée, ne rien faire

        // Réinitialiser les informations de la météo
        $('#coordinates').addClass('d-none').empty();
        $('#temperatureInfo').empty();
        $('#descriptionInfo').empty();
        $('#weatherIcon').empty();
        $('#windInfo').empty();
        $('#weatherInfo').addClass('d-none');

        var apiUrl = 'https://api-adresse.data.gouv.fr/search/?q=' + selectedVille; // URL pour la recherche de coordonnées de la ville

        $.ajax({
            url: apiUrl,
            method: 'GET',
            success: function(data) {
                var latitude = data.features[0].geometry.coordinates[1];
                var longitude = data.features[0].geometry.coordinates[0];
                $('#coordinates').text('Latitude : ' + latitude + ', Longitude : ' + longitude);
                
                var weatherApiUrl = 'https://api.openweathermap.org/data/2.5/weather?lat=' + latitude + '&lon=' + longitude + '&appid=44db16db91ed95ba40b859d2a01c1d96&lang=fr'; // URL pour la requête météo
                
                $.ajax({
                    url: weatherApiUrl,
                    method: 'GET',
                    success: function(weatherData) {
                        var temperature = (weatherData.main.temp - 273.15).toFixed(1);
                        var description = weatherData.weather[0].description;
                        var windSpeed = weatherData.wind.speed;
                        var weatherIconCode = weatherData.weather[0].icon;
                        var weatherIconUrl = 'https://openweathermap.org/img/w/' + weatherIconCode + '.png';
                        
                        // Afficher les informations météo
                        $('#temperatureInfo').append('<i class="fas fa-thermometer-half"></i> Température : ' + temperature + '°C');
                        $('#descriptionInfo').append('<i class="fas fa-info-circle"></i> Description : ' + description + ' <span class="weather-icon"><img src="' + weatherIconUrl + '"></span>');
                        $('#windInfo').append('<i class="fas fa-wind"></i> Vitesse du vent : ' + windSpeed + ' m/s');
                        
                        $('#weatherInfo').removeClass('d-none'); // Affiche les informations météo
                        
                        // Changer la couleur de fond en fonction de la température
                        if (temperature > 30) {
                            $('body').css('background-color', '#FF0000');
                        } else if (temperature >= 20 && temperature <= 29) {
                            $('body').css('background-color', '#FFA500');
                        } else if (temperature >= 15 && temperature <= 19) {
                            $('body').css('background-color', '#FFD700');
                        } else if (temperature >= 6 && temperature <= 14) {
                            $('body').css('background-color', '#D3D3D3');
                        } else if (temperature < 5) {
                            $('body').css('background-color', '#43A1FF');
                        }
                        
                        // Obtenir l'heure actuelle et le jour de la semaine
                        var currentDateTime = new Date();
                        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                        var currentDateTimeString = currentDateTime.toLocaleDateString('fr-FR', options);
                        $('#currentHour').html(currentDateTimeString); // Affiche l'heure actuelle
                        
                        // Initialiser la carte
                        initMap(latitude, longitude);
                        
                        // Animer la transition vers le haut
                        $('body').animate({marginTop: '20px'}, 1000);
                    },
                    error: function(error) {
                        console.error('Erreur lors de la requête météo :', error);
                    }
                });
            },
            error: function(error) {
                console.error('Erreur lors de la requête :', error);
            }
        });
    });

    $('#villeSelect').on('change', function() {
        var selectedVille = $(this).val();
        if (!selectedVille) return; // Si aucune ville sélectionnée, ne rien faire

        // Réinitialiser les informations de la météo
        $('#coordinates').addClass('d-none').empty();
        $('#temperatureInfo').empty();
        $('#descriptionInfo').empty();
        $('#weatherIcon').empty();
        $('#windInfo').empty();
        $('#weatherInfo').addClass('d-none');

        var apiUrl = 'api.php?city=' + selectedVille; // URL pour la recherche de coordonnées de la ville

        $.ajax({
            url: apiUrl,
            method: 'GET',
            success: function(data) {
                $('#temperatureInfo').text('Température : ' + data.temperature);
                $('#descriptionInfo').text('Description : ' + data.description);
                $('#windInfo').text('Vitesse du vent : ' + data.windSpeed);
                $('#weatherInfo').removeClass('d-none');
            }
        });
    });