<header class="bg-white shadow-sm">
    <div class="h-16 flex items-center justify-between px-4">
        <button class="md:hidden text-gray-600" onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
                <!-- Profil rasmini bosganda fayl yuklash oynasi ochiladi -->
                <label for="avatar-upload" class="cursor-pointer">
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                         onerror="this.src='https://via.placeholder.com/40'"
                         alt="Profile" class="w-10 h-10 rounded-full">
                </label>
                <span class="text-gray-700 font-medium"> {{ auth()->user()->name }} </span>
            </div>
        </div>
    </div>
</header>

<script>
function uploadAvatar() {
    document.getElementById('avatar-form').submit();
}
</script>


<!-- Fayl yuklash inputi -->
<form id="avatar-form" action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
    @csrf
    <input type="file" name="avatar" id="avatar-upload" accept="image/*" class="hidden" onchange="uploadAvatar()">
</form>
