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
$all_answered = !in_array(null, $_SESSION['answers'], true);

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
    include_once('main-container.php');
    $percentage = round(($score / $total_questions) * 100);
?>
     <div class="wrapper-result">
        <p>Вы ответили правильно на <?php echo $score; ?> из <?php echo $total_questions; ?> вопросов (<?php echo $percentage; ?>%).</p>
        <a href="ticket.php?ticket_id=<?php echo $ticket_id; ?>">Пройти тест заново</a>
    </div>
<?php
    exit(); // Выход из скрипта после вывода результата
}




// Вывод текущего вопроса
$current_question = $questions[$current_question_index];
$answers = $_SESSION['answers'];

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
  
  <title>Тест по билету №<?php echo htmlspecialchars($ticket_id); ?></title>
</head>
<body class="body-personal">
<?php include_once('main-container.php'); ?>
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
        <?php if (!$all_answered && isset($_SESSION['correct_answers'][$current_question_index])): ?>
    <div class="correct-answer">
        <?php
        // Проверяем существование ключа в массиве перед попыткой доступа к нему
        if (array_key_exists($current_question_index, $_SESSION['correct_answers'])) {
            // Проверяем, не является ли значение null перед его использованием
            if ($_SESSION['correct_answers'][$current_question_index] !== null) {
                // Выводим верный ответ и объяснение
                echo "<p>Верный ответ: " . $questions[$current_question_index]['option' . $questions[$current_question_index]['correct_answer']] . "</p>";
                echo "<p>Объяснение: " . $questions[$current_question_index]['explanation'] . "</p>";
            }
        }
                ?>
            </div>
        <?php endif; ?>


        <button class="button-submit" type="submit" <?php echo ($answers[$current_question_index] !== null) ? 'disabled' : ''; ?>>Ответить</button>
    </form>
    </section> 
    <script>
    // Функция для отображения окна подтверждения при попытке выхода из учетной записи
    function confirmLogout() {
      return confirm("Вы уверены, что хотите выйти?");
    }
  </script>
</body>
</html>
