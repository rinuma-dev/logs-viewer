    <?php 
    require './vendor/autoload.php';
    require 'connect.php';


// create database DATALOG

    $sql = "CREATE DATABASE IF NOT EXISTS datalog ";
    if ($conn->query($sql) === TRUE) {
      echo "Database created successfully";
    } else {
      echo "Error creating database: " . $conn->error;
    }

   
// create table nginx_log
    $sql = "CREATE TABLE IF NOT EXISTS nginx_log (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        remote_address VARCHAR(15),
        remote_user VARCHAR(15),
        remote VARCHAR(15),
        time_local VARCHAR(30),
        request VARCHAR(200), status VARCHAR(3),
        body_bytes_sent INT(6),
        http_referer VARCHAR(200),
        rt VARCHAR(4), uct VARCHAR(6),
        uht VARCHAR(6), urt VARCHAR(6),
        gz VARCHAR(6)
    )";

    if ($connDB->query($sql) === TRUE) {
        echo "Database created successfully";
      } else {
        echo "Error creating database: " . $conn->error;
      }


// create tabel uwsgi
    $sql_table_uwsgi = "CREATE TABLE IF NOT EXISTS uwsgi_log (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        address_space_usage VARCHAR(15),
        address_space VARCHAR(8),
        rss_usage VARCHAR(15),
        rss VARCHAR(30),
        pid VARCHAR(15),
        app VARCHAR(10),
        req VARCHAR(5)
    )";

if ($connDB->query($sql_table_uwsgi) === TRUE) {
    echo "Database uwsgi created successfully";
  } else {
    echo "Error creating database: " . $conn->error;
  }

      $conn->close();
    // ?>