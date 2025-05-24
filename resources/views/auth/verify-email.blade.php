<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-3xl flex flex-col md:flex-row">
        
        <!-- Kiri: Gambar -->
        <div class="w-1/2 bg-left-top text-white p-10 hidden md:flex flex-col justify-center relative"
            style="background-image: url('/images/motifkaro.jpeg'); background-size: cover; background-position: left center;">
            <h2 class="text-4xl font-bold mb-2 text-yellow-400 text-right">Mejuah-Juah!</h2>
            <p class="text-lg text-right">Verifikasi email Anda untuk melanjutkan</p>
        </div>

        <!-- Kanan: Isi -->
        <div class="w-full md:w-1/2 p-8 flex items-center justify-center">
            <div class="w-full max-w-md">
                <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">Verifikasi Email</h2>

                <p class="mb-4 text-sm text-gray-600 text-justify">
                    Terima kasih telah mendaftar! Sebelum mulai, mohon verifikasi email Anda dengan mengeklik link yang baru saja kami kirim. <br>
                    Jika Anda tidak menerima email tersebut, kami bisa mengirim ulang.
                </p>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 text-sm text-green-600 text-center">
                        Link verifikasi baru telah dikirim ke email Anda.
                    </div>
                @endif

                <!-- Form Kirim Ulang -->
                <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                    @csrf
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-red-600 to-yellow-600 text-white py-2 rounded-md hover:from-red-700 hover:to-yellow-700 transition">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <!-- Tombol Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-center text-sm text-gray-600 hover:text-red-600 underline">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
