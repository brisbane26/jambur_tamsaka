<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-5xl flex flex-col md:flex-row">
    
    <!-- Kiri: Gambar -->
    <div class="w-1/2 bg-left-top text-white p-12 hidden lg:flex flex-col justify-center relative"
      style="background-image: url('/images/motifkaro.jpeg'); background-size: cover; background-position: left center; background-repeat: no-repeat;">
      <h2 class="text-5xl font-bold mb-4 text-yellow-400 text-right">Mejuah-Juah!</h2>
      <p class="text-xl text-right">Reset password akun Anda</p>
    </div>

    <!-- Kanan: Form Reset Password -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8" style="background-color: white;">
      <div class="w-full max-w-md">
        <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">Reset Password</h2>

        <form method="POST" action="{{ route('password.store') }}">
          @csrf

          <!-- Token -->
          <input type="hidden" name="token" value="{{ $request->route('token') }}">

          <!-- Email -->
          <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" required autofocus
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none"
              value="{{ old('email', $request->email) }}">
            @error('email')
              <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Password -->
          <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
            <input id="password" name="password" type="password" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none">
            @error('password')
              <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Konfirmasi Password -->
          <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none">
            @error('password_confirmation')
              <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Tombol Submit -->
          <button type="submit"
            class="w-full bg-gradient-to-r from-red-600 to-yellow-600 text-white py-2 rounded-md hover:from-red-700 hover:to-yellow-700 transition">
            Reset Password
          </button>

          <div class="mt-4 text-center text-sm text-gray-600">
            Sudah ingat password? <a href="{{ route('login') }}" class="text-red-600 hover:text-red-800">Masuk</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
