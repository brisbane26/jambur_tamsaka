<x-guest-layout>
    
    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

            <!-- Name -->
            <div class="mb-4">
                <x-input-label for="name" :value="__('Nama Lengkap')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <ul class="text-sm mt-1 text-gray-600 space-y-1 transition-all" id="email-rules">
                    <li id="email-format" class="transition-all">Format email valid (cth: user@example.com)</li>
                </ul>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-4 relative">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required autocomplete="new-password" />
                <button type="button" onclick="togglePw()" id="toggleBtn" class="absolute top-9 right-3 text-sm text-blue-500 hover:text-blue-700 focus:outline-none">Tampilkan</button>
                <ul class="text-sm mt-1 text-gray-600 space-y-1 transition-all" id="password-rules">
                    <li id="rule-length">Minimal 8 karakter</li>
                    <li id="rule-upper">Huruf besar (A-Z)</li>
                    <li id="rule-lower">Huruf kecil (a-z)</li>
                    <li id="rule-digit">Angka (0-9)</li>
                    <li id="rule-symbol">Simbol (.,!@# dll)</li>
                </ul>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between">
                <a class="text-sm text-gray-600 hover:text-blue-600" href="{{ route('login') }}">
                    {{ __('Sudah punya akun?') }}
                </a>

                <x-primary-button class="bg-blue-600 hover:bg-blue-700">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');

        // Email validation
        emailInput.addEventListener('input', () => {
            const email = emailInput.value.trim();
            const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            document.getElementById('email-format').style.color = valid ? 'green' : 'gray';
        });

        // Password validation
        passwordInput.addEventListener('input', () => {
            const val = passwordInput.value;
            toggleRule('rule-length', val.length >= 8);
            toggleRule('rule-upper', /[A-Z]/.test(val));
            toggleRule('rule-lower', /[a-z]/.test(val));
            toggleRule('rule-digit', /\d/.test(val));
            toggleRule('rule-symbol', /[\W_]/.test(val));
        });

        function toggleRule(id, valid) {
            document.getElementById(id).style.color = valid ? 'green' : 'gray';
        }

        // Toggle password visibility
        function togglePw() {
            const pw = document.getElementById('password');
            const btn = document.getElementById('toggleBtn');
            const hidden = pw.type === 'password';
            pw.type = hidden ? 'text' : 'password';
            btn.textContent = hidden ? 'Sembunyikan' : 'Tampilkan';
        }

        // Final form check
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
            }
        });
    </script>
</x-guest-layout>
