<?php
// Utilisation de l'API d'un service tiers pour obtenir l'adresse IP publique
$api_url = 'https://api64.ipify.org?format=json';

// Obtenez le nom d'hôte
$hostname = gethostname();

// Username et mot de passe
$ip_username = 'a';
$ip_password = "a"; // Mot de passe déjà hashé

// Effectuer une requête HTTP pour récupérer les données
$response = file_get_contents($api_url);

// Vérifier si la requête a réussi
if ($response !== false) {
    // Convertir la réponse JSON en tableau associatif
    $data = json_decode($response, true);

    // Vérifier si la conversion a réussi
    if ($data !== null && isset($data['ip'])) {
        // Ajouter des données supplémentaires au tableau associatif
        $data['servername'] = $hostname;
        $data['ip_username'] = $ip_username;
        $data['ip_password'] = $ip_password; // Ajouter le mot de passe hashé directement

        // Re-encoder le tableau associatif en JSON
        $new_response = json_encode($data);
        
$remote_url = 'https://ips.amia.fr/receiveip.php';
 
        // URL du script distant pour recevoir les données
   //     $remote_url = 'http://localhost/ips/receiveip.php';

        // Options de la requête HTTP
        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $new_response,
            ],
        ];

        // Créer un contexte de flux avec les options de requête
        $context = stream_context_create($options);

        // Effectuer la requête HTTP POST vers le script distant
        $result = file_get_contents($remote_url, false, $context);

        // Vérifier si la requête a réussi
        if ($result !== false) {
            // Afficher le résultat
            echo 'Résultat : ' . $result . PHP_EOL;
        } else {
            // Gérer les erreurs de requête HTTP
            echo 'Erreur lors de la requête HTTP vers le script distant.' . PHP_EOL;
        }

        // Afficher l'adresse IP publique
        echo 'Adresse IP publique : http://' . $data['ip'] . PHP_EOL;
    } else {
        // Gérer les erreurs de conversion JSON ou les données manquantes
        echo 'Erreur lors de la conversion de la réponse JSON ou des données manquantes.' . PHP_EOL;
    }
} else {
    // Gérer les erreurs de requête HTTP vers l'API d'adresse IP publique
    echo 'Erreur lors de la requête HTTP vers le service d\'adresse IP publique.' . PHP_EOL;
}
?>

