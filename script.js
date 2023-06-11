// Fetch questions from the server
fetch('fetch_questions.php')
    .then(response => response.json())
    .then(data => {
        const questions = data.questions;
        let currentQuestionIndex = 0;
        let score = 0;

        const questionElement = document.getElementById('question');
        const options = Array.from(document.getElementsByClassName('option'));
        const resultContainer = document.getElementById('result-container');
        const scoreElement = document.getElementById('score');

        function showQuestion() {
            const question = questions[currentQuestionIndex];
            questionElement.textContent = question.question;

            // Set options text
            options.forEach((option, index) => {
                option.textContent = question[String.fromCharCode(65 + index)];
                option.addEventListener('click', checkAnswer);
            });
        }

        function checkAnswer() {
            const question = questions[currentQuestionIndex];
            const selectedAnswer = question.correct_answer_id;
            const selectedOption = this.textContent;

            if (selectedOption === selectedAnswer) {
                score++;
            }

            currentQuestionIndex++;

            if (currentQuestionIndex < questions.length) {
                showQuestion();
            } else {
                showResult();
            }
        }

        function showResult() {
            const totalScore = `${score} out of ${questions.length}`;
            questionElement.textContent = '';
            options.forEach(option => {
                option.style.display = 'none';
            });
            resultContainer.style.display = 'block';
            scoreElement.textContent = `Your score: ${totalScore}`;
        }

        // Display the first question
        showQuestion();
    });
