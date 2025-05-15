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

  <!-- nav bar section -->
<nav class="flex flex-wrap items-center justify-between p-3 bg-[#e8e8e5]">
    <div class="text-xl">Tamsaka</div>
    <div class="flex md:hidden">
        <button id="hamburger">https://image1.jdomni.in/banner/13062021/0A/52/CC/1AF5FC422867D96E06C4B7BD69_1623557926542.png
          <img class="toggle block" src="https://img.icons8.com/fluent-systems-regular/2x/menu-squared-2.png" width="40" height="40" />
          <img class="toggle hidden" src="https://img.icons8.com/fluent-systems-regular/2x/close-window.png" width="40" height="40" />
        </button>
    </div>
    <div class=" toggle hidden w-full md:w-auto md:flex text-right text-bold mt-5 md:mt-0 md:border-none">
        <a href="#home" class="block md:inline-block hover:text-blue-500 px-3 py-3 md:border-none">Home
        </a>
        <a href="#services" class="block md:inline-block hover:text-blue-500 px-3 py-3 md:border-none">Paket
        </a>
        <a href="#aboutus" class="block md:inline-block hover:text-blue-500 px-3 py-3 md:border-none">About us
        </a>
        <a href="#gallery" class="block md:inline-block hover:text-blue-500 px-3 py-3 md:border-none">Gallery
        </a>
        <a href="#contactUs" class="block md:inline-block hover:text-blue-500 px-3 py-3 md:border-none">Visit Us
        </a>
    </div>

    <div class="toggle w-full text-end hidden md:flex md:w-auto px-2 py-2 md:rounded">
    <a href="{{ route('login') }}">
        <div class="flex justify-end">
            <div class="flex items-center h-10 w-30 rounded-md bg-[#c8a876] text-white font-medium p-2">
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
<!-- hero seciton -->
<div class="relative w-full h-[320px]" id="home">
    <div class="absolute inset-0 opacity-70">
        <img src="https://image1.jdomni.in/banner/13062021/0A/52/CC/1AF5FC422867D96E06C4B7BD69_1623557926542.png" alt="Background Image" class="object-cover object-center w-full h-full" />

    </div>
    <div class="absolute inset-9 flex flex-col md:flex-row items-center justify-between">
        <div class="md:w-1/2 mb-4 md:mb-0">
            <h1 class="text-grey-700 font-medium text-4xl md:text-5xl leading-tight mb-2">Jambur Tamsaka</h1>
            <p class="font-regular text-xl mb-8 mt-4">Tempat kawin terbaik</p>
            <a href="#contactUs"
                class="px-6 py-3 bg-[#c8a876] text-white font-medium rounded-full hover:bg-[#c09858]  transition duration-200">Contact
                Us</a>
        </div>
    </div>
</div>



<!-- about us -->
<section class="bg-gray-100" id="aboutus">
  <div class="container mx-auto py-16 px-4 sm:px-6 lg:px-8 max-w-7xl">
    <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-6">
      <!-- Teks -->
      <div class="max-w-xl">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-left">About Us</h2>
        <p class="mt-4 text-black-600 text-lg">
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
        <img src="https://images.unsplash.com/photo-1531973576160-7125cd663d86" alt="About Us Image" class="object-cover rounded-lg shadow-md w-full">
      </div>
    </div>
  </div>
</section>
<section class="py-10 bg-gray-100" id="services">
  <div class="container mx-auto px-8 max-w-7xl">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Paket</h2>

   <div class="mb-8 flex justify-end">
  <label for="kategori" class="flex items-center text-lg font-medium text-gray-700 space-x-2">
    <span>Pilih Kategori:</span>
    <select id="kategori" class="px-3 py-1 rounded border border-gray-300 shadow-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 w-40">
      <option value="all">Semua</option>
      @foreach ($kategoris as $kategori)
        <option value="{{ strtolower($kategori->nama_kategori) }}">{{ $kategori->nama_kategori }}</option>
      @endforeach
    </select>
  </label>
</div>

    <div id="all-content" class="grid grid-cols-1 md:grid-cols-3 gap-8 justify-center">
  @foreach ($pakets as $paket)
    <div class="category-item {{ strtolower($paket->kategori->nama_kategori) }} bg-white rounded-lg shadow-md overflow-hidden">
      <img src="{{ $paket->gambar_url }}" class="w-full h-56 object-cover" alt="{{ $paket->nama_paket }}">
      <div class="p-6 text-center">
        <h4 class="text-xl font-medium text-gray-800 mb-2">{{ $paket->nama_paket }}</h4>
        <p class="text-gray-700 text-base">Rp {{ number_format($paket->harga_jual, 0, ',', '.') }}</p>
        <p class="text-gray-700 text-base mt-2">{{ $paket->deskripsi }}</p>
      </div>
    </div>
  @endforeach
