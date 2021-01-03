<?php
/**
 * Run this script from browser eg http://localhost/worktime/init.php if you are setting up your environment for first time
 * This script creates all databases correctly to mysql
 */

$DB_HOST = "127.0.0.1"; // put your own data
$DB_NAME = "test123"; // put your own data
$DB_USER = "test123"; // put your own data
$DB_PASS = "test123"; // put your own data


$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if($conn->connect_errno > 0) {
  die('Connection failed [' . $conn->connect_error . ']');
}

// User table
$sql = "CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `class` varchar(50) NOT NULL,
    `firstname` text NOT NULL,
    `lastname` text NOT NULL,
    `user_company_id` int(7) NOT NULL,
    `created_at` datetime DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`)
   ) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4";

$result = mysqli_query($conn,"SELECT id FROM users");

if (empty($result)) {
    $result = mysqli_query($conn,$sql);
    if ($result) {
        echo "User table created succesfully to database<br>";
    }
} else {
    echo "User table already exists!<br>";
}

// Company table
$sql = "CREATE TABLE IF NOT EXISTS `company` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `company_name` text NOT NULL,
    `ytunnus` varchar(30) NOT NULL,
    `company_address` text NOT NULL,
    `company_postcode` text NOT NULL,
    `company_area` varchar(15) NOT NULL,
    `created_at` datetime NOT NULL DEFAULT current_timestamp(),
    `created_user_id` int(11) NOT NULL,
    `is_client` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `id` (`id`)
   ) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4";

$result = mysqli_query($conn,"SELECT id FROM company");

if (empty($result)) {
    $result = mysqli_query($conn,$sql);
    if ($result) {
        echo "company table created succesfully to database<br>";
    }
} else {
    echo "Company table already exists!<br>";
}

// Workday table
$sql = "CREATE TABLE `workday` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `date` date NOT NULL,
    `start_time` datetime NOT NULL,
    `end_time` datetime NOT NULL,
    `break_time` time NOT NULL,
    `total_time` time NOT NULL,
    `explanation` text DEFAULT NULL,
    `created_time` datetime NOT NULL DEFAULT current_timestamp(),
    `modified_time` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `modified_user_id` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
   ) ENGINE=InnoDB AUTO_INCREMENT=254 DEFAULT CHARSET=utf8mb4";

$result = mysqli_query($conn,"SELECT id FROM workday");

if (empty($result)) {
    $result = mysqli_query($conn,$sql);
    if ($result) {
        echo "workday table created succesfully to database<br>";
    }
} else {
    echo "Workday table already exists!<br>";
}

$conn->close();

?>