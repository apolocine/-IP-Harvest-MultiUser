CREATE TABLE ips_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    activated TINYINT(1) NOT NULL DEFAULT 0,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
--admin user as username: admin  password: adminSWS 
INSERT INTO `ips_users` (`id`, `activated`, `username`, `password`) VALUES (NULL, '1', 'admin', '$2y$10$I5hgwm0whGjqs500/ya4WeODfiaOKcIfs6OxseYNzebvcSF2UZxXa') ;
    
--ALTER TABLE ips_users
--ADD COLUMN activated TINYINT(1) NOT NULL DEFAULT 0;

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

