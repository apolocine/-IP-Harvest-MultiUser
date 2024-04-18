# IP-Harvest-MultiUser
How to use :
Document préparé par Dr Hamid MADANI
mailto: drmdh@msn.com
Date 18 april 2024 Mostaganem
Objectif : collecter les adresses IP de divers serveurs qui ne sont pas équipés d'une adresse IP fixe.
Goal: Retrieve the various IP addresses of servers that do not have a fixed IP address.

1) Préparation SQL :
<br/>
hmd@amia26:~$ sudo mysql -u root -p<br/>
mysql> create database ip_db;<br/>
mysql> use ip_db;<br/>
CREATE TABLE ips_users (<br/>
    id INT AUTO_INCREMENT PRIMARY KEY,<br/>
    activated TINYINT(1) NOT NULL DEFAULT 0,<br/>
    username VARCHAR(255) NOT NULL UNIQUE,<br/>
    password VARCHAR(255) NOT NULL<br/>
);<br/>
<br/>
 
CREATE TABLE ips_table (<br/>
    id INT AUTO_INCREMENT PRIMARY KEY,<br/>
    user_id INT NOT NULL,<br/>
    ip_address VARCHAR(15) NOT NULL,<br/>
    timestamp DATETIME NOT NULL,<br/>
    servername VARCHAR(15)  NULL,<br/>
    FOREIGN KEY (user_id) REFERENCES ips_users(id)<br/>
);<br/>
<br/>
2) Copier les fichier dans le repertoire du site 

3) Enregistrement :
  http://ips.amia.fr/register.php
  création d'un username et un passord.
5) Acces au acueil intex.php
   login username et un passord.
   
4) Acces au DashBoard.php :
  Modifier directement le text dans le textaréa puis enregistrement du fichier sendip.php
5) Envoi automatique de ip au serveur
   5.1 Execution unique :
        $ php sendip.php
   5.2 Execution programmée avec cron :
        $EDITOR=nano crontab -e:
   5.3 Ajouter une ligne pour execution le script chaque 59 eme minute de chaque heure
       59 * * * * /usr/bin/php /directory/ip/sendip.php >> /directory/ip/info.log 2>&1: 


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




