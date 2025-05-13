<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Form Registrasi</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md">
    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Daftar Akun Baru</h2>

    <form id="registerForm" method="POST"  action="{{ route('register') }}" >
        @csrf
      <!-- Nama Lengkap -->
      <div class="mb-4">
        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" required
               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <!-- Username -->
      <div class="mb-4">
        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
        <input type="text" id="username" name="username" required
               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <!-- Nomor Telepon -->
      <div class="mb-4">
        <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
        <input type="text" id="telepon" name="telepon" required oninput="validatePhone()"
               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <p class="text-sm mt-1 text-gray-500" id="phone-format">Hanya angka yang diperbolehkan.</p>
      </div>

      <!-- Email -->
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" id="email" name="email" required
               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <p class="text-sm mt-1 text-gray-500" id="email-format">Format email valid (cth: user@example.com)</p>
      </div>

      <!-- Password -->
      <div class="mb-4 relative">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" id="password" name="password" required
               class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <button type="button" onclick="togglePw()" id="toggleBtn"
                class="absolute top-9 right-3 text-sm text-blue-600 hover:text-blue-800">Tampilkan</button>
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
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required
               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <!-- Button & Link -->
      <div class="flex items-center justify-between">
        <a href="/login" class="text-sm text-gray-600 hover:text-blue-600">Sudah punya akun?</a>
        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Register</button>
      </div>
    </form>
  </div>

  <script>
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const phoneInput = document.getElementById('telepon');

    emailInput.addEventListener('input', () => {
      const email = emailInput.value.trim();
      const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      document.getElementById('email-format').style.color = valid ? 'green' : 'gray';
    });

    function validatePhone() {
      const telepon = phoneInput.value;
      const valid = /^[0-9]*$/.test(telepon);
      document.getElementById('phone-format').style.color = valid ? 'green' : 'gray';
      phoneInput.value = valid ? telepon : telepon.replace(/[^0-9]/g, '');
    }

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
      const telepon = phoneInput.value.trim();

      const validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      const validPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(password);
      const validPhone = /^[0-9]*$/.test(telepon);

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

      if (!validPhone) {
        e.preventDefault();
        Swal.fire('Nomor Telepon tidak valid', 'Hanya angka yang diperbolehkan.', 'error');
      }
    });
  </script>
</body>
</html>
