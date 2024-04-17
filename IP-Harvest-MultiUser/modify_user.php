<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Vérifie si l'identifiant de l'utilisateur à modifier est passé en paramètre
if (!isset($_POST['user_id'])) {
    echo "Aucun utilisateur spécifié.";
    exit();
}

// Récupère l'identifiant de l'utilisateur à modifier depuis le formulaire
$user_id = $_POST['user_id'];

// Connexion à la base de données (à personnaliser avec vos propres informations de connexion)
$servername = "db5015489964.hosting-data.io";
$db_username = "dbu719104";
$db_password = "AWeefoo@26";
$db_name = "dbs12656493";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Vérifie si le formulaire de modification a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['modify'])) {
        // Récupère les nouvelles informations de l'utilisateur depuis le formulaire
        $new_username = $_POST['new_username'];
        $new_password = $_POST['new_password'];
        $new_activated = isset($_POST['new_activated']) ? 1 : 0;

        // Vérifie si le nom d'utilisateur est unique (sauf si c'est le même que celui actuel)
        $sql_check_username = "SELECT id FROM ips_users WHERE username = '$new_username' AND id != $user_id";
        $result_check_username = $conn->query($sql_check_username);

        if ($result_check_username->num_rows > 0) {
            echo "Le nom d'utilisateur existe déjà.";
            exit();
        }

        // Met à jour les informations de l'utilisateur dans la base de données
        $sql_update_user = "UPDATE ips_users SET username = '$new_username', password = '$new_password', activated = $new_activated WHERE id = $user_id";

        if ($conn->query($sql_update_user) === TRUE) {
          // Rediriger vers dashboard.php après la mis à jour avec succès.
   		 	header("Location: dashboard.php");
    			exit();
            echo "Utilisateur mis à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour de l'utilisateur : " . $conn->error;
        }
    } elseif (isset($_POST['delete'])) {
        // Supprimer l'utilisateur de la base de données
        $sql_delete_user = "DELETE FROM ips_users WHERE id = $user_id";

        if ($conn->query($sql_delete_user) === TRUE) {
          // Rediriger vers dashboard.php après la suppression
   		 	header("Location: dashboard.php");
    			exit();
            echo "Utilisateur supprimé avec succès.";
        } else {
            echo "Erreur lors de la suppression de l'utilisateur : " . $conn->error;
        }
    }
}

// Requête SQL pour récupérer les informations de l'utilisateur à modifier
$sql_get_user = "SELECT username, password, activated FROM ips_users WHERE id = $user_id";
$result_get_user = $conn->query($sql_get_user);

if ($result_get_user->num_rows == 1) {
    $row = $result_get_user->fetch_assoc();
    $current_username = $row['username'];
    $current_password = $row['password'];
    $current_activated = $row['activated'];
} else {
    echo "Aucun utilisateur trouvé avec cet identifiant.";
    exit();
}

// Fermer la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'utilisateur</title>
</head>
<body>
    <h2>Modifier l'utilisateur</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="new_username" placeholder="Nouveau nom d'utilisateur" value="<?php echo $current_username; ?>" required>
        <input type="password" name="new_password" placeholder="Nouveau mot de passe" value="<?php echo $current_password; ?>" required>
        <input type="checkbox" name="new_activated" <?php echo $current_activated ? 'checked' : ''; ?>>
        <label for="new_activated">Activé</label><br>
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <input type="submit" name="modify" value="Modifier">
         <input type="submit" name="delete" value="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">
    </form>
        <a href="dashboard.php">Retour au tableau de bord</a>
</body>
</html>

