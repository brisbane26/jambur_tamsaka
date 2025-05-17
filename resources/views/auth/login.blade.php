<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-5xl flex flex-col md:flex-row">
    
    <!-- Bagian Gambar Kiri -->
    <div class="w-1/2 bg-left-top text-white p-12 hidden lg:flex flex-col justify-center relative"
     style="background-image: url('/images/motifkaro.jpeg'); background-size: cover; background-position: left center; background-repeat: no-repeat;">



      <h2 class="text-5xl font-bold mb-4 text-yellow-400 text-right">Mejuah-Juah!</h2>
      <p class="text-xl text-right">Silahkan login menggunakan akun anda</p>

      <!-- Hiasan dekoratif (opsional, SVG/elemen tambahan) -->
      <div class="absolute top-10 right-10">
        <!-- Tambahkan SVG atau elemen dekoratif di sini -->
      </div>
    </div>

    <!-- Kanan: Form Login -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8" style="background-color: white;">

      <div class="w-full max-w-md">
        <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">Sign In</h2>

        <!-- Session Status -->
        <div id="session-status" class="mb-4 hidden text-sm text-green-600 text-center"></div>

        <form id="loginForm" method="POST" action="{{ route('login') }}">
          @csrf

          <!-- Email -->
          <div class="mb-4">
            <input type="email" id="email" name="email" placeholder="Username or email" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none" />
          </div>

          <!-- Password -->
          <div class="mb-4 relative">
            <input type="password" id="password" name="password" placeholder="Password" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none" />
            <button type="button" onclick="togglePw()" id="toggleBtn"
              class="absolute top-2 right-3 text-sm text-red-600 hover:text-red-800">Tampilkan</button>
          </div>

          <!-- Remember Me -->
          <div class="flex items-center justify-between mb-4">
            <label class="flex items-center text-sm text-gray-600">
              <input type="checkbox" name="remember" class="mr-2"> Remember me
            </label>
            <a href="/forgot-password" class="text-sm text-red-600 hover:text-red-800">Forgot password?</a>
          </div>

          <!-- Submit -->
          <button type="submit"
            class="w-full bg-gradient-to-r from-red-600 to-yellow-600 text-white py-2 rounded-md hover:from-red-700 hover:to-yellow-700 transition">
            Sign In
          </button>

          <div class="mt-4 text-center text-sm text-gray-600">
            New here? <a href="/register" class="text-red-600 hover:text-red-800">Create an Account</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('loginForm').addEventListener('submit', function (e) {
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value.trim();

      const validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

      if (!validEmail) {
        e.preventDefault();
        Swal.fire('Email tidak valid', 'Masukkan format email yang benar.', 'error');
        return;
      }

      if (password.length < 4) {
        e.preventDefault();
        Swal.fire('Password terlalu pendek', 'Minimal 4 karakter.', 'error');
        return;
      }
    });

    const sessionStatus = '{{ session('status') }}';
    if (sessionStatus) {
      const statusEl = document.getElementById('session-status');
      statusEl.textContent = sessionStatus;
      statusEl.classList.remove('hidden');
    }

    const loginError = @json($errors->first('email'));
    if (loginError) {
      Swal.fire('Login Gagal', loginError, 'error');
    }

    function togglePw() {
      const pw = document.getElementById('password');
      const btn = document.getElementById('toggleBtn');
      const hidden = pw.type === 'password';
      pw.type = hidden ? 'text' : 'password';
      btn.textContent = hidden ? 'Sembunyikan' : 'Tampilkan';
    }
  </script>
</body>
</html>