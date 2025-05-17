<x-admin-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detail Pesanan #{{ $pesanan->id }}</h2>
            <div class="text-sm text-gray-500">
                Terakhir diubah: {{ $pesanan->updated_at->format('d M Y H:i') }}
            </div>
        </div>

        @if (session('message'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 border border-green-300">
                {{ session('message') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4 border border-red-300">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Informasi Pesanan -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Pesanan</h3>
                <div class="space-y-3">
                    <div class="flex">
                        <span class="w-1/3 font-medium">Status:</span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($pesanan->status === 'menunggu') bg-yellow-100 text-yellow-800
                            @elseif($pesanan->status === 'disetujui') bg-blue-100 text-blue-800
                            @elseif($pesanan->status === 'ditolak') bg-red-100 text-red-800
                            @elseif($pesanan->status === 'selesai') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($pesanan->status) }}
                        </span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Tanggal Pesan:</span>
                        <span>{{ $pesanan->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Nama Acara:</span>
                        <span>{{ $pesanan->jadwal->nama_acara }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Tanggal Acara:</span>
                        <span>{{ $pesanan->jadwal->tanggal }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Nama Pemesan:</span>
                        <span>{{ $pesanan->user->nama_lengkap }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Nomor Telepon:</span>
                        <span>{{ $pesanan->user->telepon }}</span>
                    </div>
                    @if($pesanan->status === 'ditolak' && $pesanan->alasan_tolak)
                    <div class="flex">
                        <span class="w-1/3 font-medium">Alasan Penolakan:</span>
                        <span class="text-red-600">{{ $pesanan->alasan_tolak }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Pembayaran -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Pembayaran</h3>
                <div class="space-y-3">
                    <div class="flex">
                        <span class="w-1/3 font-medium">Total Harga:</span>
                        <span>Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Metode Bayar:</span>
                        <span>{{ $pesanan->pembayaran->metode_bayar ?? '-' }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Status Pembayaran:</span>
                        <span>{{ $pesanan->pembayaran->status ?? '-' }}</span>
                    </div>
                    
                    @if($pesanan->bukti_transaksi)
                    <div class="mt-4">
                        <div class="font-medium mb-2">Bukti Transfer:</div>
                        <img src="{{ asset('storage/' . $pesanan->bukti_transaksi) }}" 
                             alt="Bukti Transfer" 
                             class="max-w-full h-auto border rounded">
                        
                        @role('customer')
                        @if($pesanan->status === 'menunggu')
                        <form action="{{ route('pesanan.updateBukti', $pesanan->id) }}" 
                              method="POST" 
                              enctype="multipart/form-data"
                              class="mt-4">
                            @csrf
                            @method('PUT')
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700">Update Bukti Transfer</label>
                                <input type="file" name="bukti_transfer" required 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
                                Upload Bukti Baru
                            </button>
                        </form>
                        @endif
                        @endrole
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Update Status (Admin Only) -->
        @role('admin')
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Update Status Pesanan</h3>
            <form action="{{ route('pesanan.updateStatus', $pesanan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @foreach(['menunggu', 'disetujui', 'ditolak', 'selesai', 'dibatalkan'] as $status)
                                <option value="{{ $status }}" {{ $pesanan->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div id="alasan-tolak-container" class="{{ $pesanan->status !== 'ditolak' ? 'hidden' : '' }}">
                        <label for="alasan_tolak" class="block text-sm font-medium text-gray-700">Alasan Penolakan</label>
                        <textarea name="alasan_tolak" id="alasan_tolak" rows="2"
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ $pesanan->alasan_tolak }}</textarea>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
        @endrole

        <!-- Tombol Cancel untuk Customer -->
        @role('customer')
        @if(in_array($pesanan->status, ['menunggu', 'disetujui']))
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Batalkan Pesanan</h3>
            <form action="{{ route('pesanan.cancel', $pesanan->id) }}" method="POST" 
                  onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded text-sm hover:bg-red-600">
                    Batalkan Pesanan
                </button>
            </form>
        </div>
        @endif
        @endrole

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