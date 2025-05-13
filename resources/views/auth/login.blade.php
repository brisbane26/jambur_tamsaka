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
  <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md">
    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Masuk ke Akun</h2>

    <!-- Session Status Message (Opsional) -->
    <div id="session-status" class="mb-4 hidden text-sm text-green-600 text-center"></div>

    <form id="loginForm" method="POST"  action="{{ route('login') }}">
      <!-- CSRF Token (bila pakai Laravel backend) -->
    @csrf
      <!-- Email -->
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" id="email" name="email" required
               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <!-- Password -->
      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" id="password" name="password" required
               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <!-- Remember Me -->
      <div class="flex items-center mb-4">
        <input type="checkbox" id="remember_me" name="remember"
               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
        <label for="remember_me" class="ml-2 block text-sm text-gray-700">Ingat Saya</label>
      </div>

      <!-- Aksi -->
      <div class="flex items-center justify-between">
        <a href="/forgot-password" class="text-sm text-blue-600 hover:text-blue-800">Lupa password?</a>
        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
          Login
        </button>
      </div>
    </form>

    <div class="mt-4 text-center">
      <span class="text-sm text-gray-600">Belum punya akun? </span>
      <a href="/register" class="text-sm text-blue-600 hover:text-blue-800">Daftar disini</a>
    </div>
  </div>

  <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
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

      // Anda bisa menambahkan request ke backend jika menggunakan AJAX
    });

    // Contoh menampilkan pesan sukses dari session (jika pakai Laravel backend)
    const sessionStatus = '{{ session('status') }}';
    if (sessionStatus) {
      const statusEl = document.getElementById('session-status');
      statusEl.textContent = sessionStatus;
      statusEl.classList.remove('hidden');
    }
  </script>
</body>
</html>
