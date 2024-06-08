<?php
require "db_connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $user_id = $user['user_id'];
        $expiry_time = time() + 3600; // 1 hour

        $stmt = $pdo->prepare("UPDATE users SET reset_token_expiry = FROM_UNIXTIME(?) WHERE user_id = ?");
        if ($stmt->execute([$expiry_time, $user_id])) {
            $reset_link = "http://traffic-laws.ru/reset.php?user_id=$user_id&expiry=$expiry_time";
            $subject = "Восстановление пароля";
            $message = "Для восстановления пароля перейдите по следующей ссылке: $reset_link";
            $headers = "From: traffic-laws@traffic-laws.ru";

            if (mail($email, $subject, $message, $headers)) {
                header('Location: registration.php');
            } else {
                echo "Не удалось отправить письмо. Попробуйте снова.";
            }
        } else {
            echo "Не удалось сохранить токен. Попробуйте снова.";
        }
    } else {
        echo "Пользователь с такой электронной почтой не найден.";
    }
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
<title>Восстановление пароля</title>
</head>
<body class="body-forgot">
<section class="section-forgot">
  <div class="wrapper">
    <div class="form">
      <header>Восстановление пароля</header>
      <form action="forgot.php" method="post">
        <label for="email">Введите вашу электронную почту:</label>
        <input type="email" name="email" placeholder="Email" id="email" required>
        <input type="submit" value="Отправить">
      </form>
    </div>
  </div>
</section>
</body>
</html>
