# SHIT PHP WEB-APP 

This project is a deliberately vulnerable web application created for the purpose of refreshing and practicing PHP programming. Please note that the primary focus is on the functionality, and the user interface (UI) and CSS may be be fucked thanks to chatGPT.


## Database Setup

### Create Database

```bash
mysql -u root -p
CREATE DATABASE my_database;
```

### Create User

```bash
mysql -u root -p
CREATE USER 'regphp'@'localhost' IDENTIFIED BY 'yourpassword';
```

Replace `'yourpassword'` with the actual password you want to use.

### Grant Privileges

```bash
mysql -u root -p
GRANT ALL PRIVILEGES ON my_database.* TO 'regphp'@'localhost';
FLUSH PRIVILEGES;
```

## Table Structures

### logins Table

```sql
CREATE TABLE `logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL UNIQUE,
  `password` varchar(100) NOT NULL,
  `date_of_joining` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
);
```

### posts Table

```sql
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`),
  CONSTRAINT `posts_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
);
```

### users Table

```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
);
```

