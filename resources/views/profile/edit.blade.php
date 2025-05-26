<x-admin-layout>
<div class="w-full p-4">
    <h3 class="text-gray-700 text-3xl font-medium mb-6">Edit Profil</h3>

    <div class="bg-white border border-gray-200 rounded-lg shadow-md p-6">
        @if (session('status'))
            <div class="mb-6">
                <div class="flex items-center justify-between p-4 text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg" role="alert">
                    <span class="font-medium">Berhasil!</span> {{ session('status') }}
                    <button type="button" onclick="this.parentElement.remove()" class="ml-4 text-green-500 hover:text-green-700 transition">
                        &times;
                    </button>
                </div>
            </div>
        @endif


        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-indigo-500">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}"
                    class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-indigo-500">
                @error('username')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                    class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-indigo-500">
                @error('nama_lengkap')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
                <input id="telepon" type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}"
                    class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-indigo-500">
                @error('telepon')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="gambar" class="block text-sm font-medium text-gray-700">Ganti Gambar (opsional)</label>
                @if ($user->gambar)
                    <img src="{{ asset('storage/' . $user->gambar) }}" alt="Profile Picture"
                        class="w-20 h-20 rounded-full my-2 object-cover">
                @endif
                <input id="gambar" type="file" name="gambar"
                    class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                @error('gambar')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                <input id="password" type="password" name="password"
                    class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-indigo-500"
                    placeholder="Kosongkan jika tidak ingin mengganti">
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                    class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-indigo-500">
            </div>

            <div class="text-right">
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>

        
    </div>
</div>

</x-admin-layout>