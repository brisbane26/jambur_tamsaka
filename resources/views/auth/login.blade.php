<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-removebg-preview.png') }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-5xl flex flex-col md:flex-row">

        <!-- Bagian Gambar Kiri -->
        <div class="w-1/2 bg-left-top text-white p-12 hidden lg:flex flex-col justify-center relative"
            style="background-image: url('/images/motifkaro.jpeg'); background-size: cover; background-position: left center; background-repeat: no-repeat;">



            <h2 class="text-5xl font-bold mb-4 text-yellow-400 text-right">Mejuah-Juah!</h2>
            <p class="text-xl text-right">Silakan masuk menggunakan akun anda</p>

            <!-- Hiasan dekoratif (opsional, SVG/elemen tambahan) -->
            <div class="absolute top-10 right-10">
                <!-- Tambahkan SVG atau elemen dekoratif di sini -->
            </div>
        </div>

        <!-- Kanan: Form Login -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8" style="background-color: white;">

            <div class="w-full max-w-md">
                <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">Masuk</h2>

                <!-- Session Status -->
                <div id="session-status" class="mb-4 hidden text-sm text-green-600 text-center"></div>

                <form id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-4">
                        <input type="email" id="email" name="email" placeholder="Email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none" />
                    </div>

                    <!-- Password -->
                    <div class="mb-4 relative">
                        <input type="password" id="password" name="password" placeholder="Password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none" />
                        <button type="button" id="toggleBtn"
                            class="absolute top-2 right-3 text-sm text-red-600 hover:text-red-800">Tampilkan</button>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between mb-4">
                        <label class="flex items-center text-sm text-gray-600">
                            <input type="checkbox" name="remember" class="mr-2"> Ingat Saya
                        </label>
                        <a href="/forgot-password" class="text-sm text-red-600 hover:text-red-800">Lupa Password
                            Anda?</a>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-red-600 to-yellow-600 text-white py-2 rounded-md hover:from-red-700 hover:to-yellow-700 transition">
                        Masuk
                    </button>

                    <div class="mt-4 text-center text-sm text-gray-600">
                        Belum Punya Akun? <a href="/register" class="text-red-600 hover:text-red-800">Daftar di
                            Sini.</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function initializeLoginScripts() {
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.removeEventListener('submit', handleLoginFormSubmit);
                loginForm.addEventListener('submit', handleLoginFormSubmit);
            }

            const sessionStatus = '{{ session('status') }}';
            if (sessionStatus) {
                const statusEl = document.getElementById('session-status');
                if (statusEl) {
                    statusEl.textContent = sessionStatus;
                    statusEl.classList.remove('hidden');
                }
            }

            const loginError = @json($errors->first('email'));
            if (loginError) {
                let customErrorMessage =
                    'Terjadi kesalahan saat mencoba masuk. Silakan periksa kembali email dan kata sandi Anda.';

                Swal.fire({
                    icon: 'error', // Pastikan ini selalu ada
                    title: 'Login Gagal',
                    text: customErrorMessage,
                    confirmButtonText: 'Oke',
                });
            }

            const toggleBtn = document.getElementById('toggleBtn');
            if (toggleBtn) {
                toggleBtn.removeEventListener('click', togglePw);
                toggleBtn.addEventListener('click', togglePw);
            }
        }

        function handleLoginFormSubmit(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            const validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

            if (!validEmail) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error', // Tambahkan ini
                    title: 'Email tidak valid',
                    text: 'Masukkan format email yang benar.',
                    confirmButtonText: 'Oke'
                });
                return;
            }

            if (password.length < 4) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error', // Tambahkan ini
                    title: 'Password terlalu pendek',
                    text: 'Kata sandi harus minimal 4 karakter.', // Pesan lebih deskriptif
                    confirmButtonText: 'Oke'
                });
                return;
            }
        }

        function togglePw() {
            const pw = document.getElementById('password');
            const btn = document.getElementById('toggleBtn');
            if (pw && btn) {
                const hidden = pw.type === 'password';
                pw.type = hidden ? 'text' : 'password';
                btn.textContent = hidden ? 'Sembunyikan' : 'Tampilkan';
            }
        }

        document.addEventListener('DOMContentLoaded', initializeLoginScripts);
        document.addEventListener('turbo:load', initializeLoginScripts);
    </script>
</body>

</html>
