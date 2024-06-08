<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Подключение к базе данных
$host = 'localhost'; // Хост базы данных
$port = '3306'; // Порт базы данных
$dbname = 'bv2011pz_tickets';
$username = 'bv2011pz_tickets';
$password = 'cgQ1wpi';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>
