<?php
// Подключение к базе данных
$host = 'localhost'; // Хост базы данных
$port = '5432'; // Порт базы данных
$dbname = 'Tickets';
$username = 'postgres';
$password = 'cgQ1wpi';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$username;password=$password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Обработка регистрации
if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Проверка наличия пользователя с таким email в базе данных
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $registration_error = "Пользователь с таким email уже зарегистрирован.";
    } else {
        // Хеширование пароля
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Вставка новой записи в таблицу пользователей
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);

        // Получаем user_id только что зарегистрированного пользователя
        $user_id = $pdo->lastInsertId();

        // Устанавливаем user_id в сессии
        session_start();
        $_SESSION['user_id'] = $user_id;

        // Редирект на страницу успешной регистрации или другие действия
        header("Location: personal_area.php");
        exit();
    }
}

// Обработка входа пользователя
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Получение хешированного пароля из базы данных
    $stmt = $pdo->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $hashed_password = $stmt->fetchColumn();

    // Проверка пароля
    if ($hashed_password && password_verify($password, $hashed_password)) {
        // Проверка наличия пользователя с таким email в базе данных
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user_id = $stmt->fetchColumn();

        // Устанавливаем user_id в сессии
        session_start();
        $_SESSION['user_id'] = $user_id;

        // Редирект на страницу личного кабинета
        header("Location: personal_area.php");
        exit();
    } else {
        // Неверный email или пароль
        $login_error = "Неверный email или пароль.";
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
    <div class="wrapper">
        <div class="form signup">
            <header>Signup</header>
            <?php if (isset($registration_error)): ?>
                <p class="error-message"><?php echo $registration_error; ?></p>
            <?php endif; ?>
            <form action="" method="post">
                <input type="text" placeholder="Полное имя" id="username" name="username" required>
                <input type="email" placeholder="Ваш email" id="email" name="email" required>
                <input type="password" placeholder="Пароль" id="password" name="password" required>
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
                <p class="error-message"><?php echo $login_error; ?></p>
            <?php endif; ?>
            <form action="" method="post">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <a href="#">Забыли пароль?</a>
                <input type="submit" value="Вход" name="login"/>
            </form>
        </div>

        <script src="js/main.js"></script>
    </div>
</section>

</body>
</html>
