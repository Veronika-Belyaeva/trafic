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

// Обработка запроса на обновление профиля
if (isset($_POST['update_profile'])) {
    // Получаем данные из формы
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone']; // Предположим, что поле с телефоном называется "phone"
    
    // Обновляем данные в базе данных
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, phone = ? WHERE user_id = ?");
    $stmt->execute([$username, $email, $phone, $user_id]);
    
    // Перенаправляем пользователя обратно на страницу профиля
    header("Location: personal_area.php");
    exit();
}

// Получаем текущие данные пользователя из базы данных
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Редактировать профиль</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Редактировать профиль</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="username">Имя пользователя:</label>
        <input type="text" id="username" name="username" value="<?php echo $user_data['username']; ?>" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $user_data['email']; ?>" required><br><br>
        
        <label for="phone">Телефон:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $user_data['phone']; ?>"><br><br>
        
        <label for="photo">Фото:</label>
        <input type="file" id="photo" name="photo"><br><br>
        
        <input type="submit" value="Обновить профиль" name="update_profile">
    </form>
</body>
</html>
