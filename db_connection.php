<?php
$host = 'localhost'; // or the IP address of your MySQL server
$dbname = 'rentalDB'; // replace with your database name
$username = 'root'; // default MySQL username
$password = ''; // default MySQL password is usually empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
