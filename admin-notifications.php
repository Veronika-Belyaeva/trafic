<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: registration.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_SESSION['last_access']) && time() - $_SESSION['last_access'] > 900) {
    unset($_SESSION['user_id']);
    unset($_SESSION['last_access']);
    header("Location: registration.php");
    exit();
}

$_SESSION['last_access'] = time();

require 'db_connection.php';

$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

$email = $user_data['email'];
$phone = $user_data['phone'];
$photo_path = $user_data['photo'];

// Получение всех сообщений
$stmt = $pdo->prepare("SELECT * FROM messages");
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Обработка отправки сообщения
if (isset($_POST['send_message'])) {
    $message = $_POST['message'];
    $receiver_id = $_POST['receiver_id'];
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$user_id, $receiver_id, $message]);
        echo "Message sent successfully."; // Отправляем сообщение об успешной отправке
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); // Выводим ошибку, если возникла
    }
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
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
  <style>
        .container-messages {
            margin-top: 20px;
        }
        .list-group-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .text-muted {
            font-size: 0.8rem;
            margin-left: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
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
              
            <a href="admin_area.php" class="personal-link">Личный кабинет</a>
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

              
            <a href="admin-notifications.php" class="personal-link active-link">Уведомления</a>
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
        <div class="container-messages">
            <h2 class="personal-title">Сообщения</h2>
            <div id="message-list" class="list-group">
                <?php foreach ($messages as $message): ?>
                    <div class="list-group-item">
                        <p><?= htmlspecialchars($message['message']) ?></p>
                        <small class="text-muted"><?= htmlspecialchars(date('Y-m-d H:i:s', strtotime($message['timestamp']))) ?></small>
                        <small class="text-muted">From: 
                            <?php
                                $stmt = $pdo->prepare("SELECT username FROM users WHERE user_id = ?");
                                $stmt->execute([$message['sender_id']]);
                                $sender = $stmt->fetchColumn();
                                echo $sender;
                            ?>
                        </small>
                    </div>
                <?php endforeach; ?>
            </div>

            <form method="post" class="mt-3" id="message-form">
                <div class="form-group">
                    <select name="receiver_id" class="form-control">
                        <!-- Получение списка пользователей для выбора -->
                        <?php
                        $stmt = $pdo->prepare("SELECT user_id, username FROM users WHERE user_id != ?");
                        $stmt->execute([$user_id]);
                        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($users as $user) {
                            echo "<option value=\"{$user['user_id']}\">{$user['username']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="message" class="form-control" placeholder="Enter your message"></textarea>
                </div>
                <button type="submit" name="send_message" class="btn btn-primary">Send</button>
            </form>
        </div>
    </section>

    <!-- Добавление JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#message-form').submit(function(e){
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: 'scriptadmin.php',
                    type: 'POST',
                    data: formData,
                    success: function(response){
                        // Обновление содержимого сообщений без перезагрузки страницы
                        $('#message-list').load('admin-notifications.php #message-list');
                        $('#message-form')[0].reset(); // Очистка формы
                    }
                });
            });
        });
    </script>
</body>
</html>
