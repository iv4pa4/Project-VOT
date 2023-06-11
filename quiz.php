<?php

$db_host = '127.0.0.1';
$db_user = 'root';
$db_password = 'root';
$db_db = 'quiz';
$db_port = 8889;

$mysqli = new mysqli(
    $db_host,
    $db_user,
    $db_password,
    $db_db,
    $db_port
);

if ($mysqli->connect_error) {
    echo 'Errno: '.$mysqli->connect_errno;
    echo '<br>';
    echo 'Error: '.$mysqli->connect_error;
    exit();
}

// Query the database to get 5 random questions
$result = $mysqli->query("SELECT q.question, q.A, q.B, q.C, q.D, c.answer
                         FROM questions AS q
                         JOIN correct_answers AS c ON q.correct_answer_id = c.id
                         ORDER BY RAND() LIMIT 5");

$quizData = [];
while ($row = $result->fetch_assoc()) {
    $question = $row['question'];
    $options = [$row['A'], $row['B'], $row['C'], $row['D']];
    $correctAnswer = $row['answer'];

    $quizData[] = [
        'question' => $question,
        'options' => $options,
        'correctAnswer' => $correctAnswer
    ];
}

header('Content-Type: application/json');
echo json_encode($quizData);
?>
