<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lupa Password</title>
  <link rel="icon" type="image/png" href="{{ asset('images/favicon-removebg-preview.png') }}" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-5xl flex flex-col md:flex-row">
    
    <!-- Left: Image Section -->
    <div class="w-1/2 bg-left-top text-white p-12 hidden lg:flex flex-col justify-center relative"
     style="background-image: url('/images/motifkaro.jpeg'); background-size: cover; background-position: left center; background-repeat: no-repeat;">

      <h2 class="text-5xl font-bold mb-4 text-yellow-400 text-right">Mejuah-Juah!</h2>
      <p class="text-xl text-right">Reset your password to access your account</p>

      <!-- Decorative element (optional) -->
      <div class="absolute top-10 right-10">
        <!-- Add SVG or decorative elements here -->
      </div>
    </div>

    <!-- Right: Forgot Password Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8" style="background-color: white;">

      <div class="w-full max-w-md">
        <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">Reset Password</h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-sm text-green-600 text-center" :status="session('status')" />

        <div class="mb-4 text-sm text-gray-600 text-center">
            {{ __('Lupa Password Anda? Masukkan Email Anda dan Kami Akan Mengirim Link untuk Mereset Password Anda.') }}
        </div>

        <form method="POST" action="{{ route('password.email') }}">
          @csrf

          <!-- Email -->
          <div class="mb-4">
            <input type="email" id="email" name="email" placeholder="Your email address" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none" 
              value="{{ old('email') }}"
              autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
          </div>

          <!-- Submit -->
          <button type="submit"
            class="w-full bg-gradient-to-r from-red-600 to-yellow-600 text-white py-2 rounded-md hover:from-red-700 hover:to-yellow-700 transition">
            Send Password Reset Link
          </button>

          <div class="mt-4 text-center text-sm text-gray-600">
            Remember your password? <a href="{{ route('login') }}" class="text-red-600 hover:text-red-800">Sign In</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>