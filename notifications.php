<!DOCTYPE html>
<?php
require 'db_connection.php';
// Получение идентификатора пользователя
session_start();
$user_id = $_SESSION['user_id']; // Убедитесь, что вы установили идентификатор пользователя в сессии при входе

// Получение уведомлений для пользователя
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE receiver_id = ?");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);


if(isset($_POST['logout'])) {
    // Уничтожаем сессию
    session_unset();
    session_destroy();
    // Перенаправляем пользователя на страницу входа
    header("Location: registration.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Notifications</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>User Notifications</h2>
        <div id="notification-list" class="list-group">
            <?php foreach ($notifications as $notification): ?>
                <div class="list-group-item">
                    <p><?= htmlspecialchars($notification['message']) ?></p>
                    <small class="text-muted"><?= htmlspecialchars(date('Y-m-d H:i:s', strtotime($notification['timestamp']))) ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
    // Функция для отображения окна подтверждения при попытке выхода из учетной записи
    function confirmLogout() {
      return confirm("Вы уверены, что хотите выйти?");
    }
  </script>
</body>
</html>

