<?php
$host = '127.0.0.1';
$port = '3306';
$user = 'root';
$pass = '';

try {
    // Connect without specifying database
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create the database
    $sql = "CREATE DATABASE IF NOT EXISTS evaluasi_panitia";
    $pdo->exec($sql);
    echo "Database 'evaluasi_panitia' checked/created successfully.\n";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
