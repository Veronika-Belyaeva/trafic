<?php
require 'db_connection.php';
session_start();

// Обработка регистрации
if (isset($_POST['signup'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Проверка совпадения паролей
    if ($password !== $confirm_password) {
        $registration_error = "Пароли не совпадают.";
    } 
    else if (
        strlen($password) < 8 || // Проверка на минимальную длину
        !preg_match('/[A-Z]/', $password) || // Хотя бы одна заглавная латинская буква
        !preg_match('/\d/', $password) || // Хотя бы одна цифра
        !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password) // Хотя бы один из специальных символов
    ) {
        $registration_error = "Пароль должен содержать минимум 8 символов, одну заглавную латинскую букву, одну цифру и один специальный символ (!@#$%^&*()\-_=+{};:,<.>).";
    } 
    // Валидация email
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registration_error = "Некорректный email.";
    } else {
        // Проверка наличия пользователя с таким email в базе данных
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $registration_error = "Пользователь с таким email уже зарегистрирован.";
        } else {
            // Хеширование пароля
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Вставка новой записи в таблицу пользователей
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $stmt->execute();

            // Получаем user_id только что зарегистрированного пользователя
            $user_id = $pdo->lastInsertId();

            // Устанавливаем user_id в сессии
            $_SESSION['user_id'] = $user_id;

            // Редирект на страницу успешной регистрации или другие действия
            header("Location: personal_area.php");
            exit();
        }
    }
}

// Обработка входа пользователя
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Валидация email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $login_error = "Некорректный email.";
    } else {
        // Получение хешированного пароля из базы данных
        $stmt = $pdo->prepare("SELECT user_id, password, role FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Проверка пароля
        if ($user && password_verify($password, $user['password'])) {
            // Устанавливаем user_id в сессии
            $_SESSION['user_id'] = $user['user_id'];

            // Проверяем роль пользователя и перенаправляем в зависимости от роли
            if ($user['role'] === 'admin') {
                header("Location: admin_questions.php");
            } else {
                header("Location: personal_area.php");
            }
            exit();
        } else {
            // Неверный email или пароль
            $login_error = "Неверный email или пароль.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login & Signup Form</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="registration">
<section class="section-registration">
<!-- <button type="button" onclick="window.location.href='index.html'">Вернуться на главную</button> -->
    <div class="wrapper">
    <div class="form signup">
        <header>Signup</header>
        <?php if (isset($registration_error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($registration_error); ?></p>
        <?php endif; ?>
        <form action="" method="post">
            <input type="text" placeholder="Полное имя" id="username" name="username" required>
            <input type="email" placeholder="Ваш email" id="email" name="email" required>
            <input type="password" placeholder="Пароль" id="password" name="password" required>
            <input type="password" placeholder="Повторите пароль" id="confirm_password" name="confirm_password" required>
            <div class="checkbox">
                <input type="checkbox" id="signupCheck"/>
                <label for="signupCheck">Я принимаю все условия</label>
            </div>
            <input type="submit" value="Регистрация" name="signup"/>
        </form>
    </div>


        <div class="form login">
            <header>Вход</header>
            <?php if (isset($login_error)): ?>
                <p class="error-message"><?php echo htmlspecialchars($login_error); ?></p>
            <?php endif; ?>
            <form action="" method="post">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <a href="forgot.php">Забыли пароль?</a>
                <input type="submit" value="Вход" name="login"/>
            </form>
        </div>

        <script src="js/main.js"></script>
    </div>
</section>

</body>
</html>
