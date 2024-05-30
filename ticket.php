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

require 'db_connection.php';

// Функция для получения вопросов по идентификатору билета
function getQuestionsForTicket($pdo, $ticket_id) {
    $query = "
    SELECT 
        tq.ticket_id,
        t.ticket_name,
        q.question_id,
        q.question_text,
        q.correct_answer,
        q.explanation,
        q.image_url,
        q.option1,
        q.option2,
        q.option3,
        q.option4,
        q.option5,
        tp.topic_name
    FROM 
        ticketquestions tq
    JOIN 
        tickets t ON tq.ticket_id = t.ticket_id
    JOIN 
        questions q ON tq.question_id = q.question_id
    JOIN 
        topics tp ON q.topic_id = tp.topic_id
    WHERE 
        tq.ticket_id = :ticket_id";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute(['ticket_id' => $ticket_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Получение идентификатора билета из запроса
$ticket_id = isset($_GET['ticket_id']) ? intval($_GET['ticket_id']) : 1;

// Если пользователь выбрал новый билет, сбрасываем ответы на предыдущий
if (isset($_GET['ticket_id']) && $_GET['ticket_id'] != $ticket_id) {
    unset($_SESSION['answers']);
    unset($_SESSION['correct_answers']);
}

// Получение текущего вопроса из запроса
$current_question_index = isset($_GET['question']) ? intval($_GET['question']) : 0;

// Получение вопросов для заданного билета
$questions = getQuestionsForTicket($pdo, $ticket_id);

$total_questions = count($questions);

// Если переход происходит на страницу без указания вопроса, сбрасываем ответы
if ($current_question_index === 0 && !isset($_GET['question'])) {
    unset($_SESSION['answers']);
    unset($_SESSION['correct_answers']);
}

// Инициализация массива для хранения ответов пользователя
if (!isset($_SESSION['answers'])) {
    $_SESSION['answers'] = array_fill(0, $total_questions, null);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверяем, установлен ли уже ответ для текущего вопроса
    if ($_SESSION['answers'][$current_question_index] === null) {
        $user_answer = isset($_POST['answer']) ? intval($_POST['answer']) : null;
        $_SESSION['answers'][$current_question_index] = $user_answer;

        // Проверка правильного ответа
        $correct_answer = intval($questions[$current_question_index]['correct_answer']);
        if ($user_answer !== $correct_answer) {
            $_SESSION['correct_answers'][$current_question_index] = [
                'correct_answer' => $correct_answer,
                'explanation' => $questions[$current_question_index]['explanation']
            ];
        }

        // Добавляем информацию о данном ответе в базу данных
        try {
            $stmt = $pdo->prepare("INSERT INTO user_answers (user_id, question_id, user_answer, is_correct) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $questions[$current_question_index]['question_id'], $user_answer, $user_answer === $correct_answer ? 1 : 0]);
        } catch (PDOException $e) {
            // Обработка ошибки
        }

        $current_question_index++;
    }

    // Переход к следующему вопросу, если еще остались вопросы
    if ($current_question_index < $total_questions) {
        header("Location: ticket.php?ticket_id={$ticket_id}&question={$current_question_index}");
        exit();
    }
}
// Проверяем, были ли даны ответы на все вопросы
$all_answered = !in_array
(null, $_SESSION['answers'], true);

// Если все ответы даны, подсчитываем результаты
if ($all_answered) {
    $score = 0;
    foreach ($questions as $index => $question) {
        $correct_answer = intval($question['correct_answer']);
        $user_answer = isset($_SESSION['answers'][$index]) ? intval($_SESSION['answers'][$index]) : null;
        if ($user_answer === $correct_answer) {
            $score++;
        }
    }
    $percentage = round(($score / $total_questions) * 100);
    echo "<p>Вы ответили правильно на {$score} из {$total_questions} вопросов ({$percentage}%).</p>";
    echo "<a href=\"ticket.php?ticket_id={$ticket_id}\">Пройти тест заново</a>";


}

// Вывод текущего вопроса
$current_question = $questions[$current_question_index];
$answers = $_SESSION['answers'];
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
  
  <title>Тест по билету №<?php echo htmlspecialchars($ticket_id); ?></title>
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
            <rect width="90" height="75" fill="url(#pattern0_22_229)"/>
            <defs>
            <pattern id="pattern0_22_229" patternContentUnits="objectBoundingBox" width="1" height="1">
            <use xlink:href="#image0_22_229" transform="matrix(0.00925926 0 0 0.0111111 0.0833333 0)"/>
            </pattern>
            <image id="image0_22_229" width="90" height="90" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAYAAAA4qEECAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEzUlEQVR4nO2dyYtcRRzHP4YxIyGiCGYmPTEa8GIk5CCIJBPRg0LAP0AP3r1oFI0dQhYIEZfJMcspgeBVJYpmEFQQIiGO4HpzOaiHaBbIZpwZ0yUFNTA0r+q96nrVtXR94XsZXv1e1affq+VX1T1QVFRUVFRUVJSS7gB2AOeA64AI6HngK+BRMtM64PvAcEWFbwJPk9GTHCNkkRvsHRHAFKMA+1wEIMUowL4WAUSxzDdyhS0i87aaD/9f4BkSlIjM5Ao7RtBZwo4VdHawYwadFezYQWcDOwXQWcBOBXTysFMCnTTs1EAnCztF0EnCThV0crBTBp0U7NRBJwM7B9BSTzZIsQbdgxSRueMRttzwDSYRmbuO7THBlrvrwRQarKiA0fX4ZAeTGDEHU+iGiwI6PBRRnmiCAytdB3G79NEU0MGfwvJEEx5c6ToIDzW5PnoeeF2t1jpq5Tbf8HrfalKfZEB3K8p1La/3rW4OoNdWlJu0vN63JnMA3akoN2V5vW9N5QC6W1FuV2Rdx64cQM8vS102HQxdU50jORiKjDxWQDMU0F+qgbM80fiH/S0wXroOhvJkfwJ8A/wN/Aq8CzxW+miG1ofPACvKYMhQYJ/wATuFXMcYsFudODLV06WN/T7eNuzYcx1rgbMN6+nSRu+wm9ywU1GuY3n9INoE/G4BxqWN3mHHCvoh4K8GdVsETgMvG2LtBM4At/rKyq7oOWCu5h6Hcs11TAJ/1tRJnkR60/JD7ShoNxXk7ervd9fA7rUx9Yst1zGmVm+m+syqH3MZVOuB6b6/1cGW82wniYTcA/YDt+FHJti/uAYXCfkF/OtZzb3PuwYWCa3YfGu7Ya7+tWtwkYA/9bUsXqbpmgWR7LKcJCL3oprqDaq7gD3ASeBFQ9ZuynCm+iJwD44SkfuoQ9seAH7ri3faMJge1NThUhsbBjHkOrZo4sgFxv0OS/efNXH7p3ZLklPG/zRlniCDXMermjifD9gm+Zr/YKifXA3qNKsps5cMluDvaeK8NkB7VtckoRZq3pKdmnIfkgHo7zRxHreMIwe6z2ra8kpNjGlNuZ/IINfxhybOhGWcYzXteKNBjPs0ZS+TQa7jH00cmw3U8Zo6HW4YZ5WhTd5Bi0CW/W1TrVa5EF1CaIXFB1YVQy5mnCQi9oOWbfm4IsYpyznwhKYuF3CUiNjTlm1Zo44R9NSrfmyA8xtbQw6GIpDlVGsQ3elwQEY3vXsfR4mI/QXD1xlNXfa5BhYRewG4l+FpomJfccnyx1ecJCL3OwxPM4bs3e2uwUXkvq4GOd9arzZtq+pwpI0biAR8yuM+ISr2B5p7yxnM5hx/el5oLDN8vrTXcF/nZFJq/0xhEXgeP5uxtwyD8cNt3eilCCCKhu41yL7ZdBd7DJCl36JFjRvSlLH6I5Vlcxn4dH3ykud8fCtgXYKwrwIHFDSbefKMYXax/AzHBjxpXO0Sn01ogBTq1Z9VadmnFMyVyhNqP1J3yLHKV4BHGDFtanDYsU2fH0XIS9qgvtTjG/Kcz+4iFa0E3lbTrbYBL6jZxdC/DhezNqoZh24nxcY9tRhpbZ6ca999VCV7bAFfVLmLVpbVo9SlbFMHEOW8WO6CyONbcndFWkL9USXt5TVyx2bgLNz/4QykscobAAsAAAAASUVORK5CYII="/>
            </defs>
            </svg>

              
            <a href="tickets.php" class="personal-link active-link">Билеты</a>
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
    <section class="section section-tickets">
    <h1>Тест по билету №<?php echo htmlspecialchars($ticket_id); ?></h1>
    <div class="nav">
        <?php foreach ($questions as $index => $question): ?>
            <?php
            $class = '';
            if ($index === $current_question_index) {
                $class = 'selected';
            } elseif (isset($answers[$index]) && $answers[$index] !== null) {
                $class = $answers[$index] == $question['correct_answer'] ? 'correct' : 'incorrect';
            }
            ?>
            <a href="ticket.php?ticket_id=<?php echo $ticket_id; ?>&question=<?php echo $index; ?>" class="<?php echo $class; ?>"><?php echo $index + 1; ?></a>
        <?php endforeach; ?>
    </div>
    <form method="post">
        <div class="question" id="q<?php echo $current_question_index; ?>">
            <h2 class="question-text"><?php echo htmlspecialchars($current_question['question_text']); ?></h2>
            <?php if ($current_question['image_url']): ?>
                <img src="<?php echo htmlspecialchars($current_question['image_url']); ?>" alt="Image for question">
            <?php endif; ?>
            <ul class="two-column">
                <?php foreach (range(1, 5) as $optionIndex): ?>
                    <?php $option = 'option' . $optionIndex; ?>
                    <?php if (!empty($current_question[$option])): ?>
                        <li>
                            <input type="radio" name="answer" value="<?php echo $optionIndex; ?>" id="q<?php echo $current_question_index; ?>o<?php echo $optionIndex; ?>" <?php echo (isset($answers[$current_question_index]) && $answers[$current_question_index] == $optionIndex) ? 'checked' : ''; ?> <?php echo ($answers[$current_question_index] !== null) ? 'disabled' : ''; ?>>
                            <label for="q<?php echo $current_question_index; ?>o<?php echo $optionIndex; ?>">
                                <?php echo htmlspecialchars($current_question[$option]); ?>
                            </label>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <button class="button-submit" type="submit" <?php echo ($answers[$current_question_index] !== null) ? 'disabled' : ''; ?>>Ответить</button>
    </form>
    </section> 
</body>
</html>
