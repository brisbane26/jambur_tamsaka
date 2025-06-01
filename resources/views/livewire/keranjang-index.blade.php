<div class="w-full p-4">
    <h3 class="text-gray-700 text-3xl font-medium mb-6">Keranjang Belanja</h3>

    <table class="w-full bg-white border border-gray-200 rounded-lg shadow-md">
        <thead class="bg-gray-100">
            <tr>
                <th class="py-3 px-4 text-left text-gray-600 font-semibold border">No</th>
                <th class="py-3 px-4 text-left text-gray-600 font-semibold border">Gambar</th>
                <th class="py-3 px-4 text-left text-gray-600 font-semibold border">Nama Paket</th>
                <th class="py-3 px-4 text-left text-gray-600 font-semibold border">Kuantitas</th>
                <th class="py-3 px-4 text-left text-gray-600 font-semibold border">Harga Satuan</th>
                <th class="py-3 px-4 text-left text-gray-600 font-semibold border">Total Harga</th>
                <th class="py-3 px-4 text-left text-gray-600 font-semibold border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($keranjangs as $keranjang)
            <tr class="border-t border-gray-200 hover:bg-gray-50">
                <td class="py-3 px-4 border">{{ $loop->iteration }}</td>
                <td class="py-3 px-4 border">
                    <img src="{{ asset('images/' . $keranjang->paket->gambar) }}" class="w-20 h-20 object-cover rounded">
                </td>
                <td class="py-3 px-4 border">{{ $keranjang->paket->nama_paket }}</td>
                <td class="py-3 px-4 border">
                    <div class="flex items-center justify-center gap-2">
                        <input
                            key="qty-{{ $keranjang->id }}-{{ $keranjang->kuantitas }}"
                            type="number"
                            value="{{ $keranjang->kuantitas }}"
                            wire:input.debounce.500ms="updateQuantityLive({{ $keranjang->id }}, $event.target.value)"
                            min="1" max="1000"
                            oninput="this.value = Math.abs(this.value)">
                    </div>
                </td>
                <td class="py-3 px-4 border">Rp {{ number_format($keranjang->paket->harga_jual, 0, ',', '.') }}</td>
                <td class="py-3 px-4 border">Rp {{ number_format($keranjang->paket->harga_jual * $keranjang->kuantitas, 0, ',', '.') }}</td>
                <td class="py-3 px-4 border">
                    <button onclick="openModal({{ $keranjang->id }})" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                </td>
            </tr>

            <!-- Modal Konfirmasi -->
            <div id="modal-{{ $keranjang->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
                    <h2 class="text-lg mb-4 font-semibold">Konfirmasi Hapus</h2>
                    <p class="mb-6 text-gray-600">Apakah Anda yakin ingin menghapus paket dari keranjang?</p>

                    <div class="flex justify-end space-x-2">
                        <button onclick="closeModal({{ $keranjang->id }})"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                            Batal
                        </button>
                        <button wire:click="confirmDelete({{ $keranjang->id }})"
                            onclick="closeModal({{ $keranjang->id }})"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
            @endforeach

            <tr class="bg-gray-100 font-semibold">
                <td colspan="5" class="text-right py-3 px-4 border">Total Harga Semua Paket:</td>
                <td colspan="2" class="py-3 px-4 border">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="mt-6 flex justify-end">
        <a href="{{ route('checkout.index') }}" 
           class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 
                  {{ count($keranjangs) === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
           @if(count($keranjangs) === 0) onclick="return false;" @endif>
            <i class="bi bi-cart-check mr-2"></i> Proses
        </a>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById('modal-' + id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById('modal-' + id).classList.add('hidden');
    }
</script>
