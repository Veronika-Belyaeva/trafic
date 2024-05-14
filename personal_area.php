<?php
session_start();

// Проверяем, есть ли пользователь вошедший в систему (значит, у нас есть user_id в сессии)
if (!isset($_SESSION['user_id'])) {
    // Если пользователь не вошел в систему, перенаправляем его на страницу входа
    header("Location: registration.php");
    exit();
}

// Получаем user_id из сессии
$user_id = $_SESSION['user_id'];

// Проверяем время последнего доступа пользователя
if (isset($_SESSION['last_access']) && time() - $_SESSION['last_access'] > 900) { // 900 секунд = 15 минут
    // Если прошло более 15 минут с момента последнего доступа, разлогиниваем пользователя
    unset($_SESSION['user_id']);
    unset($_SESSION['last_access']);
    header("Location: registration.php"); // перенаправляем на страницу входа
    exit();
}

// Обновляем время последнего доступа пользователя
$_SESSION['last_access'] = time();

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

// Получаем данные пользователя из базы данных, включая фото
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Здесь вы можете получить другие данные пользователя, такие как телефон и т.д.
$email = $user_data['email'];
$phone = $user_data['phone']; // Предположим, что поле с телефоном называется "phone"
$photo_path = $user_data['photo']; // Предположим, что фото пользователя хранится в поле 'photo'

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Добро пожаловать, <?php echo $user_data['username']; ?>!</h1>
    <p>Email: <?php echo $email; ?></p>
    <?php if (!empty($phone)): ?>
        <p>Телефон: <?php echo $phone; ?></p>
    <?php endif; ?>
    <?php if (!empty($photo_path)): ?>
        <!-- Отображаем фото пользователя, если оно есть -->
        <img src="<?php echo $photo_path; ?>" alt="Фото пользователя">
    <?php endif; ?>

    <!-- Кнопка для перехода в окно редактирования -->
    <a href="edit_profile.php">Редактировать профиль</a>
</body>
</html>