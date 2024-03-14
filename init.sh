#!/bin/bash


check_mysql_running() {
    if systemctl is-active --quiet mysql; then
        return 0
    else
        return 1
    fi
}

start_mysql() {
    sudo systemctl start mysql
}

if ! check_mysql_running; then
    echo "MySQL is not running. Starting MySQL..."
    start_mysql
fi

root="root"
password="your_mysql_root_password"
db="my_database"
user="regphp"
userpassword="yourpassword"

echo "Creating database..."
mysql -u "$root" -p"$password" -e "CREATE DATABASE IF NOT EXISTS $db;"

echo "Creating user..."
mysql -u "$root" -p"$password" -e "CREATE USER IF NOT EXISTS '$user'@'localhost' IDENTIFIED BY '$userpassword';"

echo "Granting privileges..."
mysql -u "$root" -p"$password" -e "GRANT ALL PRIVILEGES ON $db.* TO '$user'@'localhost';"
mysql -u "$root" -p"$password" -e "FLUSH PRIVILEGES;"

echo "Creating tables..."
mysql -u "$root" -p"$password" "$db" <<EOF
CREATE TABLE IF NOT EXISTS logins (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(100) NOT NULL UNIQUE,
  password varchar(100) NOT NULL,
  date_of_joining datetime DEFAULT current_timestamp(),
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS posts (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) DEFAULT NULL,
  title varchar(255) NOT NULL,
  content text NOT NULL,
  created_at timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (id),
  KEY user_id_idx (user_id),
  CONSTRAINT posts_user_id_fk FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS users (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(50) NOT NULL UNIQUE,
  password varchar(255) NOT NULL,
  email varchar(100) NOT NULL UNIQUE,
  PRIMARY KEY (id)
);
EOF

echo "Database setup complete!"
