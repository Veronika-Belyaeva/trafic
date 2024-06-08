<?php
require 'db_connection.php';
require 'send_email.php'; // Include the email sending script
session_start();

function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Обработка регистрации
if (isset($_POST['signup'])) {
    $recaptcha_secret = "6LeJzu8pAAAAAJl2NPcbD-Ta-o0cJvR5Jz0eRGl9";
    $recaptcha_response = $_POST['g-recaptcha-response'];

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_data = array(
        'secret' => $recaptcha_secret,
        'response' => $recaptcha_response
    );

    $recaptcha_options = array(
        'http' => array(
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($recaptcha_data)
        )
    );

    $recaptcha_context = stream_context_create($recaptcha_options);
    $recaptcha_result = file_get_contents($recaptcha_url, false, $recaptcha_context);
    $recaptcha_result = json_decode($recaptcha_result, true);

    if (!$recaptcha_result['success']) {
        $registration_error = "Пожалуйста, пройдите проверку reCAPTCHA.";
    } else {
        $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Проверка совпадения паролей
        if ($password !== $confirm_password) {
            $registration_error = "Пароли не совпадают.";
        } else if (
            strlen($password) < 8 || // Проверка на минимальную длину
            !preg_match('/[A-Z]/', $password) || // Хотя бы одна заглавная латинская буква
            !preg_match('/\d/', $password) || // Хотя бы одна цифра
            !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password) // Хотя бы один из специальных символов
        ) {
            $registration_error = "Пароль должен содержать минимум 8 символов, одну заглавную латинскую букву, одну цифру и один специальный символ (!@#$%^&*()\-_=+{};:,<.>).";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Валидация email
            $registration_error = "Некорректный email.";
        } else {
            // Проверка наличия пользователя с таким email в базе данных
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $countEmail = $stmt->fetchColumn();

            // Проверка наличия пользователя с таким username в базе данных
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $countUsername = $stmt->fetchColumn();

            if ($countEmail > 0) {
                $registration_error = "Пользователь с таким email уже зарегистрирован.";
            } else if ($countUsername > 0) {
                $registration_error = "Пользователь с таким именем уже существует.";
            } else {
                // Хеширование пароля
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Генерация токена верификации
                $verification_token = generateToken();

                // Вставка новой записи в таблицу пользователей
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, verification_token) VALUES (:username, :email, :password, :token)");
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                $stmt->bindParam(':token', $verification_token, PDO::PARAM_STR);
                $stmt->execute();

                // Получаем user_id только что зарегистрированного пользователя
                $user_id = $pdo->lastInsertId();

                // Устанавливаем user_id в сессии
                $_SESSION['user_id'] = $user_id;

                // Отправка email подтверждения
                $verification_link = "http://traffic-laws.ru/verify_email.php?token=" . $verification_token;
                $subject = "Email Verification";
                $message = "Please click the link below to verify your email address:\n\n" . $verification_link;

                send_email($email, $subject, $message);

                // Редирект на страницу успешной регистрации или другие действия
                header("Location: registration_success.php");
                exit();
            }
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
        // Получение хешированного пароля и проверенного email из базы данных
        $stmt = $pdo->prepare("SELECT user_id, password, role, email_verified FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Проверка пароля и email_verified
        if ($user && password_verify($password, $user['password'])) {
            if ($user['email_verified'] == 1) {
                // Устанавливаем user_id в сессии
                $_SESSION['user_id'] = $user['user_id'];

                // Проверяем роль пользователя и перенаправляем в зависимости от роли
                if ($user['role'] === 'admin') {
                    header("Location: admin_area.php");
                } else {
                    header("Location: personal_area.php");
                }
                exit();
            } else {
                $login_error = "Ваш email не подтвержден. Пожалуйста, проверьте ваш email.";
            }
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
    <style>
        .wrapper {
            position: relative;
            min-width: 29.375rem;
            width: 100%;
            border-radius: 12px;
            padding: 20px 20px 120px;
            background: #4070f4;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        form
            input {
            height: 50px;
            outline: none;
            border: none;
            padding: 0 15px;
            font-size: 16px;
            font-weight: 400;
            border-radius: 8px;
            background: #fff;
            color: #333333;
        }
    </style>
    <!-- Добавленный скрипт для reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
            <!-- Код для reCAPTCHA -->
            <div class="g-recaptcha" data-sitekey="6LeJzu8pAAAAALJeNX1RFVKTvsAs8jXIsf3tCBe3"></div>
            <!-- Конец кода для reCAPTCHA -->
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
