<?php


$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Recevoir les données JSON
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if ($data !== null && isset($data['ip']) && isset($data['ip_username']) && isset($data['ip_password'])) {
    // Préparer la requête SQL d'insertion
    $ip_address = $conn->real_escape_string($data['ip']);
    $servername = $conn->real_escape_string($data['servername']); // Si le serveurname est disponible
    $ip_username = $conn->real_escape_string($data['ip_username']);
    $ip_password = $conn->real_escape_string($data['ip_password']);
    $timestamp = date('Y-m-d H:i:s');  // Obtenez le timestamp actuel

    // Préparer la requête SQL pour récupérer l'utilisateur
    $sql_user = "SELECT id, username, password FROM ips_users WHERE username = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("s", $ip_username);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    // Vérifier si l'utilisateur existe dans la base de données
    if ($result_user->num_rows == 1) {
        $row_user = $result_user->fetch_assoc();
        $user_id = $row_user['id']; // Récupérer l'ID de l'utilisateur identifié
        $hashed_password = $row_user['password']; // Récupérer le mot de passe haché de l'utilisateur

        // Vérifier le mot de passe
        if (password_verify($ip_password, $hashed_password)) {
            // Mot de passe correct, procéder à l'insertion dans la ips_table
            // Préparer la requête SQL d'insertion dans la ips_table
            if ($servername !== null) {
                $sql_ip = "INSERT INTO ips_table (user_id, ip_address, timestamp, servername) VALUES (?, ?, ?, ?)";
                $stmt_ip = $conn->prepare($sql_ip);
                $stmt_ip->bind_param("isss", $user_id, $ip_address, $timestamp, $servername);
            } else {
                $sql_ip = "INSERT INTO ips_table (user_id, ip_address, timestamp) VALUES (?, ?, ?)";
                $stmt_ip = $conn->prepare($sql_ip);
                $stmt_ip->bind_param("iss", $user_id, $ip_address, $timestamp);
            }

            // Exécuter la requête SQL d'insertion dans la ips_table
            if ($stmt_ip->execute()) {
                // Répondre avec succès
                $response = ['status' => 'success', 'message' => 'IP enregistrée avec succès'];
            } else {
                // Gérer les erreurs d'insertion
                $response = ['status' => 'error', 'message' => 'Erreur lors de l\'enregistrement de l\'IP : ' . $conn->error];
            }
        } else {
            // Mot de passe incorrect
            $response = ['status' => 'error', 'message' => 'Mot de passe incorrect'];
        }
    } else {
        // L'utilisateur n'existe pas dans la base de données
        $response = ['status' => 'error', 'message' => 'Utilisateur non trouvé dans la base de données'];
    }
} else {
    // Gérer les erreurs de décodage JSON ou de données manquantes
    $response = ['status' => 'error', 'message' => 'Données JSON non valides ou adresse IP/username/mot de passe manquant'];
}

// Fermer les requêtes préparées
$stmt_user->close();
$stmt_ip->close();

// Fermer la connexion à la base de données
$conn->close();

// Retourner la réponse en format JSON
header('Content-Type: application/json');
echo json_encode($response);

?>