</div>

  </div>
</section>

<script>
  const select = document.getElementById('kategori');
  const allItems = document.querySelectorAll('.category-item');

  select.addEventListener('change', function () {
    const selected = this.value;

    allItems.forEach(item => {
      item.classList.remove('hidden');
      if (selected !== 'all' && !item.classList.contains(selected)) {
        item.classList.add('hidden');
      }
    });
  });
</script>




<section class="py-16 bg-white" id="gallery">
  <div class="container mx-auto px-4">
    <div class="text-center mb-12">
      <h2 class="text-3xl font-bold text-gray-800 mb-4">Our Gallery</h2>
      <p class="text-lg text-gray-600 max-w-2xl mx-auto">Explore beautiful moments captured at Jambur Tamsaka</p>
    </div>

    <!-- Gallery Filter -->
    <div class="flex justify-center mb-8">
      <div class="inline-flex rounded-md shadow-sm" role="group">
        <button type="button" class="gallery-filter px-4 py-2 text-sm font-medium rounded-l-lg border border-[#c8a876] bg-[#c8a876] text-white" data-filter="all">
          All
        </button>
        <button type="button" class="gallery-filter px-4 py-2 text-sm font-medium border-t border-b border-[#c8a876] text-[#c8a876]" data-filter="wedding">
          Wedding
        </button>
        <button type="button" class="gallery-filter px-4 py-2 text-sm font-medium border border-[#c8a876] text-[#c8a876]" data-filter="event">
          Events
        </button>
        <button type="button" class="gallery-filter px-4 py-2 text-sm font-medium rounded-r-lg border border-[#c8a876] text-[#c8a876]" data-filter="venue">
          Venue
        </button>
      </div>
    </div>
    

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 gallery-container">
      <!-- Wedding Items -->
      <div class="gallery-item wedding relative group overflow-hidden rounded-lg shadow-md">
        <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80" 
             alt="Wedding at Jambur Tamsaka" 
             class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
          <span class="text-white text-lg font-medium">Wedding Celebration</span>
        </div>
      </div>

      <div class="gallery-item wedding relative group overflow-hidden rounded-lg shadow-md">
        <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80" 
             alt="Wedding Decoration" 
             class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
          <span class="text-white text-lg font-medium">Wedding Decoration</span>
        </div>
      </div>

      <!-- Event Items -->
      <div class="gallery-item event relative group overflow-hidden rounded-lg shadow-md">
        <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80" 
             alt="Cultural Event" 
             class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
          <span class="text-white text-lg font-medium">Cultural Event</span>
        </div>
      </div>

      <div class="gallery-item event relative group overflow-hidden rounded-lg shadow-md">
        <img src="https://images.unsplash.com/photo-1492681290082-e932832941e6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80" 
             alt="Music Performance" 
             class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
          <span class="text-white text-lg font-medium">Music Performance</span>
        </div>
      </div>

      <!-- Venue Items -->
      <div class="gallery-item venue relative group overflow-hidden rounded-lg shadow-md">
        <img src="https://images.unsplash.com/photo-1575425186775-b8de9a427e67?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80" 
             alt="Venue Overview" 
             class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
          <span class="text-white text-lg font-medium">Venue Overview</span>
        </div>
      </div>

      <div class="gallery-item venue relative group overflow-hidden rounded-lg shadow-md">
        <img src="https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80" 
             alt="Outdoor Area" 
             class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
          <span class="text-white text-lg font-medium">Outdoor Area</span>
        </div>
      </div>
    </div>

    <!-- View More Button -->
    <div class="text-center mt-10">
      <button class="px-6 py-3 bg-[#c8a876] text-white font-medium rounded-full hover:bg-[#b8996a] transition duration-200">
        View More Photos
      </button>
    </div>
  </div>
</section>

<!-- Facility Section -->
<div class="mt-20 px-4">
  <h2 class="text-2xl font-bold text-center mb-10">Facility</h2>
  
  <!-- Baris pertama: 4 card ukuran sama seperti gallery -->
  <div class="flex justify-center flex-wrap gap-6 mb-6 max-w-screen-xl mx-auto">
    <div class="bg-gray-300 w-64 h-64 rounded-lg shadow-md"></div>
    <div class="bg-gray-300 w-64 h-64 rounded-lg shadow-md"></div>
    <div class="bg-gray-300 w-64 h-64 rounded-lg shadow-md"></div>
    <div class="bg-gray-300 w-64 h-64 rounded-lg shadow-md"></div>
  </div>
  
  <!-- Baris kedua: 3 card ukuran sama seperti gallery, diposisikan di tengah -->
  <div class="flex justify-center gap-6 max-w-screen-xl mx-auto">
    <div class="bg-gray-300 w-64 h-64 rounded-lg shadow-md"></div>
    <div class="bg-gray-300 w-64 h-64 rounded-lg shadow-md"></div>
    <div class="bg-gray-300 w-64 h-64 rounded-lg shadow-md"></div>
  </div>
