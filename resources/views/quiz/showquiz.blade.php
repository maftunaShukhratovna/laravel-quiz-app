<x-home.header></x-home.header>

<body class="flex flex-col min-h-screen bg-gray-100">
    <x-home.navbar></x-home.navbar>
    
    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6 space-y-8" id="questionContainer">
            
            <!-- Quiz Title va Timer -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $quiz->title }}</h1>
                    <p class="text-gray-600 mt-2">{{ $quiz->description }}</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-blue-600" id="timer">{{ $quiz->time_limit }}:00</div>
                    <div class="text-sm text-gray-500">Time Remaining</div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mb-6">
                <div class="flex justify-between mb-2">
                    <span class="text-sm text-gray-600">Savol <span id="current-question">1</span> dan <span id="total-questions">{{ count($quiz->questions) }}</span></span>
                    <span class="text-sm text-gray-600">Progress: <span id="progress">0%</span></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div id="progress-bar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                </div>
            </div>

            <!-- Savollar -->
            <form id="quiz-form" method="POST" action="{{ route('submitquiz') }}">
                @csrf
                <div id="question-container">
                    @foreach($quiz->questions as $index => $question)
                        <div class="mb-8 question" data-index="{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }}">
                            <div class="mb-4">
                                <h2 class="text-xl font-semibold text-gray-800">{{ $question->name }}</h2>
                            </div>
                            <div class="space-y-4">
                                @foreach($question->options as $option)
                                    <label class="block cursor-pointer">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" class="mr-2 leading-tight">
                                        {{ $option->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Navigation -->
                <div class="flex justify-between items-center space-x-4">
                    <button type="button" id="prev-btn" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 disabled:opacity-50" disabled>
                        Oldingi
                    </button>
                    <button type="button" id="next-btn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Keyingi
                    </button>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 text-center">
                    <button id="submit-quiz" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 hidden">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </main>

    <x-home.footer></x-home.footer>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    let currentQuestionIndex = 0;
    const totalQuestions = {{ count($quiz->questions) }};
    const questions = document.querySelectorAll('.question');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-quiz');
    const progressBar = document.getElementById('progress-bar');

    function updateProgress() {
        document.getElementById('current-question').textContent = currentQuestionIndex + 1;
        let progress = ((currentQuestionIndex + 1) / totalQuestions) * 100;
        progressBar.style.width = progress + '%';
    }

    // Agar faqat bitta savol bo'lsa, Next tugmasini yashirish va Submit tugmasini ko'rsatish
    if (totalQuestions === 1) {
        nextBtn.style.display = 'none';
        submitBtn.classList.remove('hidden');
    }

    nextBtn.addEventListener('click', function() {
        if (currentQuestionIndex < totalQuestions - 1) {
            questions[currentQuestionIndex].style.display = 'none';
            currentQuestionIndex++;
            questions[currentQuestionIndex].style.display = 'block';
            prevBtn.disabled = false;
            if (currentQuestionIndex === totalQuestions - 1) {
                nextBtn.style.display = 'none';
                submitBtn.classList.remove('hidden');
            }
        }
        updateProgress();
    });

    prevBtn.addEventListener('click', function() {
        if (currentQuestionIndex > 0) {
            questions[currentQuestionIndex].style.display = 'none';
            currentQuestionIndex--;
            questions[currentQuestionIndex].style.display = 'block';
            nextBtn.style.display = 'inline-block';
            submitBtn.classList.add('hidden');
            if (currentQuestionIndex === 0) {
                prevBtn.disabled = true;
            }
        }
        updateProgress();
    });

    document.getElementById('quiz-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Server error');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route('showresults') }}';
            } else {
                alert('Xatolik yuz berdi.');
            }
        })
        .catch(error => {
            console.error('Xato:', error);
            alert('Testni yuborishda xatolik yuz berdi.');
        });
    });
});

    </script>

