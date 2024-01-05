<?php
require_once "includes/db_connect.php";

header('Content-Type: application/json');

// Vérifier que le jeton d'accès a été fourni
if (!isset($_GET['token'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Token d\'acces manquant']);
    exit;
}

// Extraire le jeton d'accès de l'en-tête Authorization
$accessToken = $_GET['token'];

// Préparer une requête pour trouver l'utilisateur avec le jeton d'accès
$stmt = $pdo->prepare('SELECT * FROM users WHERE token = ?');
$stmt->bindParam(1, $accessToken);
$stmt->execute();

// Vérifier que le jeton d'accès est valide
if ($stmt->rowCount() === 0) {
    http_response_code(403);
    echo json_encode(['error' => 'Token d\'acces invalide']);
    exit;
}

$city = $_GET['city'];

// URL pour la recherche de coordonnées de la ville
$apiUrl = 'https://api-adresse.data.gouv.fr/search/?q=' . urlencode($city);

// Envoyer une requête GET à l'API
$response = @file_get_contents($apiUrl);
if ($response === false) {
    http_response_code(400);
    echo json_encode(['error' => 'Nom de ville incorrect']);
    exit;
}

$data = json_decode($response, true);

// Vérifier si les données attendues existent
if (!isset($data['features'][0]['geometry']['coordinates'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Nom de ville incorrect']);
    exit;
}

// Extraire les coordonnées de la réponse
$coordinates = $data['features'][0]['geometry']['coordinates'];

// Construire l'URL pour l'API météo
$weatherApiUrl = 'https://api.openweathermap.org/data/2.5/weather?lat=' . $coordinates[1] . '&lon=' . $coordinates[0] . '&appid=44db16db91ed95ba40b859d2a01c1d96&lang=fr';

// Envoyer une requête GET à l'API météo
$weatherResponse = @file_get_contents($weatherApiUrl);
if ($weatherResponse === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la récupération des données météo']);
    exit;
}

$weatherData = json_decode($weatherResponse, true);

// Vérifier si les données météo attendues existent
if (!isset($weatherData['main']['temp'], $weatherData['weather'][0]['description'], $weatherData['wind']['speed'])) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la recuperation des donnees meteo']);
    exit;
}

// Extraire les données météo de la réponse
$temp = $weatherData['main']['temp'];
$description = $weatherData['weather'][0]['description'];
$windSpeed = $weatherData['wind']['speed'];

// Renvoyer les données météo en JSON
echo json_encode([
    'temperature' => number_format($temp - 273.15, 3),
    'description' => $description,
    'windSpeed' => number_format($windSpeed, 3),
]);