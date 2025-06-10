<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Registrasi</title>
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
            <p class="text-xl text-right">Silakan membuat akun baru Anda</p>

            <!-- Hiasan dekoratif (opsional, SVG/elemen tambahan) -->
            <div class="absolute top-10 right-10">
                <!-- Tambahkan SVG atau elemen dekoratif di sini -->
            </div>
        </div>

        <!-- Bagian Form Kanan -->
        <div class="md:w-1/2 p-8">
            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Daftar</h2>

            <form id="registerForm" method="POST" data-turbo="false"  action="{{ route('register') }}">
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
                        <input type="text" id="telepon" name="telepon" placeholder="Contoh: 081234567890" required
                            oninput="validatePhone()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none" />
                        <ul class="text-sm mt-1 text-gray-500 space-y-1">
                            <li id="rule-phone-numeric">Hanya angka (0-9)</li>
                            <li id="rule-phone-length">Antara 10-13 digit</li>
                        </ul>
                    </div>

                    <button type="button" onclick="nextStep()"
                        class="w-full bg-gradient-to-r from-red-600 to-yellow-600 text-white py-2 rounded-md hover:from-red-700 hover:to-yellow-700 transition">
                        Selanjutnya
                    </button>

                    <div class="mt-4 text-center text-sm text-gray-600">
                        Sudah Punya Akun? <a href="/login" class="text-red-600 hover:text-red-800">Login</a>
                    </div>
                </div>

                <!-- STEP 2 -->
                <div id="step2" class="hidden">
                    <!-- Email -->
                    <div class="mb-4">
                        <input type="email" id="email" name="email" placeholder="Email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none" />
                        <p class="text-sm mt-1 text-gray-500" id="email-format">Format email valid (cth:
                            user@example.com)</p>
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
                    <div class="mb-6 relative">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Konfirmasi Password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 outline-none" />
                        <button type="button" onclick="toggleConfirmPw()" id="toggleConfirmBtn"
                            class="absolute top-2 right-3 text-sm text-red-600 hover:text-red-800">Tampilkan</button>
                    </div>


                    <div class="flex items-center justify-between">
                        <button type="button" onclick="prevStep()"
                            class="text-gray-600 hover:text-red-600 text-sm">Kembali</button>
                        <button type="submit"
                            class="w-60 bg-gradient-to-r from-red-600 to-yellow-600 text-white py-2 rounded-md hover:from-red-700 hover:to-yellow-700 transition">Daftar</button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <script>
        // --- Referensi Elemen ---
        const registerForm = document.getElementById('registerForm');
        const namaInput = document.getElementById('nama_lengkap');
        const usernameInput = document.getElementById('username');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const phoneInput = document.getElementById('telepon');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // --- Fungsi untuk Tahap 1 ---
async function nextStep() {
    // 1. Validasi dasar di frontend dulu
    if (!namaInput.value.trim() || !usernameInput.value.trim() || !phoneInput.value.trim()) {
        Swal.fire('Lengkapi Data', 'Nama, username, dan nomor telepon wajib diisi.', 'error');
        return;
    }
    
    // Regex BARU: harus diawali 08, diikuti 8-11 digit angka lainnya (total 10-13 digit)
    const validPhoneRegex = /^08[0-9]{8,11}$/; 
    if (!validPhoneRegex.test(phoneInput.value.trim())) {
        // Pesan error BARU sesuai permintaan
        Swal.fire('Format Salah', 'Pastikan nomor telepon anda sesuai ketentuan.', 'error');
        return;
    }

    // Tampilkan loading awal
    Swal.fire({
        title: 'Mengecek username...',
        didOpen: () => Swal.showLoading(),
        allowOutsideClick: false
    });

    try {
        // 2. Pengecekan pertama: USERNAME
        let response = await fetch("{{ route('register.check.username') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ username: usernameInput.value })
        });
        let data = await response.json();

        if (data.exists) {
            Swal.fire('Username Terdaftar', 'Maaf, username ini sudah digunakan. Silakan gunakan username lain.', 'error');
            return;
        }

        // 3. Jika username aman, lanjutkan pengecekan kedua: NOMOR TELEPON
        Swal.update({ title: 'Mengecek nomor telepon...' });

        response = await fetch("{{ route('register.check.telepon') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ telepon: phoneInput.value })
        });
        data = await response.json();

        if (data.exists) {
            Swal.fire('Nomor Terdaftar', 'Maaf, nomor telepon ini sudah terdaftar. Silakan gunakan nomor lain.', 'error');
            return;
        }

        // 4. Jika semua aman, tutup loading dan lanjut ke Tahap 2
        Swal.close();
        document.getElementById('step1').classList.add('hidden');
        document.getElementById('step2').classList.remove('hidden');

    } catch (error) {
        Swal.fire('Error Server', 'Gagal menghubungi server. Silakan coba lagi.', 'error');
    }
}

        // --- Event Listener untuk Tombol "Daftar" ---
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const password = passwordInput.value;
            const validPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(password);

            if (!emailInput.value.trim() || !password || !confirmInput.value) {
                Swal.fire('Lengkapi Data', 'Email, password, dan konfirmasi wajib diisi.', 'error');
                return;
            }
            if (!validPassword) {
                Swal.fire('Password Lemah', 'Pastikan semua syarat password terpenuhi.', 'error');
                return;
            }
            if (password !== confirmInput.value) {
                Swal.fire('Konfirmasi Salah', 'Password dan konfirmasi tidak cocok.', 'error');
                return;
            }

            Swal.fire({
                title: 'Mengecek data & mendaftar...',
                didOpen: () => Swal.showLoading(),
                allowOutsideClick: false
            });

            try {
                const response = await fetch("{{ route('register.check.email') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ email: emailInput.value })
                });
                const data = await response.json();

                if (data.exists) {
                    Swal.close();
                    Swal.fire('Email Terdaftar', 'Maaf, email ini sudah terdaftar.', 'error');
                } else {
                    // Semua pengecekan lolos, submit form secara penuh
                    registerForm.submit();
                }
            } catch (error) {
                Swal.fire('Error Server', 'Gagal menghubungi server. Silakan coba lagi.', 'error');
            }
        });

        // --- Fungsi Helper Lainnya ---
        function prevStep() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
        }

function validatePhone() {
    phoneInput.value = phoneInput.value.replace(/[^0-9]/g, '');
    const currentValue = phoneInput.value;

    toggleRule('rule-phone-numeric', currentValue.length > 0);

    const isLengthValid = currentValue.length >= 10 && currentValue.length <= 13;
    toggleRule('rule-phone-length', isLengthValid);
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
            const hidden = passwordInput.type === 'password';
            passwordInput.type = hidden ? 'text' : 'password';
            document.getElementById('toggleBtn').textContent = hidden ? 'Sembunyikan' : 'Tampilkan';
        }

        function toggleConfirmPw() {
            const hidden = confirmInput.type === 'password';
            confirmInput.type = hidden ? 'text' : 'password';
            document.getElementById('toggleConfirmBtn').textContent = hidden ? 'Sembunyikan' : 'Tampilkan';
        }
    </script>
</body>

</html>
