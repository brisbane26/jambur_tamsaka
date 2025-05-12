<div>
    <h3 class="text-gray-700 text-3xl font-medium">Keranjang Belanja</h3>

    <table class="min-w-full bg-white mt-8">
        <thead>
            <tr>
                <th class="py-2">No</th>
                <th class="py-2">Gambar</th>
                <th class="py-2">Nama Paket</th>
                <th class="py-2">Kuantitas</th>
                <th class="py-2">Harga Satuan</th>
                <th class="py-2">Total Harga</th>
                <th class="py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($keranjangs as $index => $keranjang)
            <tr>
                <td class="py-2">{{ $index + 1 }}</td>
                <td class="py-2">
                    <img src="{{ $keranjang->paket->gambar_url }}" alt="{{ $keranjang->paket->nama_paket }}" width="100">
                </td>
                <td class="py-2">{{ $keranjang->paket->nama_paket }}</td>
                <td class="py-2 flex items-center gap-2">
                    <button wire:click="updateQuantity({{ $keranjang->id }}, 'decrement')" class="px-2 py-1 bg-red-500 text-white rounded">-</button>
                    <input type="text" value="{{ $keranjang->kuantitas }}" 
                        class="w-12 text-center border border-gray-300 rounded" 
                        inputmode="numeric" 
                        pattern="[0-9]*"
                        readonly>
                    <button wire:click="updateQuantity({{ $keranjang->id }}, 'increment')" class="px-2 py-1 bg-green-500 text-white rounded">+</button>
                </td>
                <td class="py-2">Rp {{ number_format($keranjang->paket->harga_jual, 0, ',', '.') }}</td>
                <td class="py-2">Rp {{ number_format($keranjang->paket->harga_jual * $keranjang->kuantitas, 0, ',', '.') }}</td>
                <td class="py-2">
                    <button wire:click="updateQuantity({{ $keranjang->id }}, 'decrement')" class="px-2 py-1 bg-red-500 text-white rounded">Hapus</button>
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="5" class="text-right font-bold py-2">Total Harga Semua Paket:</td>
                <td colspan="2" class="py-2">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>
