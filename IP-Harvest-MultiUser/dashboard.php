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

ALTER TABLE ips_users
ADD COLUMN activated TINYINT(1) NOT NULL DEFAULT 0;

CREATE TABLE ips_table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(15) NOT NULL,
    timestamp DATETIME NOT NULL,
    servername VARCHAR(15)  NULL,
    FOREIGN KEY (user_id) REFERENCES ips_users(id)
);
ALTER TABLE ips_table
ADD COLUMN servername VARCHAR(15) NOT NULL;


**/
// Vérifie si le formulaire de déconnexion a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    // Détruire la session et rediriger vers la page de connexion
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    
    <style>
        #sendip-content {
            border: 5px solid;
            border-color: green; /* Couleur par défaut pour la bordure */
        }
    </style>
    
</head>
<body>
    <h2>Tableau de bord</h2>
    <table>
    <tr>
    
      <td>
     
   <?php if (isset($_SESSION['username'])) { ?>
        <p>Bienvenue, <?php echo $_SESSION['username']; ?> !</p>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="submit" name="logout" value="Déconnexion">
    </form>
     <!--
    <form method="post" action="logout.php">
        <input type="submit" name="logout" value="Déconnexion">
    </form>
-->
    </td>
     </tr>
     <tr>
    <td>
    
   
   
     <p>  <!-- <a href="sendip.php.txt">sendip.php</a> --><br/>
     
     <h2>Contenu de sendip.php</h2>
    <textarea id="sendip-content" rows="10" cols="50" readonly><?php echo htmlspecialchars(file_get_contents("sendip.php")); ?></textarea>
    <br>
    <button onclick="toggleEditable()" id="edit-toggle">Activer l'édition</button>
  <a id="download-link" href="" download="sendip.php"><button>Télécharger le fichier sendip.php</button></a>
  <button onclick="copyContent()">Copier le contenu</button>
 
    <script>
    
      function toggleEditable() {
            var textarea = document.getElementById("sendip-content");
            textarea.readOnly = !textarea.readOnly;
             textarea.style.borderColor = textarea.readOnly ? "green" : "red"; /* Changement de couleur de la bordure */
                 var editButton = document.getElementById("edit-toggle");
                 editButton.textContent = textarea.readOnly ? "Activer l'édition" : "Désactiver l'édition";
      editButton .style.backgroundColor = textarea.readOnly ? "green" : "red";
        }

        function copyContent() {
            var textarea = document.getElementById("sendip-content");
            textarea.select();
            document.execCommand("copy");
            alert("Le contenu a été copié !");
        }
        
   
        
         function updateDownloadLink() {
            var textarea = document.getElementById("sendip-content");
            var downloadLink = document.getElementById("download-link");
            downloadLink.href = "data:text/plain;charset=utf-8," + encodeURIComponent(textarea.value);
        }

        window.onload = function() {
            updateDownloadLink();
        };

        document.getElementById("sendip-content").addEventListener("input", function() {
            updateDownloadLink();
        });
        
           /*
           //permet de telecharger le fichier sans qu'il soit modifié
           window.onload = function() {
            var textarea = document.getElementById("sendip-content");
            var downloadLink = document.getElementById("download-link");
            downloadLink.href = "data:text/plain;charset=utf-8," + encodeURIComponent(textarea.value);
        };
        */
        
    </script>
    
    </td>
    <td>
      #Execution unique : <br/>
      $  php sendip.php</p>
      #Execution programmée avec cron : <br/>
      $EDITOR=nano crontab -e: <br/>
      #Ajouter une ligne : <br/>
      #Execution chaque 59 eme minute de chaque heure: <br/>
      $59 * * * * /usr/bin/php /directory/ip/sendip.php >> /directory/ip/info.log 2>&1: <br/>
      
        </p>
    <?php } else { ?>
        <p>Bienvenue, visiteur !</p>
    <?php } ?>
   

 </td>
    
  
    
    </tr>
    </table>
    
<?php
 

 // Connexion à la base de données
    $conn = new mysqli($servername, $db_username, $db_password, $db_name);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Échec de la connexion à la base de données : " . $conn->connect_error);
    }
    
    $ip_username = null;
if (isset($_SESSION['username'])) {
$ip_username = $_SESSION['username'];
}
// Vérifier si l'utilisateur est admin avec le mot de passe correct
if ($_SESSION['username'] == 'admin' && $_SESSION['password'] == 'adminSWS') {
   

    // Requête SQL pour récupérer la liste des utilisateurs
    $sql = "SELECT * FROM ips_users";
    $result = $conn->query($sql);

 

 // Vérifier s'il y a des résultats
    if ($result->num_rows > 0) {
        // Afficher les résultats dans un tableau avec des options de modification et de suppression
        echo "<table border='1'><tr><th>ID</th><th>Username</th><th>password</th><th>Actions</th><th>M</th> </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
             echo "<td>" . $row["password"] . "</td>";
              echo "<td>" . $row["activated"] . "</td>";
            echo "<td>";
            echo "<form method='post' action='modify_user.php'>";
            echo "<input type='hidden' name='user_id' value='" . $row["id"] . "'>";
            echo "<input type='hidden' name='new_username' value='" . $row["username"] . "'>";
            echo "<input type='hidden' name='new_password' value='" . $row["password"] . "'>";
            echo "<input type='hidden' name='new_activated' value='" . $row["activated"] . "'>";
           // echo "<input type='submit' name='modify' value='Modifier'>";
            echo "<input type='submit'   value='Modifier'>";
            echo "</form>";
             echo "</td>";
           
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Aucun utilisateur trouvé.";
    }
   echo '<a href="register.php" >Sign up</a>';
    // Fermer la connexion à la base de données
    $conn->close();
} else {
   





// Supprimer les enregistrements obsolètes (par exemple, supprimer tous les enregistrements plus anciens que 24 heures)
$timezone = new DateTimeZone('UTC');
$expiry_time = new DateTime('-24 hours', $timezone);
$expiry_time = $expiry_time->format('Y-m-d H:i:s');

$sql_delete = "DELETE FROM ips_table WHERE timestamp < '$expiry_time'";
$conn->query($sql_delete);

// Traitement de la suppression si un identifiant est fourni dans l'URL
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM ips_table WHERE id = $delete_id";
    $conn->query($sql_delete);
}

// Requête SQL SELECT pour récupérer les adresses IP enregistrées pour cet utilisateur
$sql = "SELECT id, ip_address, timestamp, servername FROM ips_table WHERE user_id = (SELECT id FROM ips_users WHERE username = '".$ip_username."')";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Afficher les résultats dans un tableau HTML
    echo "<table><tr><th>Server Name</th><th>Adresse IP</th><th>Timestamp</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["servername"] . "</td>";
        echo "<td><a href='https://" . $row["ip_address"] . ":443'>" . $row["ip_address"] . "</a></td>";
        echo "<td>" . $row["timestamp"] . "</td>";
        echo "<td><a href='?delete_id=" . $row["id"] . "'>X</a></td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "Aucune adresse IP enregistrée pour cet utilisateur.";
}

// Fermer la connexion à la base de données
$conn->close();


    
    
    
}
?>



</body>
</html>
