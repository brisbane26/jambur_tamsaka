<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Jambur Tamsaka</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html {
      scroll-behavior: smooth;
    }
  </style>
</head>
<body class="bg-gray-100 text-gray-800">

  <!-- nav bar section - Changed to red theme -->
<nav class="flex flex-wrap items-center justify-between p-3" style="background-color:#5c1515;color:white;">
    <div class="text-xl font-bold">Tamsaka</div>
    <div class="flex md:hidden">
        <button id="hamburger">
          <img class="toggle block" src="https://img.icons8.com/fluent-systems-regular/2x/menu-squared-2.png" width="40" height="40" />
          <img class="toggle hidden" src="https://img.icons8.com/fluent-systems-regular/2x/close-window.png" width="40" height="40" />
        </button>
    </div>
    <div class="toggle hidden w-full md:w-auto md:flex text-right text-bold mt-5 md:mt-0 md:border-none">
        <a href="#home" class="block md:inline-block hover:text-[#a94442] px-3 py-3 md:border-none">Home</a>
        <a href="#services" class="block md:inline-block hover:text-[#a94442] px-3 py-3 md:border-none">Paket</a>
        <a href="#aboutus" class="block md:inline-block hover:text-[#a94442] px-3 py-3 md:border-none">About us</a>
        <a href="#gallery" class="block md:inline-block hover:text-[#a94442] px-3 py-3 md:border-none">Gallery</a>
        <a href="#contactUs" class="block md:inline-block hover:text-[#a94442] px-3 py-3 md:border-none">Visit Us</a>
    </div>

    <div class="toggle w-full text-end hidden md:flex md:w-auto px-2 py-2 md:rounded">
    <a href="{{ route('login') }}">
        <div class="flex justify-end">
            <div class="flex items-center h-10 w-30 rounded-md bg-white" style="color:#5c1515;font-weight:bold;" p-2 hover:bg-[#f8d7da] transition">
                <!-- Heroicon name: calendar -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 3v2.25M17.25 3v2.25M3 8.25h18M4.5 7.5v11.25A2.25 2.25 0 006.75 21h10.5A2.25 2.25 0 0019.5 18.75V7.5M16.5 12.75h.008v.008H16.5v-.008z" />
                </svg>
                Book now
            </div>
        </div>
    </a>
</div>
</div>

</nav>

<!-- hero section - Updated with red theme -->
<div class="flex justify-center mt-8" id="home">
    <div class="w-full max-w-7xl px-4">
        <div class="relative rounded-2xl overflow-hidden shadow-lg bg-white">
            <img src="{{ asset('images/Jambur.jpg') }}" alt="Background Image" class="object-cover object-center w-full h-64 md:h-80" />
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center bg-black/30">
                <h1 class="font-bold text-4xl md:text-5xl text-white drop-shadow-lg mb-2" style="text-shadow:0 2px 8px #000">Jambur Tamsaka</h1>
                <p class="italic text-lg md:text-xl text-gray-100 mb-2" style="text-shadow:0 2px 8px #000">Solusi lengkap untuk semua kebutuhan acara Anda, dari adat hingga modern.</p>
            </div>
        </div>
    </div>
</div>

<!-- about us - Adjusted for red theme -->
<section class="bg-gray-50" id="aboutus">
  <div class="container mx-auto py-16 px-4 sm:px-6 lg:px-8 max-w-7xl">
    <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-6">
      <!-- Teks -->
      <div class="max-w-xl">
        <h2 class="text-3xl font-bold mb-8 text-left" style="color:#5c1515;">About Us</h2>
        <p class="mt-4 text-gray-700 text-lg">
          Jambur Tamsaka adalah tempat yang disediakan untuk menyelenggarakan 
          berbagai acara penting dalam kehidupan masyarakat, seperti pesta pernikahan, 
          upacara adat, dan acara duka cita. Kami hadir untuk mendukung kelancaran 
          setiap momen berharga dengan menyediakan berbagai kebutuhan acara, termasuk 
          layanan katering, sound system, dan lainnya. 
          Dengan mengutamakan kenyamanan, ketertiban, dan sentuhan budaya, 
          Jambur Tamsaka siap menjadi bagian dari setiap langkah penting dalam hidup Anda.
        </p>
      </div>
      <!-- Gambar -->
      <div class="mt-12 md:mt-0">
        <img src="https://images.unsplash.com/photo-1531973576160-7125cd663d86" alt="About Us Image" class="object-cover rounded-lg shadow-md w-full border-4 border-red-100">
      </div>
    </div>
  </div>
</section>

<!-- Paket section - Updated with red accents -->
<section class="py-10 bg-gray-50" id="services">
  <div class="container mx-auto px-8 max-w-7xl">
    <h2 class="text-3xl font-bold mb-6 text-center" style="color:#5c1515;">Paket</h2>

<form method="GET" action="#services" class="flex justify-end mb-8">
  <label for="kategori" class="flex items-center text-lg font-medium text-gray-700 space-x-2">
    <span>Pilih Kategori:</span>
    <select name="kategori" id="kategori" onchange="this.form.submit()" class="px-3 py-1 rounded border border-[#5c1515] shadow-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#5c1515] w-40">
      <option value="">Semua</option>
      @foreach ($kategoris as $kategori)
        <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
          {{ $kategori->nama_kategori }}
        </option>
      @endforeach
    </select>
  </label>
</form>


    <div id="all-content" class="grid grid-cols-1 md:grid-cols-3 gap-8 justify-center">
  @foreach ($pakets as $paket)
    <div class="category-item {{ strtolower($paket->kategori->nama_kategori) }} bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
      <img src="{{ asset('images/' . $paket->gambar) }}" class="w-full h-56 object-cover" alt="{{ $paket->nama_paket }}">
      <div class="p-6 text-center">
        <h4 class="text-xl font-bold mb-2" style="color:#5c1515;">{{ $paket->nama_paket }}</h4>
        <p class="font-semibold text-base" style="color:#5c1515;">Rp {{ number_format($paket->harga_jual, 0, ',', '.') }}</p>
        <p class="text-gray-700 text-base mt-2">{{ $paket->deskripsi }}</p>
      </div>
    </div>
  @endforeach
</div>
  </div>
</section>

<!-- Gallery section - Updated with red theme -->
<section class="py-16 bg-white" id="gallery">
  <div class="container mx-auto px-8 max-w-7xl">
    <div class="text-center mb-12">
      <h2 class="text-3xl font-bold mb-4" style="color:#5c1515;">Our Gallery</h2>
      <p class="text-lg text-gray-600 max-w-2xl mx-auto">Explore beautiful moments captured at Jambur Tamsaka</p>
    </div>

    <!-- Gallery Filter -->
    <div class="flex justify-center mb-8">
      <div class="inline-flex rounded-md shadow-sm" role="group">
        <button type="button" class="gallery-filter px-4 py-2 text-sm font-medium rounded-l-lg border" style="background-color:#5c1515;color:white;border-color:#5c1515;" data-filter="all">
          All
        </button>
        <button type="button" class="gallery-filter px-4 py-2 text-sm font-medium border-t border-b" style="color:#5c1515;border-color:#5c1515;" data-filter="wedding">
          Wedding
        </button>
        <button type="button" class="gallery-filter px-4 py-2 text-sm font-medium border" style="color:#5c1515;border-color:#5c1515;" data-filter="event">
          Events
        </button>
        <button type="button" class="gallery-filter px-4 py-2 text-sm font-medium rounded-r-lg border" style="color:#5c1515;border-color:#5c1515;" data-filter="venue">
          Venue
        </button>
      </div>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
      <!-- Wedding Card 1 -->
      <div class="gallery-item wedding bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80" 
             alt="Wedding at Jambur Tamsaka" 
             class="w-full h-56 object-cover">
        <div class="p-6 text-center">
          <h4 class="text-xl font-bold mb-2" style="color:#5c1515;">Wedding Celebration</h4>
          <p class="text-gray-700 text-base">Beautiful wedding moments at our venue</p>
        </div>
      </div>

      <!-- Wedding Card 2 -->
      <div class="gallery-item wedding bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="https://images.unsplash.com/photo-1523438885200-e635ba2c371e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80" 
             alt="Wedding Decoration" 
             class="w-full h-56 object-cover">
        <div class="p-6 text-center">
          <h4 class="text-xl font-bold mb-2" style="color:#5c1515;">Wedding Decoration</h4>
          <p class="text-gray-700 text-base">Elegant decoration setup</p>
        </div>
      </div>

      <!-- Event Card 1 -->
      <div class="gallery-item event bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80" 
             alt="Cultural Event" 
             class="w-full h-56 object-cover">
        <div class="p-6 text-center">
          <h4 class="text-xl font-bold mb-2" style="color:#5c1515;">Cultural Event</h4>
          <p class="text-gray-700 text-base">Traditional performances</p>
        </div>
      </div>

      <!-- Event Card 2 -->
      <div class="gallery-item event bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="https://images.unsplash.com/photo-1492681290082-e932832941e6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80" 
             alt="Music Performance" 
             class="w-full h-56 object-cover">
        <div class="p-6 text-center">
          <h4 class="text-xl font-bold mb-2" style="color:#5c1515;">Music Performance</h4>
          <p class="text-gray-700 text-base">Live music entertainment</p>
        </div>
      </div>

      <!-- Venue Card 1 -->
      <div class="gallery-item venue bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="https://images.unsplash.com/photo-1575425186775-b8de9a427e67?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80" 
             alt="Venue Overview" 
             class="w-full h-56 object-cover">
        <div class="p-6 text-center">
          <h4 class="text-xl font-bold mb-2" style="color:#5c1515;">Venue Overview</h4>
          <p class="text-gray-700 text-base">Our beautiful venue space</p>
        </div>
      </div>

      <!-- Venue Card 2 -->
      <div class="gallery-item venue bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80" 
             alt="Outdoor Area" 
             class="w-full h-56 object-cover">
        <div class="p-6 text-center">
          <h4 class="text-xl font-bold mb-2" style="color:#5c1515;">Outdoor Area</h4>
          <p class="text-gray-700 text-base">Spacious outdoor setting</p>
        </div>
      </div>
    </div>

    <!-- View More Button -->
    <div class="text-center mt-10">
      <button class="px-6 py-3 font-bold rounded-full hover:bg-[#7a2323] transition duration-200" style="background-color:#5c1515;color:white;">
        View More Photos
      </button>
    </div>
  </div>
</section>

<!-- Facility Section - Updated with red theme -->
<div class="mt-20 px-4 bg-gray-50 py-12">
  <h2 class="text-3xl font-bold text-center mb-10" style="color:#5c1515;">Facility</h2>
  
  <!-- Baris pertama: 4 card ukuran sama seperti gallery -->
  <div class="flex justify-center flex-wrap gap-8 mb-8 max-w-screen-xl mx-auto">
    <div class="bg-white w-64 h-64 rounded-lg shadow-md hover:shadow-lg transition p-6 text-center border-t-4" style="border-color:#5c1515;">
      <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4" style="background-color:#f3eaea;">
        <svg class="w-8 h-8" style="color:#5c1515;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
      </div>
      <h3 class="text-xl font-bold mb-2" style="color:#5c1515;">Spacious Hall</h3>
      <p class="text-gray-700">Large area for various events</p>
    </div>
    
    <div class="bg-white w-64 h-64 rounded-lg shadow-md hover:shadow-lg transition p-6 text-center border-t-4" style="border-color:#5c1515;">
      <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4" style="background-color:#f3eaea;">
        <svg class="w-8 h-8" style="color:#5c1515;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
      </div>
      <h3 class="text-xl font-bold mb-2" style="color:#5c1515;">Complete Equipment</h3>
      <p class="text-gray-700">Tables, chairs, and sound system</p>
    </div>
    
    <div class="bg-white w-64 h-64 rounded-lg shadow-md hover:shadow-lg transition p-6 text-center border-t-4" style="border-color:#5c1515;">
      <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4" style="background-color:#f3eaea;">
        <svg class="w-8 h-8" style="color:#5c1515;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <h3 class="text-xl font-bold mb-2" style="color:#5c1515;">Affordable Price</h3>
      <p class="text-gray-700">Competitive packages</p>
    </div>
    
    <div class="bg-white w-64 h-64 rounded-lg shadow-md hover:shadow-lg transition p-6 text-center border-t-4" style="border-color:#5c1515;">
      <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4" style="background-color:#f3eaea;">
        <svg class="w-8 h-8" style="color:#5c1515;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
        </svg>
      </div>
      <h3 class="text-xl font-bold mb-2" style="color:#5c1515;">Decoration</h3>
      <p class="text-gray-700">Beautiful setup options</p>
    </div>
  </div>
</div>

<!-- Visit us section - Updated with red theme -->
<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:py-20 lg:px-8">
        <div class="max-w-2xl lg:max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-extrabold text-red-700" id="contactUs">Visit Our Location</h2>
            <p class="mt-3 text-lg text-gray-600">Let us serve you the best</p>
        </div>
        <div class="mt-8 lg:mt-20">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <div class="max-w-full mx-auto rounded-lg overflow-hidden bg-white shadow-md p-6">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-xl font-bold text-red-700">Contact</h3>
                            <p class="mt-2 font-bold text-gray-600"><a href="tel:+123" class="hover:text-red-700">Phone: +61 811 1111 1111</a></p>
                        </div>
                        <div class="px-6 py-4">
                            <h3 class="text-xl font-bold text-red-700">Our Address</h3>
                            <p class="mt-2 text-gray-700">Jl. Jamin Ginting No.KM.11, RW.5, Kemenangan Tani, Kec. Medan Tuntungan, Kota Medan, Sumatera Utara 20135</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg overflow-hidden order-none sm:order-first shadow-md">
                    <iframe width="100%" height="100%" style="min-height: 400px; border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps/embed/v1/place?q=jambur%20tamsaka&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- footer - Updated with red theme -->
<section>
    <footer class="py-8" style="background-color:#5c1515;color:white;">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Brand & Copyright -->
            <div class="flex flex-col items-center md:items-start">
                <span class="text-lg font-bold tracking-wide">Jambur Tamsaka</span>
                <span class="text-xs text-red-200 mt-1">&copy; 2024 All Rights Reserved</span>
            </div>
            <!-- Navigation -->
            <ul class="flex flex-wrap gap-6 justify-center md:justify-end mt-4 md:mt-0">
                <li>
                    <a href="#contactUs" class="hover:text-[#a94442] transition">Contact</a>
                </li>
                <li>
                    <a href="/privacy" class="hover:text-[#a94442] transition">Privacy Policy</a>
                </li>
                <li>
                    <a href="#aboutus" class="hover:text-[#a94442] transition">About Us</a>
                </li>
                <li>
                    <a href="#gallery" class="hover:text-[#a94442] transition">Gallery</a>
                </li>
            </ul>
            <!-- Social Media -->
            <div class="flex gap-4 mt-4 md:mt-0">
                <a href="https://wa.me/6281234567890" target="_blank" class="hover:text-red-200 transition" title="WhatsApp">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.52 3.48A12.07 12.07 0 0 0 12 0C5.37 0 0 5.37 0 12c0 2.11.55 4.14 1.6 5.93L0 24l6.22-1.63A11.94 11.94 0 0 0 12 24c6.63 0 12-5.37 12-12 0-3.19-1.24-6.19-3.48-8.52zM12 22c-1.77 0-3.5-.46-5.01-1.33l-.36-.21-3.69.97.99-3.59-.23-.37A9.93 9.93 0 0 1 2 12c0-5.52 4.48-10 10-10s10 4.48 10 10-4.48 10-10 10zm5.2-7.8c-.28-.14-1.65-.81-1.9-.9-.25-.09-.43-.14-.61.14-.18.28-.7.9-.86 1.09-.16.18-.32.2-.6.07-.28-.14-1.18-.44-2.25-1.41-.83-.74-1.39-1.65-1.55-1.93-.16-.28-.02-.43.12-.57.13-.13.28-.34.42-.51.14-.17.18-.29.27-.48.09-.19.05-.36-.02-.5-.07-.14-.61-1.47-.83-2.01-.22-.53-.44-.46-.61-.47-.16-.01-.36-.01-.56-.01-.2 0-.52.07-.8.34-.28.28-1.06 1.04-1.06 2.54 0 1.5 1.09 2.95 1.24 3.16.15.21 2.14 3.27 5.19 4.45.73.28 1.3.45 1.74.58.73.23 1.39.2 1.91.12.58-.09 1.65-.67 1.89-1.32.23-.65.23-1.2.16-1.32-.07-.12-.25-.19-.53-.33z"/>
                    </svg>
                </a>
                <a href="mailto:info@jamburtamsaka.com" class="hover:text-red-200 transition" title="Email">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 12l-4-4-4 4m8 0v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-6m16-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v4"/>
                    </svg>
                </a>
                <a href="https://instagram.com/" target="_blank" class="hover:text-red-200 transition" title="Instagram">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5A4.25 4.25 0 0 0 7.75 20.5h8.5A4.25 4.25 0 0 0 20.5 16.25v-8.5A4.25 4.25 0 0 0 16.25 3.5zm4.25 3.25a5.25 5.25 0 1 1 0 10.5 5.25 5.25 0 0 1 0-10.5zm0 1.5a3.75 3.75 0 1 0 0 7.5 3.75 3.75 0 0 0 0-7.5zm5.25.75a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                </a>
            </div>
        </div>
    </footer>
</section>

<script>
    document.getElementById("hamburger").onclick = function toggleMenu() {
        const navToggle = document.getElementsByClassName("toggle");
        for (let i = 0; i < navToggle.length; i++) {
          navToggle.item(i).classList.toggle("hidden");
        }
      };

    // Gallery filter functionality
    const galleryFilters = document.querySelectorAll('.gallery-filter');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    galleryFilters.forEach(filter => {
      filter.addEventListener('click', function() {
        const filterValue = this.getAttribute('data-filter');
        
        // Update active button
        galleryFilters.forEach(f => {
          if (f === this) {
            f.classList.add('bg-red-700', 'text-white');
            f.classList.remove('text-red-700', 'hover:bg-red-50');
          } else {
            f.classList.remove('bg-red-700', 'text-white');
            f.classList.add('text-red-700', 'hover:bg-red-50');
          }
        });
        
        // Filter items
        galleryItems.forEach(item => {
          if (filterValue === 'all' || item.classList.contains(filterValue)) {
            item.classList.remove('hidden');
          } else {
            item.classList.add('hidden');
          }
        });
      });
    });
</script>
</body>
</html>