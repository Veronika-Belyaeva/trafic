<?php
session_start();
require_once 'db_connection.php'; // Подключение к базе данных

$user_id = $_SESSION['user_id'];

// Получение статистики по билетам
$sql_tickets = "
    SELECT t.ticket_id, t.ticket_name, COUNT(ua.user_answer_id) AS errors
    FROM tickets t
    LEFT JOIN ticketquestions tq ON t.ticket_id = tq.ticket_id
    LEFT JOIN user_answers ua ON tq.question_id = ua.question_id AND ua.user_id = :user_id
    WHERE ua.is_correct = FALSE
    GROUP BY t.ticket_id, t.ticket_name
";

$stmt_tickets = $pdo->prepare($sql_tickets);
$stmt_tickets->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_tickets->execute();
$tickets_stats = $stmt_tickets->fetchAll(PDO::FETCH_ASSOC);

// Получение статистики по темам
$sql_topics = "
    SELECT tp.topic_id, tp.topic_name, COUNT(ua.user_answer_id) AS errors
    FROM topics tp
    LEFT JOIN questions q ON tp.topic_id = q.topic_id
    LEFT JOIN user_answers ua ON q.question_id = ua.question_id AND ua.user_id = :user_id
    WHERE ua.is_correct = FALSE
    GROUP BY tp.topic_id, tp.topic_name
";

$stmt_topics = $pdo->prepare($sql_topics);
$stmt_topics->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_topics->execute();
$topics_stats = $stmt_topics->fetchAll(PDO::FETCH_ASSOC);

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
  <title>Статистика</title>
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
            <rect width="90" height="75" fill="url(#pattern0_22_227)"/>
            <defs>
            <pattern id="pattern0_22_227" patternContentUnits="objectBoundingBox" width="1" height="1">
            <use xlink:href="#image0_22_227" transform="matrix(0.00925926 0 0 0.0111111 0.0833333 0)"/>
            </pattern>
            <image id="image0_22_227" width="90" height="90" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAYAAAA4qEECAAAACXBIWXMAAAsTAAALEwEAmpwYAAADDUlEQVR4nO2ZMW4TQRSG/zReQUEPblzABRBXwFyAC0CL4xtAbY6A06VIwTUSnLgBrhBBQkMRYaeh8EMjvUjIkr07O2+Xf4b3SSNF8uaf+X+/nbeeBRzHcRzHcRzHcRzHcZySqQBMAVwAWOsIfx/qZ44BQwBfAciO8UWvcRKoakL+O2yv7ASmDUK+G5OUif53lhFBn//rxebMKiLocG12VCRdfh0R9C8U1uU/99DlDwC8BbApdeuoIrr8oKM13ANwEhFwls1wGmHsTQfzP4xsgH188Z2wjDC3MJ77KYBvLUMeltzl1wBGRvO+BHBbM9933YfXus6FbhdZVXJgHNl8wvgNYJ4QeNOmt9RtJWseAPjQIuTtwI8BPI5svMcNtD8CuI/MGQO4TAi4beBNml744mda9VlX8TyxiptsKU8AvAZwBuCn7q/nut/u+/9b3bezxrqKxXhcAXiGzKv4qGEVX2qT+gTgRqvxFMArrdK5Vq11yNk0ve0ziht9DHrXsIo32hjDl1LHyDjwE/1VmP0ZRZMqft5i3tTAN3r3HJR0RrHL6LxhFXcReLjzijyj2K7isfFaRiUfby5bVPGRQRXv4qLU481VZMgvOl7PpNTjzRXZrTrQU7XU483w2XsA1zXP3bO+DpcYX2I+0rcxKcebswhf4drOOSS9VSt9QbBoebx5FeErXNuLIYtblQ2JHL0wrAk7xzcRwhg0tFonug+vcn8TAeKgUxmQdflig56Rdflig76OMPWjh/UUG7SQGWNbT7HGhGw9xRoTsvXQGRsYPb140NgftNXTiweN/UFbPb140NgftFVAHjQ86CSErBLpKpqtywuZjhlsXV7IdMxg6/JCpmMGmzEh0zGDzZiQ6ZjBZkzIdMxgMyZkOmawGRMyHTPYjAmZjhlsxoRMxww2Y0KmYwabMSHTMYPNmJDpmMFmTMh0zGAzJmQ6ZrAZEzIdM9iMCZmOGWzGhEzHDDZjQqZjBpsxIdMxg82YkOmYwWZMyHTMYDMmZDpmsBkTMh3HcRzHcRzHcRzHcRyUzh8krpHR6m8JCQAAAABJRU5ErkJggg=="/>
            </defs>
            </svg>
              
            <a href="statistics.php" class="personal-link active-link">Статистика</a>
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
              
            <a href="#" class="personal-link">Выход</a>
          </li>
        </ul>
      </div>
    </div>
    <section class="section section-personal">
      <div class="container-personal">
        <h1 class="personal-title">Статистика пользователя</h1>
        
        <h2>Статистика по билетам</h2>
        <table>
            <thead>
                <tr>
                    <th>Название билета</th>
                    <th>Количество ошибок</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets_stats as $ticket): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['ticket_name']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['errors']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Статистика по темам</h2>
        <table>
            <thead>
                <tr>
                    <th>Название темы</th>
                    <th>Количество ошибок</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topics_stats as $topic): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($topic['topic_name']); ?></td>
                        <td><?php echo htmlspecialchars($topic['errors']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
      </div>
    </section>
</body>
</html>