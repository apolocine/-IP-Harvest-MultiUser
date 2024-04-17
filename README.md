# IP-Harvest-MultiUser
How to use :
Document préparé par Dr Hamid MADANI
mailto: drmdh@msn.com
Date 18 april 2024 Mostaganem
Objectif : collecter les adresses IP de divers serveurs qui ne sont pas équipés d'une adresse IP fixe.
Goal: Retrieve the various IP addresses of servers that do not have a fixed IP address.

1) Préparation SQL :
<br/>
hmd@amia26:~$ sudo mysql -u root -p
mysql> create database ip_db;
mysql> use ip_db;
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
