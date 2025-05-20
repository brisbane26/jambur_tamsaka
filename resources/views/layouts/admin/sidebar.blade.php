<div x-cloak :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>
    
<div x-cloak :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-[#5C1515]
 lg:translate-x-0 lg:static lg:inset-0">
    <div class="flex items-center justify-center mt-8">
        <div class="flex items-center">
            
            <a href="{{ route('dashboard') }}" class="flex items-center">
    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-10 mx-2" />
</a>

        </div>
    </div>

    <nav class="mt-10">
@role('admin')
<a class="flex items-center px-6 py-2 mt-4 {{ request()->is('admin/dashboard') ? 'text-gray-100' : 'text-gray-500' }} bg-gray-700 bg-opacity-25" href="{{ route('dashboard') }}">
    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
    </svg>
    <span class="mx-3">Dashboard</span>
</a>
@endrole

@role('customer')
<a 
    class="flex items-center px-6 py-2 mt-4 text-white 
        {{ request()->is('customer/dashboard') ? 'bg-[#907878]' : 'bg-[#5C1515]' }} 
        hover:bg-[#6d3d3d] transition-colors duration-200 rounded" 
    href="{{ route('dashboard') }}">

    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
    </svg>

    <span class="mx-3">Dashboard</span>
</a>


@endrole
        
        @role('admin')
        <a class="flex items-center px-6 py-2 mt-4 {{ Route::currentRouteNamed('admin.users.index') ? 'text-gray-100' : 'text-gray-500' }} hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100" href="{{ route('admin.users.index') }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
            </svg>    
            <span class="mx-3">User</span>
        </a>
        @endrole

        <a 
        class="flex items-center px-6 py-2 mt-4 text-white 
        {{ request()->is('paket*') ? 'bg-[#907878]' : 'bg-[#5C1515]' }} 
        hover:bg-[#6d3d3d] transition-colors duration-200 rounded" 
        href="{{ route('paket.index') }}">

        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
        </svg>

        <span class="mx-3">Paket</span>
        </a>

        <a 
        class="flex items-center px-6 py-2 mt-4 text-white 
        {{ request()->is('keranjang*') ? 'bg-[#907878]' : 'bg-[#5C1515]' }} 
        hover:bg-[#6d3d3d] transition-colors duration-200 rounded" 
        href="{{ route('keranjang.index') }}">

        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
        </svg>

        <span class="mx-3">Keranjang</span>
        </a>

       
        <a 
        class="flex items-center px-6 py-3 mt-2 rounded-lg transition-all duration-200 text-white
        {{ request()->routeIs('pesanan.index') ? 'bg-[#907878]' : 'bg-[#5C1515] hover:bg-[#6d3d3d]' }}" 
        href="{{ route('pesanan.index') }}">

        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
        </svg>

        <span class="mx-3 text-sm font-medium">Pesanan</span>

        @if(request()->routeIs('pesanan.index'))
        <span class="ml-auto w-1.5 h-6 bg-blue-500 rounded-full"></span>
        @endif

        </a>


        <!-- Menu Item: History -->
        <a 
        class="flex items-center px-6 py-3 mt-2 rounded-lg transition-all duration-200 text-white
        {{ request()->routeIs('pesanan.history') ? 'bg-[#907878]' : 'bg-[#5C1515] hover:bg-[#6d3d3d]' }}" 
        href="{{ route('pesanan.history') }}">

        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="mx-3 text-sm font-medium">History</span>
        @if(request()->routeIs('pesanan.history'))
        <span class="ml-auto w-1.5 h-6 bg-blue-500 rounded-full"></span>
        @endif
        </a>


        <a 
        class="flex items-center px-6 py-2 mt-4 text-white
        {{ request()->routeIs('jadwal.index') ? 'bg-[#907878]' : 'bg-[#5C1515]' }}
        hover:bg-[#6d3d3d] transition-colors duration-200 rounded"
        href="{{ route('jadwal.index') }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
        </svg>
        <span class="mx-3">Jadwal</span>
        </a>

        @role('customer')
        <a 
    class="flex items-center px-6 py-2 mt-4 text-white
        {{ request()->is('contact-us') ? 'bg-[#907878]' : 'bg-[#5C1515]' }}
        hover:bg-[#6d3d3d] transition-colors duration-200 rounded"
    href="/contact-us">

    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
    </svg>

    <span class="mx-3">Contact Us</span>
</a>

@endrole
        
    </nav>
</div>

 
<script>
    // JavaScript to toggle the dropdown
    const dropdownButton = document.getElementById('dropdown-button');
    const dropdownMenu = document.getElementById('dropdown-menu');
    let isOpen = true; // Set to true to open the dropdown by default

    // Function to toggle the dropdown state
    function toggleDropdown() {
        isOpen = !isOpen;
        dropdownMenu.classList.toggle('hidden', !isOpen);
    }

    // Set initial state
    toggleDropdown();

    dropdownButton.addEventListener('click', () => {
        toggleDropdown();
    });
</script>