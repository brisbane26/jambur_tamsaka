<x-admin-layout>
    <div class="w-full p-4">
        <h3 class="text-gray-700 text-3xl font-medium mb-6">Edit Profil</h3>

        <div class="bg-white border border-gray-200 rounded-lg shadow-md p-6">
            @if (session('status'))
                <div class="mb-6">
                    <div class="flex items-center justify-between p-4 text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg"
                        role="alert">
                        <span class="font-medium">Berhasil!</span> {{ session('status') }}
                        <button type="button" onclick="this.parentElement.remove()"
                            class="ml-4 text-green-500 hover:text-green-700 transition">
                            &times;
                        </button>
                    </div>
                </div>
            @endif


            <form id="formProfile" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600
">
<p class="text-sm mt-1 text-gray-500" id="email-format">Format email valid (cth:
                            user@example.com)</p>
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600
">
                    @error('username')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input id="nama_lengkap" type="text" name="nama_lengkap"
                        value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600
">
                    @error('nama_lengkap')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input id="telepon" type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600
">
                            <p class="text-sm mt-1 text-gray-500" id="phone-format">Hanya angka (minimal 11 digit) yang diperbolehkan.</p>

                    @error('telepon')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gambar" class="block text-sm font-medium text-gray-700">Ganti Gambar
                        (opsional)</label>
                    @if ($user->gambar)
                        <img src="{{ asset('storage/' . $user->gambar) }}" alt="Profile Picture"
                            class="w-20 h-20 rounded-full my-2 object-cover">
                    @endif
                    <input id="gambar" type="file" name="gambar"
                        class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-red-50 file:text-red-600 hover:file:bg-red-100">
                    @error('gambar')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                    <input id="password" type="password" name="password"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600
 pr-20"
                        placeholder="Kosongkan jika tidak ingin mengganti">

                    <button type="button" onclick="togglePw()" id="toggleBtn"
                        class="absolute top-9 right-3 text-sm text-red-600 hover:text-red-800">
                        Tampilkan
                    </button>

                    <ul class="text-sm mt-2 text-gray-600 space-y-1" id="password-rules">
                        <li id="rule-length">Minimal 8 karakter</li>
                        <li id="rule-upper">Huruf besar (A-Z)</li>
                        <li id="rule-lower">Huruf kecil (a-z)</li>
                        <li id="rule-digit">Angka (0-9)</li>
                        <li id="rule-symbol">Simbol (!@#$%^&*)</li>
                    </ul>

                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                        Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600 pr-20">
                    <button type="button" onclick="toggleConfirmPw()" id="toggleConfirmBtn"
                        class="absolute top-9 right-3 text-sm text-red-600 hover:text-red-800">Tampilkan</button>
                </div>



                <div class="text-right">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>


        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const toggleBtn = document.getElementById('toggleBtn');
            const form = document.getElementById('formProfile');
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            // Tampilkan indikator kekuatan password saat user mengetik
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
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                toggleBtn.textContent = isHidden ? 'Sembunyikan' : 'Tampilkan';
            }
            window.togglePw = togglePw;

            function toggleConfirmPw() {
                const confirmInput = document.getElementById('password_confirmation');
                const btn = document.getElementById('toggleConfirmBtn');
                const isHidden = confirmInput.type === 'password';
                confirmInput.type = isHidden ? 'text' : 'password';
                btn.textContent = isHidden ? 'Sembunyikan' : 'Tampilkan';
            }
            window.toggleConfirmPw = toggleConfirmPw;


            // Validasi saat form disubmit
            form.addEventListener('submit', function(e) {
                const email = document.getElementById('email').value.trim();
                const password = passwordInput.value.trim();
                const confirm = confirmInput.value.trim();
                const telepon = document.getElementById('telepon').value.trim();
                const teleponRegex = /^08[0-9]{9,11}$/;
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    Swal.fire('Email tidak valid', 'Masukkan email dengan format yang benar.', 'error');
                    return;
                }

                if (!teleponRegex.test(telepon)) {
                    e.preventDefault();
                    Swal.fire('Nomor telepon tidak valid', 'Gunakan format Indonesia, contoh: 08xxxxxxxxxx.Minimal 11 angka', 'error');
                    return;
                }

                const wantToChange = password !== '' || confirm !== '';

                if (wantToChange) {
                    if (!passwordRegex.test(password)) {
                        e.preventDefault();
                        Swal.fire('Password tidak valid', 'Pastikan semua syarat password terpenuhi.',
                            'error');
                        return;
                    }

                    if (password !== confirm) {
                        e.preventDefault();
                        Swal.fire('Konfirmasi salah', 'Password dan konfirmasi tidak sama.', 'error');
                        return;
                    }
                }
            });
        });
    </script>
</x-admin-layout>
