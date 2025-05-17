<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Form Registrasi Bertahap</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-5xl flex flex-col md:flex-row">
    
    <!-- Bagian Gambar Kiri -->
    <div class="w-1/2 bg-left-top text-white p-12 hidden lg:flex flex-col justify-center relative"
     style="background-image: url('/images/motifkaro.jpeg'); background-size: cover; background-position: left center; background-repeat: no-repeat;">



      <h2 class="text-5xl font-bold mb-4 text-yellow-400 text-right">Mejuah-Juah!</h2>
      <p class="text-xl text-right">Silahkan membuat akun baru anda</p>

      <!-- Hiasan dekoratif (opsional, SVG/elemen tambahan) -->
      <div class="absolute top-10 right-10">
        <!-- Tambahkan SVG atau elemen dekoratif di sini -->
      </div>
    </div>

    <!-- Bagian Form Kanan -->
    <div class="md:w-1/2 p-8">
      <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Sign Up</h2>

      <form id="registerForm" method="POST" action="{{ route('register') }}">
        @csrf

        <!-- STEP 1 -->
        <div id="step1">
          <!-- Nama Lengkap -->
          <div class="mb-4">
            <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" required
             class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none" />
          </div>

          <!-- Username -->
          <div class="mb-4">
            <input type="text" id="username" name="username" placeholder="Username" required
             class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none" />
          </div>

          <!-- Nomor Telepon -->
          <div class="mb-6">
            <input type="text" id="telepon" name="telepon" placeholder="Nomor Telepon" required oninput="validatePhone()"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none" />
            <p class="text-sm mt-1 text-gray-500" id="phone-format">Hanya angka yang diperbolehkan.</p>
          </div>

          <button type="button" onclick="nextStep()"
            class="w-full bg-gradient-to-r from-red-600 to-yellow-600 text-white py-2 rounded-md hover:from-red-700 hover:to-yellow-700 transition">
            Selanjutnya
          </button>

          <div class="mt-4 text-center text-sm text-gray-600">
            Already have account? <a href="/login" class="text-red-600 hover:text-red-800">Login</a>
          </div>
        </div>

        <!-- STEP 2 -->
        <div id="step2" class="hidden">
          <!-- Email -->
          <div class="mb-4">
            <input type="email" id="email" name="email" placeholder="Email" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none" />
            <p class="text-sm mt-1 text-gray-500" id="email-format">Format email valid (cth: user@example.com)</p>
          </div>

          <!-- Password -->
          <div class="mb-4 relative">
            <input type="password" id="password" name="password" placeholder="Password" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none" />
             <button type="button" onclick="togglePw()" id="toggleBtn"
              class="absolute top-2 right-3 text-sm text-red-600 hover:text-red-800">Tampilkan</button>
            <ul class="text-sm mt-1 text-gray-600 space-y-1" id="password-rules">
              <li id="rule-length">Minimal 8 karakter</li>
              <li id="rule-upper">Huruf besar (A-Z)</li>
              <li id="rule-lower">Huruf kecil (a-z)</li>
              <li id="rule-digit">Angka (0-9)</li>
              <li id="rule-symbol">Simbol (.,!@# dll)</li>
            </ul>
          </div>

          <!-- Konfirmasi Password -->
          <div class="mb-6">
            <input type="password" id="password_confirmation" name="password_confirmation"placeholder="Konfirmasi Password" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none" />
          </div>

          <div class="flex items-center justify-between">
            <button type="button" onclick="prevStep()"
              class="text-gray-600 hover:text-red-600 text-sm">Kembali</button>
            <button type="submit"
              class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Register</button>
          </div>
        </div>
      </form>
    </div>

  </div>

  <script>
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const phoneInput = document.getElementById('telepon');

    function nextStep() {
      const nama = document.getElementById('nama_lengkap').value.trim();
      const username = document.getElementById('username').value.trim();
      const phone = phoneInput.value.trim();
      const validPhone = /^[0-9]*$/.test(phone);

      if (!nama || !username || !phone) {
        Swal.fire('Lengkapi Data', 'Semua kolom harus diisi.', 'error');
        return;
      }

      if (!validPhone) {
        Swal.fire('Nomor Telepon tidak valid', 'Hanya angka yang diperbolehkan.', 'error');
        return;
      }

      document.getElementById('step1').classList.add('hidden');
      document.getElementById('step2').classList.remove('hidden');
    }

    function prevStep() {
      document.getElementById('step2').classList.add('hidden');
      document.getElementById('step1').classList.remove('hidden');
    }

    function validatePhone() {
      const telepon = phoneInput.value;
      const valid = /^[0-9]*$/.test(telepon);
      document.getElementById('phone-format').style.color = valid ? 'green' : 'gray';
      phoneInput.value = telepon.replace(/[^0-9]/g, '');
    }

    emailInput.addEventListener('input', () => {
      const email = emailInput.value.trim();
      const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      document.getElementById('email-format').style.color = valid ? 'green' : 'gray';
    });

    passwordInput.addEventListener('input', () => {
      const val = passwordInput.value;
      toggleRule('rule-length', val.length >= 8);
      toggleRule('rule-upper', /[A-Z]/.test(val));
      toggleRule('rule-lower', /[a-z]/.test(val));
      toggleRule('rule-digit', /[0-9]/.test(val));
      toggleRule('rule-symbol', /[\W_]/.test(val));
    });

    function toggleRule(id, valid) {
      document.getElementById(id).style.color = valid ? 'green' : 'gray';
    }

    function togglePw() {
      const pw = document.getElementById('password');
      const btn = document.getElementById('toggleBtn');
      const hidden = pw.type === 'password';
      pw.type = hidden ? 'text' : 'password';
      btn.textContent = hidden ? 'Sembunyikan' : 'Tampilkan';
    }

    document.getElementById('registerForm').addEventListener('submit', function (e) {
      const email = emailInput.value.trim();
      const password = passwordInput.value;
      const confirm = confirmInput.value;

      const validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      const validPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(password);

      if (!validEmail) {
        e.preventDefault();
        Swal.fire('Email tidak valid', 'Masukkan format email yang benar.', 'error');
        return;
      }

      if (!validPassword) {
        e.preventDefault();
        Swal.fire('Password tidak valid', 'Pastikan semua syarat password terpenuhi.', 'error');
        return;
      }

      if (password !== confirm) {
        e.preventDefault();
        Swal.fire('Konfirmasi salah', 'Password dan konfirmasi tidak sama.', 'error');
        return;
      }
    });
  </script>
</body>
</html>