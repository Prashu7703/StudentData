<?php
$host = 'localhost'; // Database host
$user = 'root';      // Database user
$pass = '';          // Database password
$dbname = 'school_system'; // Database name

// Create a PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>
