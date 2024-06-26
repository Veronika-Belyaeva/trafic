<?php
require 'db_connection.php';
session_start();
$user_id = $_SESSION['user_id'];

// Получение сообщений для пользователя (и отправленных, и полученных)
$stmt = $pdo->prepare("SELECT * FROM messages WHERE sender_id = ? OR receiver_id = ?");
$stmt->execute([$user_id, $user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Обработка отправки сообщения
if (isset($_POST['send_message']) && !isset($_SESSION['form_submitted'])) {
    $message = $_POST['message'];
    $receiver_id = 1; 
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$user_id, $receiver_id, $message]);
        $_SESSION['form_submitted'] = true; // Устанавливаем флаг после отправки формы
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Сброс флага при загрузке страницы
if (!isset($_POST['send_message'])) {
    unset($_SESSION['form_submitted']);
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: registration.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" class="html-personal">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@100;200;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">

    <title>User Notifications</title>
</head>
<body class="body-personal">
<div class="main-container">
    <div class="sidebar">
      <div class="wrapper-list">
        <p class="main-title">Traffic laws</p>
        <div class="personal-seporator"></div>
        <ul class="personal-list">
          <li class="personal-item">
            <svg width="55" height="40" viewBox="0 0 90 75" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <rect width="90" height="75" fill="url(#pattern0_19_97)"/>
            <defs>
            <pattern id="pattern0_19_97" patternContentUnits="objectBoundingBox" width="1" height="1">
            <use xlink:href="#image0_19_97" transform="matrix(0.0111111 0 0 0.0133333 0 -0.1)"/>
            </pattern>
            <image id="image0_19_97" width="90" height="90" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAYAAAA4qEECAAAACXBIWXMAAAsTAAALEwEAmpwYAAACfElEQVR4nO3cPWsUURTG8QPKqoWirYpdCNhp40cwna02wcrKl9LWLhH9AlYWCtZa6RfwpTFWIlgpmpRiAioo/mVkkXUT3dnMnXPPvfP8ymV27jkPszc5uWHNRERERGQCsB+4CawDG8Bq89rkNdIRcBR4znYvgRNd7y/2O+RTwDv+7SNwRmF1e5LPA1+Y7RtwUWHPCdgz3oPndQfYO+96gwQcBB6ye4+Bw7n7CA1YBN7QXXOPxRbrjSZ+k5ne95tP1MhqAywBn0inudfSjDVnbU+rVhPgEvCd9H4A1/+z7vSTPG3DagDsA+7SvwfAgR3Wn8kqHkL6sm24afMmq3wI6ctfw02bN1ipgAsth5C+/Blu2lxshQ4ht4njVpuLrMAh5BEFslIAC8BrCmUlAM4mHkLcWXTAtfHAUDSLynEIcWERZRhCemfRAKeB91TGIgGWga/Uabnkk5DS5Du5AQ6VOoTs0hPgiHfIRQ8hHbwFTpZ6ElKaTeBc3yFXMYQk8BO40UfAzSHm/RQVVuZe0gNcYCV3R4GtpAy6OZmQna0raB8fUgY9hKEkxNYxGoetLSTHfzMxMJYLA2MK2oeCdqKgnShoJwraiYJ2oqCHGrTuk5iCdqKgnShoJwraiYJ2oqCdKGgnCtqJgnaioJ0oaCfR/ohDsHqSidYYwepJJlpjBKsnmWiNEayeZKI1RrB6konWGMHqSSZaYwSrJ5lojRGsnmSiNUawepKJ1hjB6kkmWmMEqyeZaI0RrJ5kgC2G43POoF8wHM9yBn2V4bic+2t9XlG/texfcQwcrzzsNeCYRTB+sq80+1glPyC3gKfNdpH9SRYRERERERERsXh+Ae8I60XtDjVYAAAAAElFTkSuQmCC"/>
            </defs>
            </svg>

              
            <a href="personal_area.php" class="personal-link">Личный кабинет</a>
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
            <rect width="90" height="75" fill="url(#pattern0_22_228)"/>
            <defs>
            <pattern id="pattern0_22_228" patternContentUnits="objectBoundingBox" width="1" height="1">
            <use xlink:href="#image0_22_228" transform="matrix(0.00925926 0 0 0.0111111 0.0833333 0)"/>
            </pattern>
            <image id="image0_22_228" width="90" height="90" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAYAAAA4qEECAAAACXBIWXMAAAsTAAALEwEAmpwYAAAC9UlEQVR4nO2dPWhVQRBGD1qJUSy0UKNYWBtBiNpaKajYCDaCrZrERuxELKxsNDb+YWFpJ/hXWwgW1iqWChIbNQkkIdGVhRUUJO/uvS9vZsx3YLrHZPe8ucPu4+4GhBBCCCGEEEIIIcROYBx4AbwDZoH0n8ZsmWOe6xiwYxBf/3bgDrDkQEAyih/AI2DXSkk+Acw4mGhyEtPA8X5LvlC+SevJJWeRnUz0s5IlmWVld67sYbULmraRbV1EP3DweKYgca/LEm41ry5SZSyVDlDNhIPBp2Bxvo3o5w4GnoLF0zaiPzgYeAoWeQdZjTYnVIvOzqqxro4UNCQaica6ClXR2ItT68Beqno0Eo11xamisZek1oG9QPVofIU2LEg01lUYvqLngBvAKLC+xH7gJjAfOI8r0R+BPcvkHymfiZbHlei5HpP6c3LzgfK4E50fz6ZMBsrjTnTugU05ECiPO9FDFX9nKFCe0KI3BsrjTrS3R360T3ncic7r0qbcCpTHnej5slTqxV5gIVAed6JTWfyP9JjUp4B53In+XUmTpe8NlThYHs+FwHnciV6NUY31gFPQkGgkGusqVEVjL06tA3up6tFINNYVp4rGXpJaB/YC1aPxFdqw4FS0DgtRLfl7G9HvHTyGKVi8bSNaBzoZzIHOMQcVkoLF2Tai891BOnRPY8mLbQ/dZ+47qJIUJG7T8bKqaQeTSAFWG1vpyBG1EHpd9XOMPpHv7tC9SvxTcr7/r6/kC5rURvirXRxlhdhS3vpZdNAXk2EVP+xHT27CcLnW5lnZDf3P2/WZMse8GTnXZQk3CK63nOQl64FH400Lya+AtdYDj8TmFsvDb8Bu64FH42Kl5Px+3CHrQUdjA/C5QvJP4LT1oCNyt0LyHHDSesARGa+Q/KW8PisqWANcKW2gieQnXW+qXY3sA142FDwFnLEecEQuN/zhaaqsRPKBd9GCr8vIXSwt4hSwTna7ca38K418KOc18Bi4ChwGNkmuEEIIIQQD4Bfkt/CJcjMacwAAAABJRU5ErkJggg=="/>
            </defs>
            </svg>

              
            <a href="notifications.php" class="personal-link active-link">Уведомления</a>
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
    <section class="section section-personal">
        <h2 class="personal-title">User Messages</h2>
        <div class="container-messages" id="message-container">
            <?php foreach ($messages as $message): ?>
                <div class="message <?= ($message['sender_id'] == $user_id) ? 'message-user' : 'message-admin' ?>">
                    <div class="message-body"><?= htmlspecialchars($message['message']) ?></div>
                    <div class="message-time"><?= htmlspecialchars(date('Y-m-d H:i:s', strtotime($message['timestamp']))) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <form method="post" class="mt-3" id="message-form">
            <textarea name="message" class="message-input" placeholder="Введите ваше сообщение" id="message-input"></textarea>
            <button type="submit" name="send_message" class="message-submit">Отправить</button>
        </form>
    </section>
    <script>

        function confirmLogout() {
            return confirm("Вы уверены, что хотите выйти?");
        }
    </script>
    <!-- Добавьте JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        // Обработка отправки формы через AJAX
        $('#message-form').submit(function(e){
            e.preventDefault(); // Предотвращаем стандартное поведение формы

            var formData = $(this).serialize(); // Получаем данные формы

            $.ajax({
                url: 'script.php', // URL для обработки запроса
                type: 'POST', // Метод запроса
                data: formData, // Данные для отправки
                success: function(response){
                    // Обновление содержимого сообщений без перезагрузки страницы
                    $('#message-container').load('notifications.php #message-container');
                    $('#message-input').val(''); // Очистка поля ввода
                }
            });
        });
    });
</script>


</body>
</html>
