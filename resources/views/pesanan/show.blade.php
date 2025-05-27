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
                        <span
                            class="px-2 inline-flex text-xs leading-7 font-semibold rounded-full 
                            @if ($pesanan->status === 'menunggu') bg-yellow-100 text-yellow-800
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
                        <span class="w-1/3 font-medium">Tanggal Acara</span>
                        <span>: {{ \Carbon\Carbon::parse($pesanan->jadwal->tanggal)->format('d M Y') }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Nama Pemesan</span>
                        <span>: {{ $pesanan->user->nama_lengkap }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Nomor Telepon</span>
                        <span>: {{ $pesanan->user->telepon }}</span>
                    </div>
                    @if ($pesanan->status === 'ditolak' && $pesanan->alasan_tolak)
                        <div class="flex">
                            <span class="w-1/3 font-medium">Alasan Penolakan</span>
                            <span class="text-red-600">: {{ $pesanan->alasan_tolak }}</span>
                        </div>
                    @endif
                    @if ($pesanan->detailPesanan->first()->catatan)
                        <div class="mt-3">
                            <div class="flex">
                                <span class="w-1/3 font-medium">Catatan Admin</span>
                                <span>:</span>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-1">
                                <p class="text-blue-700 whitespace-pre-line">
                                    {{ $pesanan->detailPesanan->first()->catatan }}</p>
                            </div>
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

                    @if ($pesanan->bukti_transaksi)
                        <div class="mt-4">
                            <div class="font-medium mb-2 flex">
                                <span class="w-1/3 font-medium">Bukti Transfer</span>
                                <span>:</span>
                            </div>
                            <img src="{{ asset('storage/' . $pesanan->bukti_transaksi) }}" alt="Bukti Transfer"
                                class="max-w-full h-auto border rounded">

                            @role('customer')
                                @if ($pesanan->status === 'menunggu')
                                    <form action="{{ route('pesanan.updateBukti', $pesanan->id) }}" method="POST"
                                        enctype="multipart/form-data" class="mt-4">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-2">
                                            <label class="block text-sm font-medium text-gray-700">Update Bukti
                                                Transfer</label>
                                            <input type="file" name="bukti_transfer" required
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <button type="submit"
                                            class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
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
                                @foreach (['menunggu', 'disetujui', 'ditolak', 'selesai', 'dibatalkan'] as $status)
                                    <option value="{{ $status }}"
                                        {{ $pesanan->status === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="alasan-tolak-container" class="{{ $pesanan->status !== 'ditolak' ? 'hidden' : '' }}">
                            <label for="alasan_tolak" class="block text-sm font-medium text-gray-700">Alasan
                                Penolakan</label>
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
        <div class="p-6">
            @role('customer')
                @if (in_array($pesanan->status, ['menunggu', 'disetujui']))
                    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Batalkan Pesanan</h3>
                        <button type="button" onclick="openCancelModal({{ $pesanan->id }})"
                            class="bg-red-500 text-white px-4 py-2 rounded text-sm hover:bg-red-600">
                            Batalkan Pesanan
                        </button>
                    </div>
                @endif
            @endrole

        </div>

        <div id="cancel-modal-{{ $pesanan->id }}"
            class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
                <h2 class="text-lg mb-4 font-semibold">Konfirmasi Pembatalan Pesanan</h2>
                <p class="text-gray-700 mb-6">Apakah Anda yakin ingin membatalkan pesanan ini? Aksi ini tidak dapat
                    dibatalkan.</p>

                <div class="flex justify-end space-x-2">
                    <button onclick="closeCancelModal({{ $pesanan->id }})"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Batal
                    </button>
                    <form action="{{ route('pesanan.cancel', $pesanan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Ya, Batalkan
                        </button>
                    </form>
                </div>
            </div>
        </div>


        <!-- Detail Paket -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Detail Paket</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Paket</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kuantitas</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga Satuan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($pesanan->detailPesanan as $detail)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $detail->paket->nama_paket }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $detail->kuantitas }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    Rp{{ number_format($detail->harga * $detail->kuantitas, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="font-semibold bg-gray-50">
                            <td colspan="3" class="px-6 py-4 text-right">Total</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Invoice/Struk (hanya jika status disetujui) -->
        @if ($pesanan->status === 'disetujui')
            <div class="bg-white shadow-md rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Invoice / Kuitansi
                </h3>
                <div id="invoice-area" class="bg-gray-50 p-6 rounded border mb-4">
                    <div class="flex justify-between mb-2">
                        <div>
                            <div class="font-bold text-lg text-blue-700 mb-1">Jambur Tamsaka</div>
                            <div class="text-sm text-gray-600">Jl. [Alamat Jambur], Medan</div>
                            <div class="text-sm text-gray-600">Telp: 08xx-xxxx-xxxx</div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold">No. Pesanan: #{{ $pesanan->id }}</div>
                            <div class="text-sm">Tanggal: {{ $pesanan->created_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="mb-2">
                        <span class="font-medium">Nama Pemesan:</span> {{ $pesanan->user->nama_lengkap }}<br>
                        <span class="font-medium">No. Telepon:</span> {{ $pesanan->user->telepon }}<br>
                        <span class="font-medium">Nama Acara:</span> {{ $pesanan->jadwal->nama_acara }}<br>
                        <span class="font-medium">Tanggal Acara:</span> {{ $pesanan->jadwal->tanggal }}
                    </div>
                    <table class="w-full text-sm mb-2">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-2 text-left">Paket</th>
                                <th class="p-2 text-center">Qty</th>
                                <th class="p-2 text-right">Harga</th>
                                <th class="p-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pesanan->detailPesanan as $detail)
                                <tr>
                                    <td class="p-2">{{ $detail->paket->nama_paket }}</td>
                                    <td class="p-2 text-center">{{ $detail->kuantitas }}</td>
                                    <td class="p-2 text-right">Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td class="p-2 text-right">
                                        Rp{{ number_format($detail->harga * $detail->kuantitas, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="font-bold bg-gray-100">
                                <td colspan="3" class="p-2 text-right">Total</td>
                                <td class="p-2 text-right">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-2 text-xs text-gray-500">* Invoice ini sah tanpa tanda tangan & dapat digunakan
                        sebagai bukti pembayaran.</div>
                </div>
                <div class="flex gap-2">
                    <button onclick="printInvoice()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm">Print</button>
                    <button onclick="downloadInvoice()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm">Download
                        PDF</button>
                </div>
            </div>
            <script>
                function printInvoice() {
                    var printContents = document.getElementById('invoice-area').innerHTML;
                    var originalContents = document.body.innerHTML;
                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;
                    location.reload();
                }

                function downloadInvoice() {
                    var printContents = document.getElementById('invoice-area').innerHTML;
                    var win = window.open('', '', 'height=700,width=900');
                    win.document.write('<html><head><title>Invoice Jambur Tamsaka</title>');
                    win.document.write(
                        '<style>body{font-family:sans-serif;} .p-2{padding:8px;} .text-right{text-align:right;} .text-center{text-align:center;} .font-bold{font-weight:bold;} .bg-gray-100{background:#f3f4f6;} .mt-2{margin-top:8px;} .mb-2{margin-bottom:8px;} .rounded{border-radius:8px;} .border{border:1px solid #e5e7eb;} .w-full{width:100%;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #e5e7eb;} .text-xs{font-size:12px;}</style>'
                        );
                    win.document.write('</head><body>');
                    win.document.write(printContents);
                    win.document.write('</body></html>');
                    win.document.close();
                    win.focus();
                    win.print();
                    win.close();
                }
            </script>
        @endif

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

    <script>
        function openCancelModal(id) {
            document.getElementById('cancel-modal-' + id).classList.remove('hidden');
        }

        function closeCancelModal(id) {
            document.getElementById('cancel-modal-' + id).classList.add('hidden');
        }
    </script>
</x-admin-layout>
