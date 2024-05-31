<?php
session_start();

// Проверяем, есть ли пользователь вошедший в систему (значит, у нас есть user_id в сессии)
if (!isset($_SESSION['user_id'])) {
    // Если пользователь не вошел в систему, перенаправляем его на страницу входа
    header("Location: registration.php");
    exit();
}

require 'db_connection.php';

// Проверяем, есть ли параметр topic_id в запросе
if (!isset($_GET['topic_id'])) {
    // Если нет, перенаправляем на страницу со списком тем
    header("Location: topics.php");
    exit();
}

// Получаем параметр topic_id
$topic_id = $_GET['topic_id'];

// Функция для получения списка вопросов по теме
function getQuestionsForTopic($pdo, $topic_id) {
    $stmt = $pdo->prepare("SELECT question_id, question_text FROM questions WHERE topic_id = :topic_id");
    $stmt->bindParam(':topic_id', $topic_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Получение списка вопросов по теме
$questions = getQuestionsForTopic($pdo, $topic_id);
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
    .question-list {
      list-style: none;
      padding: 0;
    }

    .question-list li {
      margin-bottom: .625rem;
    }

    .question-item {
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 5px;
    }
  </style>
  <title>Тест по теме</title>
</head>
<body class="body-personal">
    <section class="section section-questions">
        <h1>Тест по теме</h1>
        <ul class="question-list">
            <?php foreach ($questions as $question): ?>
                <li class="question-item">
                    <?php echo htmlspecialchars($question['question_text']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</body>
</html>
