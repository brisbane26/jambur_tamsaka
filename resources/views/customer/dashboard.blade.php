  <link rel="icon" type="image/png" href="{{ asset('images/favicon-removebg-preview.png') }}" />
<x-admin-layout>
    <!-- Personalized Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h3 class="text-gray-700 text-3xl font-medium">Halo, {{ auth()->user()->username }}</h3>
            <p class="text-gray-600 mt-2">Selamat datang kembali di dashboard Anda</p>
        </div>
    </div>

    <div class="p-6">
        <!-- Banner Jambur -->
        <div class="relative w-full h-[320px] md:h-[380px] rounded-xl overflow-hidden mb-8 shadow-lg">
            <img 
                src="{{ asset('images/Jambur.jpg') }}" 
                alt="Jambur Tamsaka" 
                class="object-cover object-[center_32%] w-full h-full" 
            />
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center bg-black/30">
                <h1 class="font-bold text-3xl md:text-5xl text-white drop-shadow-lg mb-2">Jambur Tamsaka</h1>
                <p class="text-lg md:text-2xl text-white mb-6 drop-shadow">Solusi lengkap untuk semua kebutuhan acara Anda, dari adat hingga modern.</p>
                <a href="{{ route('paket.index') }}" class="px-8 py-3 bg-white text-red-700 font-bold rounded-full shadow hover:bg-red-100 transition duration-200 text-lg">Pesan Sekarang</a>
            </div>
        </div>

        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
            <!-- Pesanan Diproses -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Pesanan Diproses</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $pesananDiproses }}</p>
                    </div>
                </div>
            </div>

            <!-- Pesanan Selesai -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Pesanan Selesai</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $pesananSelesai }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Pengeluaran -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="overflow-x-auto">
                        <p class="text-gray-500 text-sm">Total Pengeluaran</p>
                        <p class="text-2xl font-bold text-gray-800 break-words max-w-full">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aturan Pemesanan Section -->
<section class="flex justify-center">
  <div class="w-full max-w-7xl px-4">
    <div class="relative rounded-2xl shadow-lg bg-gradient-to-br from-[#f8d7da] via-[#fff] to-[#f3eaea] border-l-8 border-[#5c1515] mx-auto" style="width:100%;">
      <div class="p-8">
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-[#5c1515] flex items-center">
          <svg class="w-8 h-8 mr-2 text-[#5c1515]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Aturan Pemesanan Jambur
        </h2>
        <ul class="list-disc pl-6 text-gray-800 text-lg space-y-2">
          <li><span class="font-semibold text-[#5c1515]">Pemesanan gedung</span> hanya dapat dilakukan minimal <span class="font-bold">3 hari setelah hari ini</span>.</li>
          <li><span class="font-semibold text-[#5c1515]">Pembatalan pesanan</span> hanya dapat dilakukan maksimal <span class="font-bold">H-2 sebelum hari acara</span>.</li>
          <li><span class="font-semibold text-[#5c1515]">Pembayaran cash (tunai)</span> wajib dilakukan <span class="font-bold">langsung di kantor Jambur.</span></li>
          <li><span class="font-semibold text-[#5c1515]">Pengembalian uang</span> dilakukan dengan <span class="font-bold">menghubungi admin.</span></li>

        </ul>
        <div class="mt-6 flex items-center bg-[#f3eaea] rounded-lg p-4">
          <svg class="w-6 h-6 text-[#a94442] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
          </svg>
          <span class="text-[#5c1515] font-medium">Untuk pembayaran cash, silakan datang ke kantor Jambur Tamsaka sesuai jam kerja.</span>
        </div>
      </div>
    </div>
  </div>
</section>
</x-admin-layout>
