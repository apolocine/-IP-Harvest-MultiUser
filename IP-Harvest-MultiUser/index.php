<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
 include_once 'config.php'; // Inclure le fichier config.php

/**
CREATE TABLE ips_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    activated TINYINT(1) NOT NULL DEFAULT 0,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
 

CREATE TABLE ips_table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(15) NOT NULL,
    timestamp DATETIME NOT NULL,
    servername VARCHAR(15)  NULL,
    FOREIGN KEY (user_id) REFERENCES ips_users(id)
);


**/
// Vérifie si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Vérifie si le formulaire de connexion a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valider les données d'entrée
    $username = $_POST["username"];
    $password = $_POST["password"];



 
 

    $conn = new mysqli($servername, $db_username, $db_password, $db_name);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Préparer la requête SQL pour récupérer l'utilisateur
    $sql = "SELECT id, username, password FROM ips_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si l'utilisateur existe dans la base de données
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Vérifier le mot de passe
        if (password_verify($password, $row['password'])) {
            // Authentification réussie, définir les variables de session et rediriger vers le tableau de bord
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['password'] = $password  ;
            header("Location: dashboard.php");
            exit();
            
            
        } else {
            // Mot de passe incorrect
            $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        // Utilisateur non trouvé
        $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
    }

    // Fermer la connexion à la base de données
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Nom d'utilisateur:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Se connecter">
    </form>
    
    Don't have an account?   registration   allowed  <a href="register.php" >Sign up</a>. 
    
</body>
</html>

