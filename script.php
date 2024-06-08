<?php
require 'db_connection.php';
session_start();

$user_id = $_SESSION['user_id'];

if (isset($_POST['message'])) {
    $message = $_POST['message'];
    $receiver_id = 1; 
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$user_id, $receiver_id, $message]);
        echo "Message sent successfully."; // Отправляем сообщение об успешной отправке
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); // Выводим ошибку, если возникла
    }
}
?>
