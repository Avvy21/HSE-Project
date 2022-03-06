<?php
$host = 'localhost';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
  die('Connect Error: ' . $conn->connect_error);
}
// make DB
$sql = 'CREATE DATABASE IF NOT EXISTS hseproject;';
if (!$conn->query($sql) === TRUE) {
  die('Error creating database: ' . $conn->error);
}
// use DB
$sql = 'USE hseproject;';
if (!$conn->query($sql) === TRUE) {
  die('Error using database: ' . $conn->error);
}
//make table
$sql = 'CREATE TABLE IF NOT EXISTS users (
    id int NOT NULL AUTO_INCREMENT,
    user_id bigint(20),
    user_name MEDIUMTEXT,
    full_name MEDIUMTEXT,
    address MEDIUMTEXT,
    dob MEDIUMTEXT,
    phone_number MEDIUMTEXT,
    password MEDIUMTEXT,
    iv varchar(32),
    img_file_name MEDIUMTEXT,
    img_contents MEDIUMTEXT,
    list MEDIUMTEXT,
    PRIMARY KEY (id));';
    if (!$conn->query($sql) === TRUE) {
      die('Error creating table: ' . $conn->error);
    }
?>