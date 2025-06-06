<header class="flex print:hidden items-center justify-between px-6 py-4 bg-white border-b-2 border-[#5C1515]">
    <div class="flex items-center">
        <!-- Sidebar Toggle Button -->
        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
    </div>

    <div class="flex items-center">

        <!-- Dropdown Profil -->
        <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center focus:outline-none">
                <!-- Foto Profil -->
                @if (auth()->user()->gambar)
                    <img src="{{ asset('storage/' . auth()->user()->gambar) }}" alt="Foto Profil"
                        class="w-10 h-10 rounded-full object-cover">
                @else
                    <img src="{{ asset('storage/profile_images/profile.jpg') }}" alt="Default Foto Profil"
                        class="w-10 h-10 rounded-full object-cover">
                @endif


                <!-- Nama Pengguna -->
                @if (auth()->check())
                    <span class="ml-2 text-gray-700 font-medium">{{ auth()->user()->username }}</span>
                @endif


                <!-- Ikon Dropdown -->
                <svg class="w-4 h-4 ml-2 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg overflow-hidden z-20">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" @click="dropdownOpen = false; $dispatch('open-logout-modal')"
                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- Modal Konfirmasi Logout -->
<div x-data="{ open: false }" x-on:open-logout-modal.window="open = true" x-show="open" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white w-96 p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Konfirmasi Logout</h2>
        <p class="mb-6 text-gray-600">Apakah Anda yakin ingin keluar dari akun ini?</p>

        <div class="flex justify-end space-x-4">
            <button @click="open = false"
                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded w-[80 px] h-10">Batal</button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Keluar</button>
            </form>
        </div>
    </div>
</div>







<!-- Notifications -->
<!-- <div x-data="{ notificationOpen: false }" class="relative">
            <button @click="notificationOpen = !notificationOpen" class="flex mx-4 text-gray-600 focus:outline-none">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 17H20L18.5951 15.5951C18.2141 15.2141 18 14.6973 18 14.1585V11C18 8.38757 16.3304 6.16509 14 5.34142V5C14 3.89543 13.1046 3 12 3C10.8954 3 10 3.89543 10 5V5.34142C7.66962 6.16509 6 8.38757 6 11V14.1585C6 14.6973 5.78595 15.2141 5.40493 15.5951L4 17H9M15 17V18C15 19.6569 13.6569 21 12 21C10.3431 21 9 19.6569 9 18V17M15 17H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div x-cloak x-show="notificationOpen" @click="notificationOpen = false" class="fixed inset-0 z-10 w-full h-full"></div>

            <div x-cloak x-show="notificationOpen" class="absolute right-0 z-10 mt-2 overflow-hidden bg-white rounded-lg shadow-xl w-80">
                <a href="#" class="flex items-center px-4 py-3 -mx-2 text-gray-600 hover:text-white hover:bg-indigo-600">
                    <img class="object-cover w-8 h-8 mx-1 rounded-full" src="https://via.placeholder.com/40" alt="avatar">
                    <p class="mx-2 text-sm">
                        <span class="font-bold">Sara Salah</span> replied on the <span class="font-bold text-indigo-400">Upload Image</span> article. 2m
                    </p>
                </a>
                <a href="#" class="flex items-center px-4 py-3 -mx-2 text-gray-600 hover:text-white hover:bg-indigo-600">
                    <img class="object-cover w-8 h-8 mx-1 rounded-full" src="https://via.placeholder.com/40" alt="avatar">
                    <p class="mx-2 text-sm">
                        <span class="font-bold">Slick Net</span> started following you. 45m
                    </p>
                </a>
                <a href="#" class="flex items-center px-4 py-3 -mx-2 text-gray-600 hover:text-white hover:bg-indigo-600">
                    <img class="object-cover w-8 h-8 mx-1 rounded-full" src="https://via.placeholder.com/40" alt="avatar">
                    <p class="mx-2 text-sm">
                        <span class="font-bold">Jane Doe</span> liked your reply on <span class="font-bold text-indigo-400">Test with TDD</span>. 1h
                    </p>
                </a>
            </div>
        </div> -->
