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
require 'db_connection.php';

// Получаем данные пользователя из базы данных, включая фото
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Здесь вы можете получить другие данные пользователя, такие как телефон и т.д.
$email = $user_data['email'];
$phone = $user_data['phone']; // Предположим, что поле с телефоном называется "phone"
$photo_path = $user_data['photo']; // Предположим, что фото пользователя хранится в поле 'photo'


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
<html class="html-personal" lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@100;200;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/style.css">
  <title>Личный кабинет</title>
</head>
<body class="body-personal">
  <div class="main-container">
    <div class="sidebar">
      <div class="wrapper-list">
        <p class="main-title">Traffic laws</p>
        <div class="personal-seporator"></div>
        <ul class="personal-list">
          <li class="personal-item">
            <svg width="60" height="45" viewBox="0 0 90 75" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <rect width="90" height="75" fill="url(#pattern0_22_230)"/>
              <defs>
              <pattern id="pattern0_22_230" patternContentUnits="objectBoundingBox" width="1" height="1">
              <use xlink:href="#image0_22_230" transform="matrix(0.00925926 0 0 0.0111111 0.0833333 0)"/>
              </pattern>
              <image id="image0_22_230" width="90" height="90" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAYAAAA4qEECAAAACXBIWXMAAAsTAAALEwEAmpwYAAACoUlEQVR4nO2cPWsUURSGH4hELSLaqtiJYBcbf4J2ttqIlZUfpa2dEf0DVhYK1lrpH/CjMVYSsFJMLIMGVFA8MjALcVw2ye6de9+5+z7wNlkyc87D5WTPTlgwxhhjjDH/cgC4C2wAX4CV9mcmIUeB10B08hY4YdNpWAY+jpE8yjpw1rJn4yLwfYLkUX4CVyx77yy0Mzj2mAfAvinuN5csAU+nkDzKc+Bw6SbUOQWszSB5lLX2WjuxuO2dTHfur7SvV8d5YDOB5FE222tOYqfx1LxeFVeBXwklj/IbuDXhvt2T3E3zfr0K9gMPexDczRPg4Jj77+Z3q11CoqeMW26qF728wxLSV7rLTdWiL+1yCekr25ebKkUvAPcLCu7mXo2il4BnAnKnyWA4CbwXEFa16HOJl5Cw6P+52S4MMfAw70tIzLPo3EtIzKPoM8AnATFRs+jLwA8BKdFDmt4G+yQkBpaiT24ODXgJiSnyAjiSW/LQl5CYMh+A00N9EhIDyzfgQt+Sa1lCYsb8AW73Ibh5SPlYoMEQy6PUD3DvCDQVomncJGNdoKEQTfOA16LpX/TnlKLnYSkJhdGx2Mr2CKHMfzPFnKUYpRsPiy4vJXyiKS7MowPteEZj0cVPoU805cV5dFBe6iBmtK+TGIvOhEVnwqIzYdGZsOhMWHQmLDoTFp0Ji86ERWfCojOh9iFOiNWTDLXGQqyeZKg1FmL1JEOtsRCrJxlqjYVYPclQayzE6kmGWmMhVk8y1BoLsXqSodZYiNWTDLXGQqyeZKg1FmL1JEOtsRCrJxlbGZoLkXwtKfqNgIDIlFclRd8QEBCZcq301/q8E5AQPWdV4SuOj1cuexU4hgjNyb7ezrEa/kBuAS/bcVH8JBtjjDHGGGOMMcYgxl+nBIeTfLmCTQAAAABJRU5ErkJggg=="/>
              </defs>
              </svg>
              
            <a href="personal_area.php" class="personal-link active-link">Личный кабинет</a>
          </li>
          <li class="personal-item">
            <svg width="60" height="45" viewBox="0 0 90 75" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <rect width="90" height="75" fill="url(#pattern0_19_74)"/>
              <defs>
              <pattern id="pattern0_19_74" patternContentUnits="objectBoundingBox" width="1" height="1">
              <use xlink:href="#image0_19_74" transform="matrix(0.00925926 0 0 0.0111111 0.0833333 0)"/>
              </pattern>
              <image id="image0_19_74" width="90" height="90" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAYAAAA4qEECAAAACXBIWXMAAAsTAAALEwEAmpwYAAAC/klEQVR4nO2dPWgUQRiGD60kp1hooSZiYW0E4dTWSkGDjWAj2GpMbMROxMLKJomNf1hY2glGrS0EC2sjlhEkNhoTSEKMjyxOYE30dn7OmZ3b94Hrju/95sne3OyxM2m1hBBCCCGEEEIIIRoOsB8YA14BM8Ai/cuiGWMx1ivAUAzB+4D7wA+ayxrwFDjwvySfBRZSj7JGfAdGei35qvlLij8pnIz38kqW5H+zFnxlA4OaLqynkb0hoh/b5QjgYcgSrsmrC1cKV4M+osedo8Soj+iX8ubMtI/oj+45jWfGR7RuTtxZ8BEtPJDoSEh0JCQ6EhIdCYmOhERHQqIjIdGRkOhISHQkJLrPRS8BE0AHGDCvo8AksJxxnVqJngUOdak/bN6TW51aiV7qNqgNg1vOqE4lVRl/Cw1hwiFnKqM6ldjmlAND6DjkHMuoTiW2OeXAENoOOe2M6lRim1MODMFlYDsyqlOJbU45MIS6feQ7PapTiW1OOTCESYecuxnVqcQ2pxwYQrFEGrbIOAysZFSnEhfH66GhzHYbHL8H9SnDOrUTvX4lTZl5r21ex83HcyXjOrUT3ThaEh0HiY6EREdCoiMh0ZGQ6EhIdCQkOhISHQmJjoRE11i0Ngu5M+8j+oNHUNN57yNaGzojbegsjrURblzyET2kTfdOrHptujeyH7llNZp7XpJLh1UVh36I7swDe7xFG9mnNIVUHvVzJkjyhrM7dK7SZgonYz2RXJI9omlk03RxuqeSS7J3m6fji2/YJl/FT4LnZIfTw0aBF8XdUJ/fri+YMU4Dl72XcDEA7ngO8nrq3rMCeOch+Q2wNXXv2QDs8lgefgMOpu49K4BrjpKL5+NOpO47K4DtwGcHyT+BC6n7zg7ggYPkJeBc6p6zw5yYbsuX4vHZ1D1nBbAFuGmmARueB51U20SAI8BrS8FzwMXUPWcHcMPyh6c5sxIZSN1zlgBfu8hdNVPEeWBb6l6zBrht/pVGsSnnLfAMuAWcBHam7k8IIYQQotUIfgFjSEUeALB02wAAAABJRU5ErkJggg=="/>
              </defs>
              </svg>
              
            <a href="notifications.php" class="personal-link">Уведомления</a>
          </li>
          <li class="personal-item">
            <svg width="60" height="45" viewBox="0 0 90 75" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <rect width="90" height="75" fill="url(#pattern0_19_75)"/>
              <defs>
              <pattern id="pattern0_19_75" patternContentUnits="objectBoundingBox" width="1" height="1">
              <use xlink:href="#image0_19_75" transform="matrix(0.00925926 0 0 0.0111111 0.0833333 0)"/>
              </pattern>
              <image id="image0_19_75" width="90" height="90" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAYAAAA4qEECAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFF0lEQVR4nO2cbaifYxzHL2bnEDaNFqZW3ogXkoyJVqJW4oUQr4S98bR5wVCsPK0dvGAiEWppUkpplDfaC+Ug2n+sPK5mdpxhjJ3TPJ+Prv7Xyd9x7uu+Hs917f//fep+df737/pe3/s61+PvvpUSBEEQBEEQBEEQBEEQBEEQagEYBtYA7wOT5noPWK3/pioCOB44E7gMuAl4CNgEvA18BhwA9gDrgfmqFoBTgO00sw1YUomJB/FjvaoB4MgWk3vNHq7MRBe+UTUA3O4h+raA+EPAo8APFELVAN0+2ZXRgPiPURhVA8CEh+aJgPh7KYw6BI3+JSB+cdSAdB3FUTVAd+6cczAsjqpoodJxnN4NBcQvjqpswdLJsWChAlRN0G3Zerk9agZIfb2ru4uQltwTtzixxhwLPA7sCyh70uxjrMm9j0EFxIi/GNiVSEdHdx1J3f2v1uKEiD4aeBqYSqylk6tlUwG+glcAX2XUs3qgjQaOMn3x35n1jA6s0cBy4NM50jMxcEYD84ANwF8Ocb4GtgDfRuo5MIhGa5Pb0APiM8CCnvtOBi4H7gd+89TTGUSj2+bGO4GLLPcfEzAzeWEQjR5ruEcPiBv1NK+lcucE6LmlAqP1eHQlcAZwI7CbBNjEjczye32mdoFj5a4L0HNeSoN7tLiiDwhOmHHvQuD52HVD2znbiGnZWsCDeprnUTmXPr6XP33i+4A7T1pirIxp3TnqNS3s9RoGQo3HoewdykJM61a5AL5IMRDSHVRfARZHaHk2hdExrTtUu0uehsv8u3UgNFNFzXfAFRG7jJtN9xRtdEjrDtHtIkInqCQZCIGnPGKMmXFlyNIATrVcCwPqutJloRbio0vh16YaCPHvgjQjWSpmb1iTJYx+wNOY7Q1xdCsLYSxLxex13lzC6Fc9jXmxIc7Nh5DRz5UweoenMbc2xHkp0OgNWSpmT5y09tO5Cta5wT4sb4izwCz396UYDDPVdYk5QLaSq/Afa1gRTgMcpqeP5nR9p+VapTwArgf2u1TSJ66PgHc8jD4YMq3y1HNX4gWLbsVvetQxm9H6SZMzzcsHczCRxGjgas//2KxGD5udPld2ZBHyr57oQTSkFfeSs3IXeh7mrsioxZVPZnuxx6cvbiJX3UKWzy9n1OGDXgOcaO47LaYV95KiEvoQ92zLZs4uRy2/x+zQtWgMwbsftpFi2qRXROPAcZbUsSlHPfdECWrWWZzYCug3nVxOJzY56tGzg3lRomYvvzgx4u+dEUsPfOc2/HaRxws7l8aY2lB+cXwFzzcJ43c2xPsAOLzh3qscNW1JZXBP2cWZKWgxsMxMyteaWcMbJtt+3LGvbUwZAF5zuF+fzCztO6N1CzSmNuVx+LJ/eno0S4VPAn6a63enqYCQTfo2vrS1SOAGhxh7U+7AUQHKdAmp+Fjn3jlU/C2HWNf0m9GpuoytrrtwwFKHt2W39pvRs6V+EbBsHU78RYMp4PR+Mro39csXfXyzLmSRQXcQbjuZ2Ng3RpeE7qbNrxZ9P7dlrTqWU5w0jsWZcF+LxlUJyihOGrfiTDgC+MiicVuCMoqjagA4C/jDonNZZHz5MMo0wCO+CTauyKd+/v8u4+c5Mo/MzEo/yO8phKoJ4PyGc8Y9iR+ozum7xLz+cbfJn9av732oPyWUwefdqjaAJypJ8Ur5bbyHVW3QPR5bZ9LA5jzFK/HDGDddVj2fzBQEQRAEQRAEQRAEQRAEQVBF+AeQn4g/dSb6zQAAAABJRU5ErkJggg=="/>
              </defs>
              </svg>
              <form method="post" action="" onsubmit="return confirmLogout();">
                <button type="submit" name="logout" class="personal-link">Выход</button>
              </form>
          </li>
        </ul>
      </div>
    </div>
    <section class="section section-personal">
      <div class="container-personal">
        <h1 class="personal-title">Информация об администраторе</h1>
        <?php
          // Проверяем, есть ли фото у пользователя
          if (!empty($photo_path)) {
              echo '<div class="avatar"><img src="' . $photo_path . '" alt="Avatar"></div>';
          }
        ?>
        <?php
          // Выводим имя пользователя
          echo '<p class="info">Имя администратора</p>';
          echo '<div class="info-personal">';
          echo '<p class="info-text">' . $user_data['username'] . '</p>';
          echo '</div>';
        ?>
        <p class="info">Телефон</p>
        <div class="info-personal">
          <p class="info-text">
            <?php if (!empty($phone)): 
            echo $phone; 
            endif; ?>
          </p>
        </div>
        <p class="info">Почта</p>
        <div class="info-personal">
          <p class="info-text"><?php echo $email; ?></p>
        </div>
      </div>
    </section>
  </div>
  <script>
    // Функция для отображения окна подтверждения при попытке выхода из учетной записи
    function confirmLogout() {
      return confirm("Вы уверены, что хотите выйти?");
    }
  </script>
</body>
</html>
