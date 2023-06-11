<?php
// Assuming you have already established a database connection
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
    echo 'Errno: ' . $mysqli->connect_errno;
    echo '<br>';
    echo 'Error: ' . $mysqli->connect_error;
    exit();

    $query = "SELECT id, correct_answer_id FROM quiz.correct_answers";
    $result = $mysqli->query($query);

    if ($result) {
        $quizData = array();
        while ($row = $result->fetch_assoc()) {
            $quizData[] = $row;
        }
    }
// Get the submitted answers from the request
    $submittedAnswers = json_decode(file_get_contents('php://input'))->answers;

    $score = 0;
    $total = count($submittedAnswers);

// Validate and compare submitted answers with correct answers
    foreach ($submittedAnswers as $index => $submittedAnswer) {
        $questionId = $quizData[$index]['id'];
        $correctAnswerId = $quizData[$index]['correctAnswerId'];

        if ($submittedAnswer == $correctAnswerId) {
            $score++;
        }
    }

    $response = [
        'score' => $score,
        'total' => $total
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
