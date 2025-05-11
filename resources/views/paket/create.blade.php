<x-admin-layout>
    <h3 class="text-gray-700 text-3xl font-medium">Tambah Paket</h3>

    <form action="{{ route('paket.store') }}" method="POST" enctype="multipart/form-data" class="mt-8">
        @csrf
        
        <!-- Nama Paket -->
        <div class="mb-4">
            <label for="nama_paket" class="block text-gray-600">Nama Paket</label>
            <input type="text" name="nama_paket" id="nama_paket" class="w-full p-2 border border-gray-300 rounded-md" required>
        </div>

        <!-- Kategori -->
        <div class="mb-4">
            <label for="kategori_id" class="block text-gray-600">Kategori</label>
            <select name="kategori_id" id="kategori_id" class="w-full p-2 border border-gray-300 rounded-md" required>
                <option value="">Pilih Kategori</option>
                @foreach($kategori as $kat)
                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <!-- Deskripsi -->
        <div class="mb-4">
            <label for="deskripsi" class="block text-gray-600">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="w-full p-2 border border-gray-300 rounded-md" required></textarea>
        </div>

        <!-- Modal -->
        <div class="mb-4">
            <label for="modal" class="block text-gray-600">Modal</label>
            <input type="number" name="modal" id="modal" class="w-full p-2 border border-gray-300 rounded-md" required>
        </div>

        <!-- Harga Jual -->
        <div class="mb-4">
            <label for="harga_jual" class="block text-gray-600">Harga Jual</label>
            <input type="number" name="harga_jual" id="harga_jual" class="w-full p-2 border border-gray-300 rounded-md" required>
        </div>

        <!-- Gambar -->
        <div class="mb-4">
            <label for="gambar" class="block text-gray-600">Gambar</label>
            <input type="file" name="gambar" id="gambar" class="w-full p-2 border border-gray-300 rounded-md" accept="image/*">
            @error('gambar')
            <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="px-6 py-3 bg-blue-600 rounded-md text-white font-medium tracking-wide hover:bg-blue-500">
            Simpan
        </button>

    </form>

</x-admin-layout>
