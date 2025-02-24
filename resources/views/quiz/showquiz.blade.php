<x-home.header></x-home.header>

<body class="flex flex-col min-h-screen bg-gray-100">
    <x-home.navbar></x-home.navbar>

    <main class="flex-grow container mx-auto px-4 py-8">
        <div id="start-card" class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <label class="block text-gray-600 text-sm mb-1">Quiz Title:</label>
                <h2 class="text-2xl font-bold text-gray-800 mb-4" id="title">
                    {{ $quiz->title }}
                </h2>

                <label class="block text-gray-600 text-sm mb-1">Description:</label>
                <p class="text-xl text-gray-700 mb-6" id="description">
                    {{ $quiz->description }}
                </p>

                <div class="flex justify-center space-x-12 mb-8">
                    <div class="text-center">
                        <label class="block text-gray-600 text-sm mb-1">Time Limit:</label>
                        <p class="text-3xl font-bold text-blue-600" id="time-taken">
                            {{ $quiz->time_limit }} minutes
                        </p>
                    </div>
                </div>


                <form action="{{ route('startquiz', ['slug' => $quiz->slug]) }}" method="POST">
                    @csrf
                    <button id="start-btn"
                        class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Start Quiz
                    </button>
                </form>
            </div>
        </div>
    </main>

    <x-home.footer></x-home.footer>