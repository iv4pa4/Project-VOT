window.addEventListener('DOMContentLoaded', () => {
    fetchQuizData();
});

function fetchQuizData() {
    fetch('quiz.php')
        .then(response => response.json())
        .then(data => {
            const quizContainer = document.getElementById('quiz-container');
            data.forEach(question => {
                const questionElement = document.createElement('div');
                questionElement.classList.add('question');
                questionElement.innerHTML = `
                    <p>${question.question}</p>
                    <ul>
                        ${question.options.map(option => `<li>${option}</li>`).join('')}
                    </ul>
                `;
                quizContainer.appendChild(questionElement);
            });
        });
}

function submitQuiz() {
    const questions = document.getElementsByClassName('question');
    const answers = [];

    for (let i = 0; i < questions.length; i++) {
        const options = questions[i].getElementsByTagName('li');
        let selectedOptionIndex = -1;

        for (let j = 0; j < options.length; j++) {
            if (options[j].classList.contains('selected')) {
                selectedOptionIndex = j;
                break;
            }
        }

        answers.push(selectedOptionIndex);
    }

    fetch('submit_quiz.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ answers })
    })
        .then(response => response.json())
        .then(data => {
            const scoreContainer = document.getElementById('score-container');
            scoreContainer.textContent = `Your score: ${data.score}/${data.total}`;
        });
}

document.addEventListener('click', (event) => {
    if (event.target.tagName === 'LI') {
        const selectedOption = event.target;
        const options = selectedOption.parentNode.getElementsByTagName('li');

        for (let i = 0; i < options.length; i++) {
            options[i].classList.remove('selected');
        }

        selectedOption.classList.add('selected');
    }
});
