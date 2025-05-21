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
            @foreach($keranjangs as $index => $keranjang)
            <tr class="border-t border-gray-200 hover:bg-gray-50">
                <td class="py-3 px-4 border">{{ $loop->iteration }}</td>
                <td class="py-3 px-4 border">
                    <img src="{{ asset('images/' . $keranjang->paket->gambar) }}" alt="{{ $keranjang->paket->nama_paket }}" class="w-20 h-20 object-cover rounded">
                </td>
                <td class="py-3 px-4 border">{{ $keranjang->paket->nama_paket }}</td>
                <td class="py-3 px-4 border">
                    <div class="flex items-center justify-center gap-2">
                        <input key="qty-{{ $keranjang->id }}-{{ $keranjang->kuantitas }}"
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
                    <button wire:click="removeItem({{ $keranjang->id }})" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                </td>
            </tr>
            @endforeach
            <tr class="bg-gray-100 font-semibold">
                <td colspan="5" class="text-right py-3 px-4 border">Total Harga Semua Paket:</td>
                <td colspan="2" class="py-3 px-4 border">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
        <!-- Add this checkout button section -->
    <div class="mt-6 flex justify-end">
        <a href="{{route('checkout.index')}}" 
           class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 
                  {{ count($keranjangs) === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
           @if(count($keranjangs) === 0) onclick="return false;" @endif>
            <i class="bi bi-cart-check mr-2"></i> Checkout
        </a>
    </div>
</div>