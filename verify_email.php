<?php
require 'db_connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    $stmt = $pdo->prepare("SELECT user_id, email_verified FROM users WHERE verification_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && !$user['email_verified']) {
        $stmt = $pdo->prepare("UPDATE users SET email_verified = TRUE, verification_token = NULL WHERE user_id = ?");
        $stmt->execute([$user['user_id']]);
        
        echo "Email successfully verified. You can now <a href='registration.php'>login</a>.";
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>
