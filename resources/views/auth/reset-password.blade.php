<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-5xl flex flex-col md:flex-row">

    <div class="w-1/2 bg-left-top text-white p-12 hidden lg:flex flex-col justify-center relative"
      style="background-image: url('/images/motifkaro.jpeg'); background-size: cover; background-position: left center; background-repeat: no-repeat;">
      <h2 class="text-5xl font-bold mb-4 text-yellow-400 text-right">Mejuah-Juah!</h2>
      <p class="text-xl text-right">Reset password akun Anda</p>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-8" style="background-color: white;">
      <div class="w-full max-w-md">
        <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">Reset Password</h2>

        <form id="resetForm" method="POST" action="{{ route('password.store') }}">
  @csrf

  <!-- Token -->
  <input type="hidden" name="token" value="{{ $request->route('token') }}">

  <!-- Email -->
  <div class="mb-4">
    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
    <input
      id="email"
      name="email"
      type="email"
      required
      autofocus
      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none"
      value="{{ old('email', $request->email) }}"
    >
    @error('email')
      <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
  </div>

  <!-- Password -->
  <div class="mb-4 relative">
    <input
      type="password"
      id="password"
      name="password"
      placeholder="Password"
      required
      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none"
    >
    <button
      type="button"
      onclick="togglePw()"
      id="toggleBtn"
      class="absolute top-2 right-3 text-sm text-red-600 hover:text-red-800"
    >
      Tampilkan
    </button>
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
    <input
      type="password"
      id="password_confirmation"
      name="password_confirmation"
      placeholder="Konfirmasi Password"
      required
      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none"
    >
  </div>

  <!-- Tombol Submit -->
  <button
    type="submit"
    class="w-full bg-gradient-to-r from-red-600 to-yellow-600 text-white py-2 rounded-md hover:from-red-700 hover:to-yellow-700 transition"
  >
    Reset Password
  </button>

  <!-- Link ke Login -->
  <div class="mt-4 text-center text-sm text-gray-600">
    Sudah ingat password?
    <a href="{{ route('login') }}" class="text-red-600 hover:text-red-800">Masuk</a>
  </div>
</form>

      </div>
    </div>
  </div>


    <script>
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');


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

    document.getElementById('resetForm').addEventListener('submit', function (e) {
      const password = passwordInput.value;
      const confirm = confirmInput.value;
      const validPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(password);

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
