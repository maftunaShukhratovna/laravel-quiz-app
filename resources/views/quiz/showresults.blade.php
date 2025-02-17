<x-home.header></x-home.header>

<body class="flex flex-col min-h-screen bg-gray-100">
    <x-home.navbar></x-home.navbar>
    
    <main class="flex-grow container mx-auto px-4 py-8">
    <div id="results-card" class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Quiz Complete!</h2>
                <h3 class="text-xl text-gray-700 mb-6">{{ $quiz->title}}</h3>

                <div class="flex justify-center space-x-12 mb-8">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-blue-600" id="final-score">{{ $correctAnswers.'/'.$totalQuestions }}</p>
                        <p class="text-gray-600">Final Score</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-blue-600" id="time-taken">{{ $time }}</p>
                        <p class="text-gray-600">Time Taken</p>
                    </div>
                </div>

                <a href="{{ route('dashboard') }}" class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Return to Dashboard
                </a>
            </div>
        </div>
    </main>
    
    <x-home.footer></x-home.footer>
