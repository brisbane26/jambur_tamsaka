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
            <h1 class="text-grey-700 font-medium text-4xl md:text-5xl leading-tight mb-2">Bappa Flour mill</h1>
            <p class="font-regular text-xl mb-8 mt-4">One stop solution for flour grinding services</p>
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




<!-- gallery -->
<section class="text-gray-700 body-font" id="gallery">
    <div class="flex justify-center text-3xl font-bold text-gray-800 text-center py-10">
        Gallery
    </div>

    <div class="grid grid-cols-1 place-items-center sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4">

        <div class="group relative">
            <img
      src="https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0NzEyNjZ8MHwxfHNlYXJjaHw1fHxuYXR1cmV8ZW58MHwwfHx8MTY5NDA5OTcyOXww&ixlib=rb-4.0.3&q=80&w=1080"
      alt="Image 1"
      class="aspect-[2/3] h-80 object-cover rounded-lg transition-transform transform scale-100 group-hover:scale-105"
    />
        </div>

        <div class="group relative">
            <img
      src="https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0NzEyNjZ8MHwxfHNlYXJjaHw1fHxuYXR1cmV8ZW58MHwwfHx8MTY5NDA5OTcyOXww&ixlib=rb-4.0.3&q=80&w=1080"
      alt="Image 1"
      class="aspect-[2/3] h-80 object-cover rounded-lg transition-transform transform scale-100 group-hover:scale-105"
    />
        </div>

        <div class="group relative">
            <img
      src="https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0NzEyNjZ8MHwxfHNlYXJjaHw1fHxuYXR1cmV8ZW58MHwwfHx8MTY5NDA5OTcyOXww&ixlib=rb-4.0.3&q=80&w=1080"
      alt="Image 1"
      class="aspect-[2/3] h-80 object-cover rounded-lg transition-transform transform scale-100 group-hover:scale-105"
    />
        </div>
        <div class="group relative">
            <img
      src="https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0NzEyNjZ8MHwxfHNlYXJjaHw1fHxuYXR1cmV8ZW58MHwwfHx8MTY5NDA5OTcyOXww&ixlib=rb-4.0.3&q=80&w=1080"
      alt="Image 1"
      class="aspect-[2/3] h-80 object-cover rounded-lg transition-transform transform scale-100 group-hover:scale-105"
    />
        </div>



        <!-- Repeat this div for each image -->
    </div>

</section>

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
    <footer class="bg-gray-200 text-white py-4 px-3">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            <div class="w-full md:w-1/2 md:text-center md:mb-4 mb-8">
                <p class="text-xs text-gray-400 md:text-sm">Copyright 2024 &copy; All Rights Reserved</p>
            </div>
            <div class="w-full md:w-1/2 md:text-center md:mb-0 mb-8">
                <ul class="list-reset flex justify-center flex-wrap text-xs md:text-sm gap-3">
                    <li><a href="#contactUs" class="text-gray-400 hover:text-white">Contact</a></li>
                    <li class="mx-4"><a href="/privacy" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                </ul>
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