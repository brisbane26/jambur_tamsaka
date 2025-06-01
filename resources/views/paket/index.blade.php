<link rel="icon" type="image/png" href="{{ asset('images/favicon-removebg-preview.png') }}" />
<x-admin-layout>
    <h3 class="text-gray-700 text-3xl font-medium">Paket</h3>

    <div class="mt-2">
        <div class="flex items-center justify-between">
            @role('admin')
                <a href="{{ route('paket.create') }}"
                    class="px-6 py-3 bg-green-600 rounded-md text-white font-medium tracking-wide hover:bg-green-500 transition duration-300">
                    Tambah Paket
                </a>
            @endrole

            @if (request()->routeIs('paket.index'))
                <div class="ml-auto">
                    <form method="GET" action="{{ route('paket.index') }}"
                        class="hidden lg:block relative max-w-md w-full">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="7" />
                                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                </svg>
                            </span>
                            <input type="text" id="search-paket" name="search" placeholder="Cari paket..."
                                value="{{ request('search') }}" autocomplete="off"
                                class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 text-gray-700 placeholder-gray-400
                                focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600 transition duration-200">
                            <ul id="autocomplete-results"
                                class="absolute left-0 right-0 mt-1 bg-white border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 rounded-md shadow-lg max-h-60 overflow-auto z-50 hidden transition-all duration-200"
                                role="listbox">
                            </ul>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        @foreach ($paket as $pkt)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="h-56 bg-gray-100 flex items-center justify-center overflow-hidden">
                    @if ($pkt->gambar)
                        @if (strpos($pkt->gambar, 'uploads/gambar/') === false)
                            <img src="{{ asset('images/' . $pkt->gambar) }}" alt="Gambar Paket"
                                class="object-cover w-full h-full transition duration-300 ease-in-out hover:brightness-50">
                        @else
                            <img src="{{ asset('storage/' . $pkt->gambar) }}" alt="Gambar Paket"
                                class="object-cover w-full h-full transition duration-300 ease-in-out hover:brightness-50">
                        @endif
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
                            <button onclick="toggleDeleteModal({{ $pkt->id }})"
                                class="flex items-center justify-center px-2.5 py-1.5 bg-red-500 text-white text-xs font-semibold rounded-md hover:bg-red-600 transition duration-300 ease-in-out h-[32px]">
                                <i class="bi bi-trash-fill mr-1"></i> Hapus
                            </button>

                            <button onclick="toggleEditModal({{ $pkt->id }})"
                                class="flex items-center justify-center px-2.5 py-1.5 bg-yellow-500 text-white text-xs font-semibold rounded-md hover:bg-yellow-600 transition duration-300 ease-in-out h-[32px]">
                                <i class="bi bi-pencil-square mr-1"></i> Edit
                            </button>
                        @endrole

                        <form action="{{ route('keranjang.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="paket_id" value="{{ $pkt->id }}">
                            <button type="submit"
                                class="flex items-center justify-center w-full px-2.5 py-1.5 bg-green-500 text-white text-xs font-semibold rounded-md hover:bg-green-600 transition duration-300 ease-in-out h-[32px] whitespace-nowrap">
                                <i class="bi bi-cart-plus-fill mr-1"></i> Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div id="deleteModal{{ $pkt->id }}" class="fixed inset-0 hidden overflow-y-auto bg-black/50">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="relative w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
                        <h2 class="text-lg mb-4 font-semibold">Apakah Anda yakin untuk menghapus?</h2>
                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="toggleDeleteModal({{ $pkt->id }})"
                                class="flex items-center justify-center px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 w-[80px] h-[40px]">Batal</button>
                            <form action="{{ route('paket.destroy', $pkt->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex items-center justify-center px-4 py-2 text-white bg-red-500 rounded hover:bg-red-700 h-[40px]">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="editModal{{ $pkt->id }}" class="fixed inset-0 hidden overflow-y-auto bg-black/50">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="relative w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
                        <h2 class="text-lg mb-4 font-semibold">Edit Paket</h2>

                        <form action="{{ route('paket.update', $pkt->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="nama_paket" class="block text-gray-600">Nama Paket</label>
                                <input type="text" name="nama_paket" id="nama_paket" value="{{ $pkt->nama_paket }}"
                                    class="w-full p-2 border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 rounded-md"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label for="kategori_id" class="block text-gray-600">Kategori</label>
                                <select name="kategori_id" id="kategori_id"
                                    class="w-full p-2 border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 rounded-md"
                                    required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($kategori as $kat)
                                        <option value="{{ $kat->id }}"
                                            {{ $pkt->kategori_id == $kat->id ? 'selected' : '' }}>
                                            {{ $kat->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="deskripsi" class="block text-gray-600">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi"
                                    class="w-full p-2 border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 rounded-md" required>{{ $pkt->deskripsi }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label for="modal" class="block text-gray-600">Modal</label>
                                <input type="number" name="modal" id="modal" value="{{ $pkt->modal }}"
                                    class="w-full p-2 border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 rounded-md"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label for="harga_jual" class="block text-gray-600">Harga Jual</label>
                                <input type="number" name="harga_jual" id="harga_jual"
                                    value="{{ $pkt->harga_jual }}"
                                    class="w-full p-2 border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 rounded-md"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label for="gambar" class="block text-gray-600">Gambar</label>
                                <input type="file" name="gambar" id="gambar"
                                    class="w-full p-2 border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 rounded-md"
                                    accept="image/*">
                                @error('gambar')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex justify-end space-x-2">
                                <button type="button" onclick="toggleEditModal({{ $pkt->id }})"
                                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Batal
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
    <script>
        // Bungkus semua script yang perlu dijalankan ulang dalam sebuah fungsi
        function initializePaketScripts() {
            const input = document.getElementById('search-paket');
            const results = document.getElementById('autocomplete-results');

            // Hanya inisialisasi jika elemen-elemennya ada di halaman
            if (input && results) {
                let debounceTimer;

                // Pastikan event listener tidak didaftarkan berkali-kali
                // Hapus event listener lama sebelum mendaftarkan yang baru
                input.removeEventListener('input', handleInput);
                document.removeEventListener('click', handleOutsideClick);

                // Definisikan fungsi handler terpisah
                function handleInput() {
                    const query = this.value.trim();
                    clearTimeout(debounceTimer);

                    if (query.length < 2) {
                        results.innerHTML = '';
                        results.classList.add('hidden');
                        return;
                    }

                    results.innerHTML = '<li class="p-2 text-gray-400 italic">Mencari...</li>';
                    results.classList.remove('hidden');

                    debounceTimer = setTimeout(() => {
                        fetch(`{{ route('paket.search') }}?q=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                results.innerHTML = '';

                                if (data.length === 0) {
                                    results.innerHTML =
                                        '<li class="p-2 text-gray-500 italic">Tidak ada hasil ditemukan</li>';
                                } else {
                                    data.forEach(item => {
                                        const li = document.createElement('li');
                                        li.textContent = item.nama_paket;
                                        li.className =
                                            'p-2 cursor-pointer hover:bg-indigo-100 text-gray-800 transition';
                                        li.dataset.id = item.id;

                                        li.addEventListener('click', function() {
                                            input.value = item.nama_paket;
                                            results.classList.add('hidden');
                                            input.form
                                                .submit(); // Optional: auto-submit
                                        });

                                        results.appendChild(li);
                                    });
                                }

                                results.classList.remove('hidden');
                            });
                    }, 300); // debounce 300ms
                }

                function handleOutsideClick(e) {
                    if (!input.contains(e.target) && !results.contains(e.target)) {
                        results.classList.add('hidden');
                    }
                }

                // Daftarkan event listener
                input.addEventListener('input', handleInput);
                document.addEventListener('click', handleOutsideClick);
            }

            // Inisialisasi fungsi toggle modal
            // Pastikan fungsi ini dideklarasikan di scope global atau diakses dengan window
            window.toggleDeleteModal = function(id) {
                const modal = document.getElementById('deleteModal' + id);
                if (modal) {
                    modal.classList.toggle('hidden');
                }
            };

            window.toggleEditModal = function(id) {
                const modal = document.getElementById('editModal' + id);
                if (modal) {
                    modal.classList.toggle('hidden');
                }
            };
        }

        // Jalankan script saat DOMContentLoaded (untuk muatan halaman awal)
        document.addEventListener('DOMContentLoaded', initializePaketScripts);

        // Jalankan script saat Turbo memuat halaman baru
        document.addEventListener('turbo:load', initializePaketScripts);
    </script>


</x-admin-layout>