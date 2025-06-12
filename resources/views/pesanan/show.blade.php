<x-admin-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detail Pesanan #{{ $pesanan->id }}</h2>
            <div class="text-sm text-gray-500">
                Terakhir diubah: {{ $pesanan->updated_at->format('d M Y H:i') }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Pesanan</h3>
                <div class="space-y-3">
                    <div class="flex">
                        <span class="w-1/3 font-medium">Status</span>
                        <span class="mr-2">:</span> {{-- Tambahkan margin-right untuk jarak --}}
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
                        <span class="mr-2">:</span> {{ $pesanan->created_at->format('d M Y H:i') }}
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Nama Acara</span>
                        <span class="mr-2">:</span> {{ $pesanan->jadwal->nama_acara }}
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Tanggal Acara</span>
                        <span class="mr-2">:</span> {{ \Carbon\Carbon::parse($pesanan->jadwal->tanggal)->format('d M Y') }}
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Nama Pemesan</span>
                        <span class="mr-2">:</span> {{ $pesanan->user->nama_lengkap }}
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Nomor Telepon</span>
                        <span class="mr-2">:</span> {{ $pesanan->user->telepon }}
                    </div>
                    @if ($pesanan->status === 'ditolak' && $pesanan->alasan_tolak)
                        <div class="flex items-start"> {{-- Gunakan items-start agar konten multi-baris rapi --}}
                            <span class="w-1/3 font-medium">Alasan Penolakan</span>
                            <span class="mr-2 text-red-600">:</span> {{-- Pindahkan titik dua ke span terpisah dengan margin --}}
                            <span class="w-2/3 text-red-600"> {{-- Beri lebar pada konten alasan agar sejajar --}}
                                {{ $pesanan->alasan_tolak }}
                            </span>
                        </div>
                    @endif
                    {{-- Perbaiki bagian catatan admin jika ada, agar rapi juga. Pastikan kolom 'catatan' ada di model Pesanan --}}
                    @if ($pesanan->catatan) {{-- Menggunakan $pesanan->catatan karena sudah dipindahkan di checkout_store --}}
                        <div class="flex items-start">
                            <span class="w-1/3 font-medium">Catatan Admin</span>
                            <span class="mr-2">:</span>
                            <span class="w-2/3">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-1 text-blue-700">
                                    {{-- Gunakan nl2br untuk menampilkan newline jika ada --}}
                                    {!! nl2br(e($pesanan->catatan)) !!} 
                                </div>
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Pembayaran</h3>
                <div class="space-y-3">
                    <div class="flex">
                        <span class="w-1/3 font-medium">Total Harga</span>
                        <span class="mr-2">:</span> Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-medium">Metode Bayar</span>
                        <span class="mr-2">:</span> {{ $pesanan->pembayaran->metode_bayar ?? '-' }}
                    </div>

                    @if ($pesanan->bukti_transaksi)
                        <div class="mt-4">
                            <div class="flex items-start"> {{-- Gunakan flex dan items-start di sini juga --}}
                                <span class="w-1/3 font-medium">Bukti Transfer</span>
                                <span class="mr-2">:</span>
                                <div class="w-2/3">
                                    <img src="{{ asset('storage/' . $pesanan->bukti_transaksi) }}" alt="Bukti Transfer"
                                        class="max-w-full h-auto border rounded">
                                </div>
                            </div>

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

        @role('admin')
            @if (!empty($statusOptions))
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Update Status Pesanan</h3>
                    <form action="{{ route('pesanan.updateStatus', $pesanan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Ubah Status ke</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('status', $pesanan->status) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div id="alasan-tolak-container" class="{{ old('status', $pesanan->status) == 'ditolak' ? '' : 'hidden' }}">
                                <label for="alasan_tolak" class="block text-sm font-medium text-gray-700">Alasan Penolakan</label>
                                <textarea name="alasan_tolak" id="alasan_tolak" rows="2"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    >{{ old('alasan_tolak', $pesanan->alasan_tolak) }}</textarea> {{-- Tambahkan $pesanan->alasan_tolak sebagai fallback old value --}}
                                
                                @error('alasan_tolak')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-2">Update Status Pesanan</h3>
                    <div class="alert alert-info bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded relative" role="alert">
                        Status pesanan adalah <strong>"{{ ucfirst($pesanan->status) }}"</strong> dan tidak ada aksi lebih lanjut yang dapat dilakukan.
                    </div>
                </div>
            @endif

            @push('scripts')
                <script>
                    document.addEventListener('turbo:load', function() {
                        const statusSelect = document.getElementById('status');
                        const alasanTolakContainer = document.getElementById('alasan-tolak-container');

                        if (statusSelect && alasanTolakContainer) {
                            const checkStatusVisibility = () => {
                                if (statusSelect.value === 'ditolak') {
                                    alasanTolakContainer.classList.remove('hidden');
                                } else {
                                    alasanTolakContainer.classList.add('hidden');
                                }
                            };

                            checkStatusVisibility(); // Jalankan saat halaman dimuat
                            statusSelect.addEventListener('change', checkStatusVisibility);
                        }
                    });
                </script>
            @endpush
        @endrole

        {{-- Ini adalah div yang terpotong di respons sebelumnya, telah dikembalikan --}}
        {{-- Pastikan ini tidak duplikat dengan p-6 paling atas --}}
        {{-- Jika p-6 paling atas sudah mengelilingi seluruh konten, ini bisa dihapus --}}
        {{-- Berdasarkan struktur asli Anda, div ini sepertinya tidak diperlukan dan bisa menyebabkan layout pecah --}}
        {{-- Jadi, saya akan menghapusnya dan merapikan struktur div di bawahnya --}}
        {{-- <div class="p-6"> --}} 
            @role('customer')
                @if (in_array($pesanan->status, ['menunggu', 'disetujui']))
                    <div class="bg-white shadow-md rounded-lg p-6 mt-6 mb-6"> {{-- Tambahkan mt-6 jika ini di luar grid --}}
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Batalkan Pesanan</h3>
                        <button type="button" onclick="openCancelModal({{ $pesanan->id }})"
                            class="bg-red-500 text-white px-4 py-2 rounded text-sm hover:bg-red-600">
                            Batalkan Pesanan
                        </button>
                    </div>
                @endif
            @endrole

        {{-- </div> --}} {{-- Penutup div yang kemungkinan menyebabkan masalah --}}

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


        <div class="bg-white shadow-md rounded-lg p-6 mt-6"> {{-- Tambahkan mt-6 untuk jarak dari elemen sebelumnya --}}
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

        {{-- Pastikan script admin di sini tidak duplikat dengan yang ada di @role('admin') di atas --}}
        {{-- Karena Anda memiliki @push('scripts') di dalam @role('admin') dan juga di luar @role('admin') --}}
        {{-- Sebaiknya semua script admin berada di satu tempat di dalam @role('admin') --}}
        {{-- Saya akan memindahkan script ini ke dalam @role('admin') paling atas --}}
        {{-- Ini adalah script untuk modal pembatalan yang sebelumnya ada di luar @role('admin') --}}
        <script>
            function openCancelModal(id) {
                document.getElementById('cancel-modal-' + id).classList.remove('hidden');
            }

            function closeCancelModal(id) {
                document.getElementById('cancel-modal-' + id).classList.add('hidden');
            }
        </script>
    </div> {{-- Penutup div class="p-6" paling awal --}}
</x-admin-layout>