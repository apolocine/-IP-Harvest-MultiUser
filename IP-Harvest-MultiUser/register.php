<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);



// Vérifie si le formulaire d'enregistrement a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valider les données d'entrée
    $username = $_POST["username"];
    $password = $_POST["password"];



    $conn = new mysqli($servername, $db_username, $db_password, $db_name);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Vérifier si l'utilisateur existe déjà
    $sql_check_user = "SELECT id FROM ips_users WHERE username = ?";
    $stmt_check_user = $conn->prepare($sql_check_user);
    $stmt_check_user->bind_param("s", $username);
    $stmt_check_user->execute();
    $result_check_user = $stmt_check_user->get_result();
    
 $stmt_insert_user = null;
 
 
    if ($result_check_user->num_rows > 0) {
        $error_message = "Le nom d'utilisateur est déjà utilisé.";
    } else {
        // Hacher le mot de passe avant de le stocker dans la base de données
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insérer l'utilisateur dans la base de données
        $sql_insert_user = "INSERT INTO ips_users (username, password) VALUES (?, ?)";
        $stmt_insert_user = $conn->prepare($sql_insert_user);
        $stmt_insert_user->bind_param("ss", $username, $hashed_password);

        if ($stmt_insert_user->execute()) {
            // Rediriger l'utilisateur vers la page de connexion après l'enregistrement réussi
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Une erreur s'est produite lors de l'enregistrement de l'utilisateur.";
        }
    }

 
    // Fermer la connexion à la base de données
$stmt_check_user->close();
if ($stmt_insert_user !== null) {
    $stmt_insert_user->close();
}
$conn->close();


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrement</title>
</head>
<body>
    <h2>Enregistrement</h2>
    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Nom d'utilisateur:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="S'inscrire">
    </form>
</body>
</html>

