<x-home.header></x-home.header>

<body class="flex flex-col min-h-screen bg-gray-100">
    <x-home.navbar></x-home.navbar>
    
    <main class="flex-grow container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6 " id="questionContainer">
            <form method="POST" action="{{ route('storeresults', ['slug' => $quiz->slug]) }}">
                @csrf
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $quiz->title }}</h1>
                        <p class="text-gray-600 mt-2">{{ $quiz->description }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-bold text-blue-600" id="timer">{{ $quiz->time_limit }}</div>
                        <div class="text-sm text-gray-500">Time Remaining</div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600">Question <span id="current-question">1</span> of <span id="total-questions">10</span></span>
                        <span class="text-sm text-gray-600">Progress: <span id="progress">10%</span></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 10%"></div>
                    </div>
                </div>

                <!-- Question Container -->
                <div class="mb-8">
                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-800" id="question">What is the output of console.log(typeof undefined)?</h2>
                    </div>

                    <!-- Options -->
                    <div class="space-y-3" id="options">

                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between items-center">
                    <button type="button" id="prev-btn" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 disabled:opacity-50">
                        Previous
                    </button>
                    <button type="button" id="next-btn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Next
                    </button>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 text-center">
                    <button id="submit-quiz" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Submit Quiz
                    </button>
                </div>
            </form>

        </div>
    </main>
    <script>
        // Timer functionality
        document.addEventListener('DOMContentLoaded', () => {
    let questions = JSON.parse(`<?php echo $quiz->toJSON() ?>`).questions;
    let totalQuestions = questions.length;
    let currentQuestionIndex = 0;
    let timerDisplay = document.getElementById('timer');
    let progressBar = document.getElementById('progress-bar');
    let progressText = document.getElementById('progress');
    
    // Start timer using quiz time limit
    let timeLimit = parseInt(`<?php echo $quiz->time_limit; ?>`) * 60;
    startTimer(timeLimit, timerDisplay);
    
    function startTimer(duration, display) {
        let timer = duration;
        setInterval(() => {
            let minutes = Math.floor(timer / 60);
            let seconds = timer % 60;
            display.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            if (timer > 0) {
                timer--;
            } else {
                alert("Time's up!");
                document.getElementById('submit-quiz').click();
            }
        }, 1000);
    }
    
    function getQuestion(index) {
        return questions[index];
    }
    
    function displayQuestion(question) {
        document.getElementById('question').innerText = question.name;
        let optionsElement = document.getElementById('options');
        optionsElement.innerHTML = '';
        question.options.forEach(option => {
            optionsElement.innerHTML += `
                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="answer" class="h-4 w-4 text-blue-600" value="${option.id}">
                    <span class="ml-3">${option.name}</span>
                </label>
            `;
        });
        updateProgress();
    }
    
    function updateProgress() {
        let progressPercent = ((currentQuestionIndex + 1) / totalQuestions) * 100;
        progressBar.style.width = `${progressPercent}%`;
        progressText.innerText = `${Math.round(progressPercent)}%`;
        document.getElementById('current-question').innerText = currentQuestionIndex + 1;
        document.getElementById('total-questions').innerText = totalQuestions;
    }
    
    document.getElementById('next-btn').addEventListener('click', () => {
        if (currentQuestionIndex < totalQuestions - 1) {
            currentQuestionIndex++;
            displayQuestion(getQuestion(currentQuestionIndex));
        } else {
            alert('Quiz completed!');
        }
    });
    
    document.getElementById('prev-btn').addEventListener('click', () => {
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            displayQuestion(getQuestion(currentQuestionIndex));
        } else {
            alert('You are at the first question');
        }
    });
    
    displayQuestion(getQuestion(currentQuestionIndex));
});

    </script>


    <x-home.footer></x-home.footer>

   