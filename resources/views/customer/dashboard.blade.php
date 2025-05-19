<x-admin-layout>
    
<!-- Personalized Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Selamat Datang {{ auth()->user()->username }}</h1>
                <p class="text-gray-600 mt-2">Selamat datang kembali di dashboard Anda</p>
            </div>
        </div>

    <div class="p-6">
        <!-- Banner Jambur & Pesan Sekarang -->
        <div class="relative w-full h-[260px] md:h-[320px] rounded-xl overflow-hidden mb-8 shadow-lg">
            <img src="{{ asset('images/Jambur.jpg') }}" alt="Jambur Tamsaka" class="object-cover object-center w-full h-full mix-blend-overlay opacity-90" />
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                <h1 class="font-bold text-3xl md:text-5xl text-white drop-shadow-lg mb-2">Jambur Tamsaka</h1>
                <p class="text-lg md:text-2xl text-white mb-6 drop-shadow">Tempat kawin terbaik</p>
                <a href="{{ route('paket.index') }}" class="px-8 py-3 bg-white text-red-700 font-bold rounded-full shadow hover:bg-red-100 transition duration-200 text-lg">Pesan Sekarang</a>
            </div>
        </div>

        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Active Orders -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Pesanan Diproses</p>
                        <p class="text-2xl font-bold text-gray-800">3</p>
                    </div>
                </div>
            </div>
            
            <!-- Completed Orders -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Pesanan Selesai</p>
                        <p class="text-2xl font-bold text-gray-800">15</p>
                    </div>
                </div>
            </div>
            
            <!-- Total Spending -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Pengeluaran</p>
                        <p class="text-2xl font-bold text-gray-800">Rp 3.250.000</p>
                    </div>
                </div>
            </div>
            
            <!-- Loyalty Points -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-amber-500">
                <div class="flex items-center">
                    <div class="bg-amber-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Review (kalo ada)</p>
                        <p class="text-2xl font-bold text-gray-800">1.250</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Orders -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Pesanan Saat Ini</h3>
                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</a>
            </div>
            <div class="divide-y divide-gray-200">
                <!-- Order 1 -->
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="mb-4 md:mb-0">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-lg mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Gedung</h4>
                                <p class="text-sm text-gray-600">#ORD-2023-015</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-6">
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">Dalam Proses</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Estimasi Selesai</p>
                            <p class="font-medium">Hari Ini, 16:00</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total</p>
                            <p class="font-medium">Rp 150.000</p>
                        </div>
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium self-end md:self-center">Detail</a>
                    </div>
                </div>
            </div>
        </div>
            
            <!-- Recommended Services -->
            <div class="lg:col-span-2">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Rekomendasi Untuk Anda</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Service 1 -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        <div class="relative">
                            <img src="{{ asset('images/jus.jpg') }}" alt="Cuci Mobil Premium" class="w-full h-40 object-cover">
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Populer</span>
                        </div>
                        <div class="p-4">
                            <h4 class="font-semibold text-lg mb-1">Jus</h4>
                            <p class="text-gray-600 text-sm mb-3">Jeruk</p>
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-bold text-blue-600">Rp 150.000</span>
                                    <span class="text-gray-400 text-sm line-through ml-2">Rp 175.000</span>
                                </div>
                                <a href="#" class="text-sm bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">Pesan</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Service 2 -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        <div class="relative">
                            <img src="{{ asset('images/saksang.jpg') }}" alt="Detailing Interior" class="w-full h-40 object-cover">
                            <span class="absolute top-2 right-2 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">Baru</span>
                        </div>
                        <div class="p-4">
                            <h4 class="font-semibold text-lg mb-1">Saksang</h4>
                            <p class="text-gray-600 text-sm mb-3">Enak</p>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-blue-600">Rp 300.000</span>
                                <a href="#" class="text-sm bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">Pesan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in as csustomer!") }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>