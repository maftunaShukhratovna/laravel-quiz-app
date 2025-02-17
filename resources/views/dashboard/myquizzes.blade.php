<x-dashboard.header></x-dashboard.header>
<div class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-dashboard.sidebar></x-dashboard.sidebar>
        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Navigation -->
            <x-dashboard.navbar></x-dashboard.navbar>

            <!-- Content -->
            <main class="p-6">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">My Quizzes</h2>
                    <div class="flex space-x-4">
                        <a href="/dashboard/createquiz"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                            Create New Quiz
                        </a>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                    <div class="flex flex-wrap gap-4">
                        <div class="flex-1">
                            <input type="text" id="search-input" placeholder="Search quizzes..."
                                class="w-full px-4 py-2 border rounded-lg" onkeyup="searchQuizzes()">
                        </div>
                        <select id="sort-select" class="px-4 py-2 border rounded-lg" onchange="sortQuizzes()">
                            <option value="default">Sort by</option>
                            <option value="date">Date Created</option>
                            <option value="completion">Completion Rate</option>
                            <option value="title">Title</option>
                        </select>
                    </div>
                </div>

                <!-- Quiz Grid -->
                <div id="quiz-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($quizzes as $quiz)
                    <div class="quiz-card bg-white rounded-lg shadow-sm p-6" 
                        data-title="{{ $quiz->title }}" 
                        data-date="{{ $quiz->created_at }}" 
                        data-completion="75">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $quiz->title }}</h3>
                                <p class="text-gray-500 text-sm">{{ $quiz->title }}</p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">{{ $quiz->description }}</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm text-gray-500">{{ $quiz->questions_count }} questions</span>
                            <span class="text-sm text-gray-500">{{ $quiz->time_limit }} minutes</span>
                        </div>
                        <div class="mb-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                            <span class="text-sm text-gray-500">75% Completion Rate</span>
                        </div>

                        <div class="flex gap-2 items-center">
                            <a href="{{ route('editquizzes', [$quiz]) }}"
                                class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-800 rounded-lg shadow-md transition">
                                Edit
                            </a>

                            <button
                                class="px-4 py-2 text-white bg-green-600 hover:bg-green-800 rounded-lg shadow-md transition">
                                View Results
                            </button>

                            <button
                                class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-800 rounded-lg shadow-md transition"
                                onclick="share('{{ $quiz->slug }}')">
                                Share
                            </button>
                            <form action="{{ route('deletequiz', ['quiz' => $quiz->id]) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this quiz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 text-white bg-red-600 hover:bg-red-800 rounded-lg shadow-md transition">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="mt-6 flex justify-center">
                    <div class="inline-flex items-center -space-x-px text-sm">
                        {{ $quizzes->links() }}
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<script>
async function share(slug) {
    try {
        slug = "{{ env('APP_URL') }}" + "/showquiz/" + slug;
        await navigator.clipboard.writeText(slug);
        alert('Content copied to clipboard');
    } catch (err) {
        console.error('Failed to copy', err);
    }
}

function searchQuizzes() {
    let input = document.getElementById('search-input').value.toLowerCase();
    let quizzes = document.querySelectorAll('.quiz-card');

    quizzes.forEach(quiz => {
        let title = quiz.getAttribute('data-title').toLowerCase();
        quiz.style.display = title.includes(input) ? "block" : "none";
    });
}


function sortQuizzes() {
    let sortBy = document.getElementById('sort-select').value;
    let quizList = document.getElementById('quiz-list');
    let quizzes = Array.from(quizList.getElementsByClassName('quiz-card'));

    quizzes.sort((a, b) => {
        if (sortBy === 'date') {
            return new Date(b.getAttribute('data-date')) - new Date(a.getAttribute('data-date'));
        } else if (sortBy === 'completion') {
            return b.getAttribute('data-completion') - a.getAttribute('data-completion');
        } else if (sortBy === 'title') {
            return a.getAttribute('data-title').localeCompare(b.getAttribute('data-title'));
        }
        return 0;
    });

    quizzes.forEach(quiz => quizList.appendChild(quiz));
}
</script>

<x-dashboard.footer></x-dashboard.footer>
