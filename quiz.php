<?php
// PHP code to fetch questions from the database

// Your database connection code
$db_host = '127.0.0.1';
$db_user = 'root';
$db_password = 'root';
$db_db = 'quiz';
$db_port = 8889;

global $conn;
$conn = new mysqli(
    $db_host,
    $db_user,
    $db_password,
    $db_db,
    $db_port
);

if ($conn->connect_error) {
    echo 'Errno: '.$conn->connect_errno;
    echo '<br>';
    echo 'Error: '.$conn->connect_error;
    exit();
}

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch 5 random questions from the 'questions' table
$query = "SELECT questions.*, correct_answers.answer FROM questions INNER JOIN correct_answers ON questions.correct_answer_id = correct_answers.id ORDER BY RAND() LIMIT 5";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Animal Quiz</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            font-family: 'Pacifico', cursive;
            font-size: 36px;
            margin-bottom: 30px;
            color: #007bff;
        }

        #question-container {
            text-align: center;
            margin-bottom: 20px;
        }

        #options-container {
            margin-top: 10px;
        }

        .option-btn {
            margin-right: 10px;
        }

        #result-container {
            margin-top: 20px;
        }

        #result-text {
            margin-bottom: 10px;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <script src="quiz.js"></script>
</head>
<body>
<div class="container">
    <h1>Quiz</h1>

    <div id="question-container">
        <h3 id="question-text"></h3>
        <div id="options-container">
            <button class="btn btn-primary option-btn" id="option-A"></button>
            <button class="btn btn-primary option-btn" id="option-B"></button>
            <button class="btn btn-primary option-btn" id="option-C"></button>
            <button class="btn btn-primary option-btn" id="option-D"></button>
        </div>
    </div>

    <div id="result-container">
        <h3 id="result-text"></h3>
        <button class="btn btn-primary" id="next-btn" style="display: none;">Next</button>
    </div>
</div>

<script>
    // JavaScript code to handle quiz logic
    var questions = <?php echo json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC)); ?>;
    var currentQuestionIndex = 0;
    var currentQuestion = questions[currentQuestionIndex];
    var questionTextElement = document.getElementById('question-text');
    var optionAElement = document.getElementById('option-A');
    var optionBElement = document.getElementById('option-B');
    var optionCElement = document.getElementById('option-C');
    var optionDElement = document.getElementById('option-D');
    var resultTextElement = document.getElementById('result-text');
    var nextButton = document.getElementById('next-btn');
    var score = 0;

    function displayQuestion() {
        var question = questions[currentQuestionIndex];
        questionTextElement.innerText = question.question;
        optionAElement.innerText = question.A;
        optionBElement.innerText = question.B;
        optionCElement.innerText = question.C;
        optionDElement.innerText = question.D;
    }

    function checkAnswer(selectedOption) {
        var question = questions[currentQuestionIndex];
        var selectedOptionText = selectedOption.innerText;
        var correctAnswer = question.answer;
        var resultText = '';

        if (selectedOptionText === correctAnswer) {
            score++;
        }

        resultTextElement.innerText = resultText;
        setTimeout(nextQuestion, 1000);
    }

    function nextQuestion() {
        currentQuestionIndex++;

        if (currentQuestionIndex < questions.length) {
            displayQuestion();
            resultTextElement.innerText = '';
            nextButton.style.display = 'none';
        } else {
            // End of the quiz, display the final result
            questionTextElement.innerText = 'Quiz finished! You have earned:';
            optionAElement.style.display = 'none';
            optionBElement.style.display = 'none';
            optionCElement.style.display = 'none';
            optionDElement.style.display = 'none';
            resultTextElement.innerText = ' ' + score + ' points';
            nextButton.style.display = 'none';
        }
    }

    displayQuestion();

    // Add event listeners to the option buttons
    optionAElement.addEventListener('click', function() {
        checkAnswer(optionAElement);
    });

    optionBElement.addEventListener('click', function() {
        checkAnswer(optionBElement);
    });

    optionCElement.addEventListener('click', function() {
        checkAnswer(optionCElement);
    });

    optionDElement.addEventListener('click', function() {
        checkAnswer(optionDElement);
    });
</script>
</body>
</html>