</div>




<!-- Visit us section -->
<section class="bg-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:py-20 lg:px-8">
        <div class="max-w-2xl lg:max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-extrabold text-gray-900" id="contactUs">Visit Our Location</h2>
            <p class="mt-3 text-lg text-gray-500">Let us serve you the best</p>
        </div>
        <div class="mt-8 lg:mt-20">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <div class="max-w-full mx-auto rounded-lg overflow-hidden">
                        <div class="border-t border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-bold text-gray-900">Contact</h3>
                            <p class="mt-1 font-bold text-gray-600"><a href="tel:+123">Phone: +61 811 1111 1111</a></p>
                        </div>
                        <div class="px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900">Our Address</h3>
                            <p class="mt-1 text-gray-600">Jl. Jamin Ginting No.KM.11, RW.5, Kemenangan Tani, Kec. Medan Tuntungan, Kota Medan, Sumatera Utara 20135</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg overflow-hidden order-none sm:order-first">
                    <iframe width="500px" height="450px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps/embed/v1/place?q=jambur%20tamsaka&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8"></iframe>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- footer -->
<section>
    <footer class="bg-[#e8e8e5] text-gray-800 py-8 border-t border-gray-300">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Brand & Copyright -->
            <div class="flex flex-col items-center md:items-start">
                <span class="text-lg font-bold tracking-wide text-gray-900">Jambur Tamsaka</span>
                <span class="text-xs text-gray-600 mt-1">&copy; 2024 All Rights Reserved</span>
            </div>
            <!-- Navigation -->
            <ul class="flex flex-wrap gap-6 justify-center md:justify-end mt-4 md:mt-0">
                <li>
                    <a href="#contactUs" class="hover:text-[#c8a876] transition">Contact</a>
                </li>
                <li>
                    <a href="/privacy" class="hover:text-[#c8a876] transition">Privacy Policy</a>
                </li>
                <li>
                    <a href="#aboutus" class="hover:text-[#c8a876] transition">About Us</a>
                </li>
                <li>
                    <a href="#gallery" class="hover:text-[#c8a876] transition">Gallery</a>
                </li>
            </ul>
            <!-- Social Media -->
            <div class="flex gap-4 mt-4 md:mt-0">
                <a href="https://wa.me/6281234567890" target="_blank" class="hover:text-[#25D366] transition" title="WhatsApp">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.52 3.48A12.07 12.07 0 0 0 12 0C5.37 0 0 5.37 0 12c0 2.11.55 4.14 1.6 5.93L0 24l6.22-1.63A11.94 11.94 0 0 0 12 24c6.63 0 12-5.37 12-12 0-3.19-1.24-6.19-3.48-8.52zM12 22c-1.77 0-3.5-.46-5.01-1.33l-.36-.21-3.69.97.99-3.59-.23-.37A9.93 9.93 0 0 1 2 12c0-5.52 4.48-10 10-10s10 4.48 10 10-4.48 10-10 10zm5.2-7.8c-.28-.14-1.65-.81-1.9-.9-.25-.09-.43-.14-.61.14-.18.28-.7.9-.86 1.09-.16.18-.32.2-.6.07-.28-.14-1.18-.44-2.25-1.41-.83-.74-1.39-1.65-1.55-1.93-.16-.28-.02-.43.12-.57.13-.13.28-.34.42-.51.14-.17.18-.29.27-.48.09-.19.05-.36-.02-.5-.07-.14-.61-1.47-.83-2.01-.22-.53-.44-.46-.61-.47-.16-.01-.36-.01-.56-.01-.2 0-.52.07-.8.34-.28.28-1.06 1.04-1.06 2.54 0 1.5 1.09 2.95 1.24 3.16.15.21 2.14 3.27 5.19 4.45.73.28 1.3.45 1.74.58.73.23 1.39.2 1.91.12.58-.09 1.65-.67 1.89-1.32.23-.65.23-1.2.16-1.32-.07-.12-.25-.19-.53-.33z"/>
                    </svg>
                </a>
                <a href="mailto:info@jamburtamsaka.com" class="hover:text-[#0072c6] transition" title="Email">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 12l-4-4-4 4m8 0v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-6m16-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v4"/>
                    </svg>
                </a>
                <a href="https://instagram.com/" target="_blank" class="hover:text-[#c8a876] transition" title="Instagram">
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
</script>
</body>
</html>