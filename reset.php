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

            // Добавленная проверка пароля
            if (strlen($new_password) < 8 ||
                !preg_match('/[A-Z]/', $new_password) || // Хотя бы одна заглавная латинская буква
                !preg_match('/\d/', $new_password) || // Хотя бы одна цифра
                !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $new_password) // Хотя бы один из специальных символов
            ) {
                $error_message = "Пароль должен содержать минимум 8 символов, одну заглавную латинскую букву, одну цифру и один специальный символ (!@#$%^&*()\-_=+{};:,<.>).";
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token_expiry = NULL WHERE user_id = ?");
                if ($stmt->execute([$hashed_password, $user['user_id']])) {
                    header('Location: registration.php');
                    exit();
                } else {
                    $error_message = "Не удалось обновить пароль. Попробуйте снова.";
                }
            }
        }
    } else {
        $error_message = "Неверный или просроченный токен.";
    }
} else {
    $error_message = "Токен не указан.";
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
    <title>Сброс пароля</title>
</head>
<body class="body-forgot">
<section class="section-forgot">
  <div class="wrapper">
    <div class="form">
      <header>Восстановление пароля</header>
      <?php if (isset($error_message)): ?>
          <p><?php echo $error_message; ?></p>
      <?php endif; ?>
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
