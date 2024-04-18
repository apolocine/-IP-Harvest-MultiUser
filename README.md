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
--admin user as username: admin  password: adminSWS <br/>
INSERT INTO `ips_users` (`id`, `activated`, `username`, `password`) VALUES (NULL, '1', 'admin', '$2y$10$I5hgwm0whGjqs500/ya4WeODfiaOKcIfs6OxseYNzebvcSF2UZxXa') ;<br/>
    

CREATE TABLE ips_table (<br/>
    id INT AUTO_INCREMENT PRIMARY KEY,<br/>
    user_id INT NOT NULL,<br/>
    ip_address VARCHAR(15) NOT NULL,<br/>
    timestamp DATETIME NOT NULL,<br/>
    servername VARCHAR(15)  NULL,<br/>
    FOREIGN KEY (user_id) REFERENCES ips_users(id)<br/>
);<br/>
<br/>
3) Copier les fichier dans le repertoire du site <br/>
IP-Harvest-MultiUser dans votre site<br/>
4) Enregistrement :<br/>
  http://ips.amia.fr/register.php<br/>
  création d'un username et un passord.<br/>
5) Acces au accueil index.php<br/>
    http://ips.amia.fr/<br/>
   login username et un passord.<br/>
   <br/>
4) Acces au DashBoard.php :<br/>
  Modifier directement le text dans le textaréa puis enregistrement du fichier sendip.php<br/>
5) Envoi automatique de ip au serveur<br/>
   5.1 Execution unique :<br/>
        $ php sendip.php<br/>
   5.2 Execution programmée avec cron :<br/>
        $EDITOR=nano crontab -e:<br/>
   5.3 Ajouter une ligne pour execution le script chaque 59 eme minute de chaque heure<br/>
       59 * * * * /usr/bin/php /directory/ip/sendip.php >> /directory/ip/info.log 2>&1: <br/>


 
<br/>



