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

require 'db_connection.php';

// Обработка запроса на обновление профиля
if (isset($_POST['update_profile'])) {
    // Получаем данные из формы
    $username = $_POST['username'];
    $phone = $_POST['phone']; // Предположим, что поле с телефоном называется "phone"
    
    // Обновляем данные в базе данных
    $stmt = $pdo->prepare("UPDATE users SET username = ?, phone = ? WHERE user_id = ?");
    $stmt->execute([$username, $phone, $user_id]);
    
    // Перенаправляем пользователя обратно на страницу профиля
    header("Location: personal_area.php");
    exit();
}

// Получаем текущие данные пользователя из базы данных
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

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
              <rect width="90" height="75" fill="url(#pattern0_19_72)"/>
              <defs>
              <pattern id="pattern0_19_72" patternContentUnits="objectBoundingBox" width="1" height="1">
              <use xlink:href="#image0_19_72" transform="matrix(0.00925926 0 0 0.0111111 0.0833333 0)"/>
              </pattern>
              <image id="image0_19_72" width="90" height="90" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAYAAAA4qEECAAAACXBIWXMAAAsTAAALEwEAmpwYAAADFUlEQVR4nO2ZMW4TQRSGJ01QKNyHNC7gAogrEC6QC0Abww2gNkfAdBQUXANIoCG5ggUJDUWETUPhD40yRRSR7M7u2/W/4/dJliLZ+ef9z2/njd+E4DiO4ziO4ziO4ziO4xQLcAd4AXwBlukV/34e31t3fEUA7AGn3MxJ/My64yyhkk9vSfLVZHtlNyVtF3WZNF5o0wG+ZiT6eN3xDhZgkZHoRRgaiHT5tG5dfofCuvy3rrs8sAW8BFZFbh3kdfntjmLYAd6Tz6TULn/Ywfq7mQ2w8y++E8gzeWS89kPge8Mk75Xc5ZfA2GjdA+BPxXo/4j6c1o1xHsXtYlCVHAH2M5tP5C8wa5rwjKYXn7TdMGSAEfCmQZKvJ/wdcD+z8cb/qeIDcDcMGS6reN4iwY0SXrPpxS9+Gqs+DLyKZy2ruCrhUf8B8Az4BPxK++tx2m9vI+7XB2HIdFDF1pwBj8LAq/htzSqepyb1GbhI1fgReJqqdJaq1pphNL3/zCgu0jHoVc0qXqXGOKqx1tg44fHX4E4oYEZRxRx43GDdtglfpadnq6QZxU1GZ3WquKOEL0OhM4rrVbxvHMu42PFmg0HMKjXGUUfxxB5R5HhzkZnkJx3HE2cQRY43F0qPKrCdpmqtxptJ5zVwXnHunvYyXFK8xATupduYxuPNlMC6TPswFe/y5B5VLk9Dh+ksnz3eTNVal7O+DLV+VNUgkz5/sJyUdBOBYqKvNI9JmpAtBn0TEYQT3RbEunzJiZ4qdfmSE32e4etnD/EUm+gsNi2eYo0hFk+xxhCLR84YRqcXT3Si69OLVTxyYGTM6vRiFY8cGBlT05EDsQRZ6ciBWIKsdMxQ6/KI6Zih1uUR0zFDrcsjpmOGmjHEdMxQM4aYjhlqxhDTMUPNGGI6ZqgZQ0zHDDVjiOmYoWYMMR0z1IwhpmOGmjHEdMxQM4aYjhlqxhDTMUPNGGI6ZqgZQ0zHDDVjiOmYoWYMMR0z1IwhpmOGmjHEdMxQM4aYjhlqxhDTMUPNGGI6ZqgZQ0zHcRzHcRzHcRzHcRwnbAD/AHS6nEKS43zVAAAAAElFTkSuQmCC"/>
              </defs>
              </svg>
              
            <a href="statistics.php" class="personal-link">Статистика</a>
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
              <rect width="90" height="75" fill="url(#pattern0_19_86)"/>
              <defs>
              <pattern id="pattern0_19_86" patternContentUnits="objectBoundingBox" width="1" height="1">
              <use xlink:href="#image0_19_86" transform="matrix(0.00925926 0 0 0.0111111 0.0833333 0)"/>
              </pattern>
              <image id="image0_19_86" width="90" height="90" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAYAAAA4qEECAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFEElEQVR4nO2dz4scRRTHK5K4IglKQJNsjD8gFxXxIIjoruhBIZA/wBy8e4k/iMkESQyIYnRzNMlJQfaqkogmCCoIBtEV/JWbmoN6WOMPUKObyZr9SDE1OJl0dXd11dS86qkPzGXoevXed3peVb2qnlEqk8lkMplMJhmAq4AngE+Bc4yXLnAKuFu1CeAG4CvksQQ8rFp0J0sUuV1im3QhnaXkxTY5OQWWkhYb+AtZ/N1KsZHHbMWHfx7YrlIDYaieT+0TG2Go//1ql9gIQ13qW3vERhjqcv/aITbCUMU+pi82wlB2P9MWG2Gocl/TFRthqGp/0xQbYah6PqcnNsJQ9f1OS2yEodx8T0dshKHc/U9DbIShmsUgX2yEoZrH8WCNEuv49iCRx/QIxT4VVj0356TR8YynTOxuOOXcHZNGV4s9qjs7rHpuTk0UKgsdhyx0JLLQkchCRyILHYksdCSy0JHIQkdCutBdYI9erZlXx7xXeX0E/+v4k4zQnYJ2HZfrI8TRaYPQmwrabXS5PkIcG9sg9HRBu80u10eIY3NbU8del+sjxLG3DUJ3+6VLh8HQq9Q5qYNhm1idhY7DR3rgzHd0HL4ApnLqiMO7wOfAWeB7YB64J+foeMwBV+TBMA6vBRc7hVoHsBp4xpw4suIZ4zCvBhVbeq0D2AR8UsdJzxhHK7bHEnza5fqGvt0B/FBXFc8YRyu2VKGBW4Gfa/i2DJwAniyxtRv4GLg41Fanoh3AQkUfh1pZ66BXifupwid9EulFlw/V3ByHzIFHLfI28/61FWKveE/9pNU66A18evVWxkn9Yy4eMd8IzAy9VyX2fNP++h2kxApwAFjlFbRdizKxv/M1nhKPBVPVrscjlr4XfQ2nwlwwNe1abCuZq3/mazwF3hvJsvhSHWYqFkQHfDuQzrKe6nnEdw2wD3gd2Gmr2pmtMNsB9l+B9W0X+ohHbDcDZ4bsnbANpsDzFh9+894wkFDrAO612NELjJs8lu7fWuzOlPz+37+WNg808WPQ+NhrHcAui50PGsa0Hvi6xL8dJW31HL2I/U18GTQ89iU48IbFztMN4llbUYS6UPYtMcv1Io67+iJR6C8tdu53tDMFvF8Ry1M1Zh9FnHbxpcjw2GsdwI8WOxsc7RytiOOFGja2WNr+7uJLkeGx1zqAfyx2phzv5jKfXqlp52pbTHV9sRmWzFrH3KxrIUXM113wmA+siPNtFnqrYyzvFNg45jIH1unK4ssvjQQeMCyZGcdYrjfHCFZMGjnqen4DuG+cg+G42N0wpnVND8iUTO/ebGJv0LBkPvQKrpkeesuriGd9DUvmAnBdMBXr5efhfcU+s77GpfNyMCWrtdCnlGzVuzW+xqVzTg9ywdQs30fUm7ZFHA7RQQocG9U+odFgFfCWpW89g7mzjT89b2NXEFWLNdhv7dW3mJTgnyksA48GCfryzdiLJYPx7aE6epx0WKmqvjmmi30lImsOhuhrcG1vK1NK5W1dZfMc+Gw5uc9C8KcCzBZOamL/CTynRXOcJ8+VzC76LAK3BBV56M7eaXYnUhkgMV/9k6Ys+5AR80rz2mD2I22HHIv4A7hLTRL0ju1WHXYMyeLEidxHf4XNQz2jZmFk6SIV6KWBl8x0KzTa5sHoj8NJBrjNzDhsOykuaBvHg82TW5y7j5hijyu6zeEgy+oJSymz5sy0nhefNse39O6KfmlRv9FFe3ONPkrQuAr3H7lIW4+dlLtFAAAAAElFTkSuQmCC"/>
              </defs>
              </svg>
              
            <a href="tickets.php" class="personal-link">Билеты</a>
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
    <section class="section-personal section-edit">
      <div class="container-personal container-edit">
        <h1 class="personal-title">Редактировать профиль</h1>
        <form action="" method="post" class="form-edit" enctype="multipart/form-data">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username" value="<?php echo $user_data['username']; ?>" required>
            
            <label for="phone">Телефон:</label>
            <input type="text" id="phone" name="phone" value="<?php echo $user_data['phone']; ?>">
            
            <label for="photo">Фото:</label>
            <input type="file" id="photo" name="photo">
            
            <div class="button-personal-link">
            <svg width="60" height="45" viewBox="0 0 90 75" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <rect width="90" height="75" fill="url(#pattern0_19_92)"/>
            <defs>
            <pattern id="pattern0_19_92" patternContentUnits="objectBoundingBox" width="1" height="1">
            <use xlink:href="#image0_19_92" transform="matrix(0.00925926 0 0 0.0111111 0.0833333 0)"/>
            </pattern>
            <image id="image0_19_92" width="90" height="90" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAYAAAA4qEECAAAACXBIWXMAAAsTAAALEwEAmpwYAAACJUlEQVR4nO3cv0ocURiG8RfTWIWkE0srwRACaS1TauUd2FoEYpEmMF8T2KuIJqm1sdXOW/AG1NhGUEL+wYaBKcRdJzuzZ77jzPf8YMpdZx6OZ85ZZ5UAAAAAAI/aK0lfJX2T9FvSpaR9SS9yn9iQ7Er6K2k85fgjaSf3CQ6BPRD4/vEh94lGiDwmtl9kYjtGJnZD7+cMzZzdALF7NIWUB0s/p9jlOntt1h8Wnc0Zey/3BUSJfa7g4azFa9qE/qWg7E4Ej9gXCsimhOg69mcFYzUxuopdfur3UoHYDFG6iP1OgViDX/NRwh1koUCsxc0rRWwiq/vYRFazkd1mzm76GkX/MGjccmSHYQkjE9sxMrEdIxPbMXL42OYYOWxsyxC5PFgni8iM5L4xpgsiD4Ixkok8CMZIJvIgGCOZyINgjGQiD4Ixkok8CIxkB0R2QGQHRHZAZAdEdrIi6a2kU/6Q6qf87t1hx8HN8XoetQ0eCfCxxHMXfq54uMXHEU8Q9WvJVzidb+gbYpH7IiLcEIvcF9AnzyWtV5uZL5LOiOxnWdJmzaaGzUhiq0wXD3udOPYZI3nSQvUPUkcdLAGLhO/Ze286GH1rzMmTPkX/Yo2HRUnfp9y8iJ3YVs36l9gJHfxno0HsBJ5K+jHDjo7Yc9qecdt81cE6O5TjmrjX1WqkXPo9yX2ifXdzL+7P6nOKrWo1gkQ+SrqVdFJNI89SvTEAAAAAQHn9A4rXWuBinqfwAAAAAElFTkSuQmCC"/>
            </defs>
            </svg>
            
            <input type="submit" value="Обновить профиль" name="update_profile" class="edit-submit">
        </div>
            
        </form>
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