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
