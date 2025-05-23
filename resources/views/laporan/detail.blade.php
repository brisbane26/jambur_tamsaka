<x-admin-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detail Pesanan #{{ $pesanan->id }}</h2>
            <div class="text-sm text-gray-500">
                Terakhir diubah: {{ $pesanan->updated_at->format('d M Y H:i') }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Informasi Pesanan -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Pesanan</h3>
                <div class="space-y-3">
                    <div class="flex">
                        <span class="w-1/3 font-medium">Status</span>
                        <span>:</span>
                        <span></span>
                        <span class="px-2 inline-flex text-xs leading-7 font-semibold rounded-full 
                            @if($pesanan->status === 'menunggu') bg-yellow-100 text-yellow-800
                            @elseif($pesanan->status === 'disetujui') bg-blue-100 text-blue-800
                            @elseif($pesanan->status === 'ditolak') bg-red-100 text-red-800
                            @elseif($pesanan->status === 'selesai') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($pesanan->status) }}
                        </span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Tanggal Pesan</span>
                        <span>: {{ $pesanan->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Nama Acara</span>
                        <span>: {{ $pesanan->jadwal->nama_acara }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Tanggal Acara:</span>
                        <span>: {{ $pesanan->jadwal->tanggal }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Nama Pemesan:</span>
                        <span>: {{ $pesanan->user->nama_lengkap }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Nomor Telepon:</span>
                        <span>: {{ $pesanan->user->telepon }}</span>
                    </div>
                    @if($pesanan->status === 'ditolak' && $pesanan->alasan_tolak)
                    <div class="flex">
                        <span class="w-1/3 font-medium">Alasan Penolakan:</span>
                        <span class="text-red-600">: {{ $pesanan->alasan_tolak }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Pembayaran -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Pembayaran</h3>
                <div class="space-y-3">
                    <div class="flex">
                        <span class="w-1/3 font-medium">Total Harga</span>
                        <span>: Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Metode Bayar</span>
                        <span>: {{ $pesanan->pembayaran->metode_bayar ?? '-' }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Status Pembayaran</span>
                        <span>: {{ $pesanan->pembayaran->status ?? '-' }}</span>
                    </div>
                    
                    @if($pesanan->bukti_transaksi)
                    <div class="mt-4">
                        <div class="font-medium mb-2 flex">
                            <span class="w-1/3 font-medium">Bukti Transfer</span>
                            <span>:</span>
                        </div>
                        <img src="{{ asset('storage/' . $pesanan->bukti_transaksi) }}" 
                             alt="Bukti Transfer" 
                             class="max-w-full h-auto border rounded">
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Status Pesanan</h3>
    <p class="text-gray-800">
        Status: <span class="font-semibold capitalize">{{ $pesanan->status }}</span>
    </p>
</div>

        <!-- Detail Paket -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Detail Paket</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Paket</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuantitas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pesanan->detailPesanan as $detail)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $detail->paket->nama_paket }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $detail->kuantitas }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($detail->harga * $detail->kuantitas, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="font-semibold bg-gray-50">
                            <td colspan="3" class="px-6 py-4 text-right">Total</td>
                            <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <script>

        @role('admin')
        @push('scripts')
        <script>
            document.getElementById('status').addEventListener('change', function() {
                const alasanTolakContainer = document.getElementById('alasan-tolak-container');
                if (this.value === 'ditolak') {
                    alasanTolakContainer.classList.remove('hidden');
                } else {
                    alasanTolakContainer.classList.add('hidden');
                }
            });
        </script>
        @endpush
        @endrole
    </div>
</x-admin-layout>