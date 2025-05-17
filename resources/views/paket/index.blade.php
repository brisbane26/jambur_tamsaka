<x-admin-layout>
    <h3 class="text-gray-700 text-3xl font-medium">Tables</h3>

    <div class="mt-8">
        <div class="flex justify-between items-center">
            <h4 class="text-gray-600">Daftar Paket</h4>
            @role('admin')
                <button class="px-6 py-3 bg-blue-600 rounded-md text-white font-medium tracking-wide hover:bg-blue-500">
                    <a href="{{ route('paket.create') }}">Tambah Paket</a>
                </button>
            @endrole
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            @foreach ($paket as $pkt)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="h-56 bg-gray-100 flex items-center justify-center overflow-hidden">
                        @if ($pkt->gambar_url)
                            <img src="{{ $pkt->gambar_url }}" alt="Gambar Paket" class="object-cover w-full h-full">
                        @else
                            <span class="text-base text-gray-500">No Image</span>
                        @endif
                    </div>
                    <div class="px-4 py-3">
                        <h5 class="text-xl font-semibold text-gray-900 mb-2">{{ $pkt->nama_paket }}</h5>
                        <p class="text-sm text-gray-600 mb-4">{{ $pkt->deskripsi }}</p>
                        <span class="block text-basem font-bold text-gray-800 mb-3">Rp
                            {{ number_format($pkt->harga_jual, 0, ',', '.') }}</span>
                        <div class="grid grid-cols-3 gap-2">
                            @role('admin')
                                <span class="text-sm text-gray-500">Kategori: {{ $pkt->kategori->nama_kategori }}</span>
                            <!-- Tombol Delete -->
                            <button onclick="toggleDeleteModal({{ $pkt->id }})"
                                class="flex items-center justify-center px-2 py-1 bg-red-500 text-white text-xs font-semibold rounded-md hover:bg-red-600 transition duration-300 ease-in-out min-h-[28px]">
                                <i class="bi bi-trash-fill mr-1"></i> Delete
                            </button>

                            <!-- Tombol Edit -->
                            <button onclick="toggleEditModal({{ $pkt->id }})"
                                class="flex items-center justify-center px-2 py-1 bg-yellow-500 text-white text-xs font-semibold rounded-md hover:bg-yellow-600 transition duration-300 ease-in-out min-h-[28px]">
                                <i class="bi bi-pencil-square mr-1"></i> Edit
                            </button>
                            @endrole

                            <!-- Tombol Add to Cart -->
                            <form action="{{ route('keranjang.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="paket_id" value="{{ $pkt->id }}">
                                <button type="submit"
                                    class="flex items-center justify-center w-full px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded-md hover:bg-green-600 transition duration-300 ease-in-out min-h-[28px] whitespace-nowrap">
                                    <i class="bi bi-cart-plus-fill mr-1"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Delete -->
                <div id="deleteModal{{ $pkt->id }}" class="fixed inset-0 hidden overflow-y-auto bg-black/50">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="relative w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
                            <h2 class="text-lg mb-4 font-semibold">Apakah kamu yakin untuk menghapus?</h2>
                            <div class="flex justify-end space-x-2">
                                <button type="button" onclick="toggleDeleteModal({{ $pkt->id }})"
                                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                                <form action="{{ route('paket.destroy', $pkt->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-700">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit -->
                <div id="editModal{{ $pkt->id }}" class="fixed inset-0 hidden overflow-y-auto bg-black/50">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="relative w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
                            <h2 class="text-lg mb-4 font-semibold">Edit Paket</h2>

                            <form action="{{ route('paket.update', $pkt->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Nama Paket -->
                                <div class="mb-4">
                                    <label for="nama_paket" class="block text-gray-600">Nama Paket</label>
                                    <input type="text" name="nama_paket" id="nama_paket"
                                        value="{{ $pkt->nama_paket }}"
                                        class="w-full p-2 border border-gray-300 rounded-md" required>
                                </div>

                                <!-- Kategori -->
                                <div class="mb-4">
                                    <label for="kategori_id" class="block text-gray-600">Kategori</label>
                                    <select name="kategori_id" id="kategori_id"
                                        class="w-full p-2 border border-gray-300 rounded-md" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($kategori as $kat)
                                            <option value="{{ $kat->id }}"
                                                {{ $pkt->kategori_id == $kat->id ? 'selected' : '' }}>
                                                {{ $kat->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Deskripsi -->
                                <div class="mb-4">
                                    <label for="deskripsi" class="block text-gray-600">Deskripsi</label>
                                    <textarea name="deskripsi" id="deskripsi" class="w-full p-2 border border-gray-300 rounded-md" required>{{ $pkt->deskripsi }}</textarea>
                                </div>

                                <!-- Modal -->
                                <div class="mb-4">
                                    <label for="modal" class="block text-gray-600">Modal</label>
                                    <input type="number" name="modal" id="modal" value="{{ $pkt->modal }}"
                                        class="w-full p-2 border border-gray-300 rounded-md" required>
                                </div>

                                <!-- Harga Jual -->
                                <div class="mb-4">
                                    <label for="harga_jual" class="block text-gray-600">Harga Jual</label>
                                    <input type="number" name="harga_jual" id="harga_jual"
                                        value="{{ $pkt->harga_jual }}"
                                        class="w-full p-2 border border-gray-300 rounded-md" required>
                                </div>

                                <!-- Gambar -->
                                <div class="mb-4">
                                    <label for="gambar" class="block text-gray-600">Gambar</label>
                                    <input type="file" name="gambar" id="gambar"
                                        class="w-full p-2 border border-gray-300 rounded-md" accept="image/*">
                                    @error('gambar')
                                        <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <button type="button" onclick="toggleEditModal({{ $pkt->id }})"
                                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>

                                    <button type="submit"
                                        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-500">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</x-admin-layout>
