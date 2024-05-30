<?php
require "db_connection.php";

if (isset($_GET['user_id']) && isset($_GET['expiry'])) {
    $user_id = $_GET['user_id'];
    $expiry = $_GET['expiry'];

    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE user_id = ? AND reset_token_expiry > NOW()");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && time() < $expiry) {
        if (isset($_POST['password'])) {
            $new_password = $_POST['password'];
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token_expiry = NULL WHERE user_id = ?");
            if ($stmt->execute([$hashed_password, $user['user_id']])) {
                echo "Пароль успешно обновлен.";
                header('Location: registration.php');
                exit();
            } else {
                echo "Не удалось обновить пароль. Попробуйте снова.";
            }
        }
    } else {
        echo "Неверный или просроченный токен.";
    }
} else {
    echo "Токен не указан.";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@100;200;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сброс пароля</title>
</head>
<body class="body-forgot">
<section class="section-forgot">
  <div class="wrapper">
    <div class="form">
      <header>Восстановление пароля</header>
      <form action="" method="post">
        <label for="password">Введите новый пароль:</label>
        <input type="password" name="password" id="password" required>
        <input type="submit" value="Сбросить пароль">
      </form>
    </div>
  </div>
</section>
</body>
</html>
