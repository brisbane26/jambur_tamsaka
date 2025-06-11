<link rel="icon" type="image/png" href="{{ asset('images/favicon-removebg-preview.png') }}" />
<style>
@media print {
    /* Gunakan seluruh lebar halaman kertas */
    body {
        zoom: 75%;
    }

    table {
        width: 100%;
        table-layout: auto;
        font-size: 12px;
    }

    th, td {
        padding: 4px 6px;
        word-wrap: break-word;
    }

    .print\:hidden {
        display: none !important;
    }

    .whitespace-nowrap {
        white-space: normal !important;
    }

    /* Hapus pembatas atau margin yang menghalangi tabel */
    .p-6, .px-6, .py-4 {
        padding: 2px !important;
    }

    /* Gunakan font kecil agar semua kolom muat */
    body, table {
        font-size: 10px;
    }
}
</style>

<x-admin-layout>
    <div class="p-1">
        <div class="mt-2">
            <h3 class="text-gray-700 text-3xl font-medium">Laporan Keuntungan</h3>
            <div class="flex items-center justify-end mb-5">
            </div>
            <form action="{{ route('laporan.index') }}" method="GET" class="mb-4 flex flex-wrap items-center gap-4 print:hidden">
                <label for="filter" class="font-medium">Filter Waktu:</label>
                <select name="filter" id="timeFilter" class="border rounded px-3 py-1 w-[180px]">
                    <option value="">Semua</option>
                    <option value="minggu" {{ request('filter') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan" {{ request('filter') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun" {{ request('filter') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="custom" {{ request('filter') == 'custom' ? 'selected' : '' }}>Rentang Tanggal</option>
                </select>

                <div id="customDateRange" class="flex items-center gap-2 {{ request('filter') == 'custom' ? '' : 'hidden' }}">
                    <label for="start_date" class="font-medium">Dari:</label>
                    <input type="date" name="start_date" id="start_date"
                           value="{{ request('start_date') }}"
                           class="border rounded px-3 py-1">

                    <label for="end_date" class="font-medium">Sampai:</label>
                    <input type="date" name="end_date" id="end_date"
                           value="{{ request('end_date') }}"
                           class="border rounded px-3 py-1">
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded">
                    Filter
                </button>
            </form>
        </div>

        <div class="mb-4 text-gray-800">
    @if ($filterDescription)
        <p class="font-semibold">{{ $filterDescription }}@if($filterDescription != 'Semua Waktu' && $filterDescription != 'Tidak ada data laporan.'):@endif</p>
    @endif

            @if ($startDateForDisplay && $endDateForDisplay && $filterDescription != 'Semua Waktu')
                <p>
                    Dari: {{ $startDateForDisplay->translatedFormat('d F Y') }}
                    Sampai: {{ $endDateForDisplay->translatedFormat('d F Y') }}
                </p>
            @elseif ($filterDescription === 'Tidak ada data laporan.')
                <p>{{ $filterDescription }}</p>
            @endif
        </div>

        <div class="flex justify-end mb-4 print:hidden">
            <span class="text-gray-700 text-lg font-semibold">Total Pesanan: {{ $totalPesanan }}</span>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-auto">
            <div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID Pesanan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pemesan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Acara
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Acara
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pendapatan Kotor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pendapatan Bersih
                            </th>
                            <th class="px-6 py-3 print:hidden text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pesanans as $pesanan)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    #{{ $pesanan->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $pesanan->user->nama_lengkap }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $pesanan->jadwal->nama_acara }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $pesanan->jadwal->tanggal }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    Rp{{ number_format($pesanan->total_keuntungan, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm print:hidden font-medium">
                                    <a href="{{ route('laporan.detail', $pesanan->id) }}" class="text-blue-600 hover:text-blue-900">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    Tidak ada data pesanan untuk laporan.
                                </td>
                            </tr>
                        @endforelse

                        <tr class="bg-gray-100 ">
                            <td colspan="5" class="px-6 py-4 text-right font-bold">
                                Total Pendapatan Bersih:
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold">
                                Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                        <tr class="bg-gray-100 ">
                            <td colspan="5" class="px-6 py-4 text-right font-bold">
                                Total Pesanan:
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold">
                                {{ $totalPesanan }}
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-between items-center mt-4">
            <button onclick="window.print()" class="ml-6 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 print:hidden">
                Cetak
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const timeFilter = document.getElementById('timeFilter');
            const customDateRange = document.getElementById('customDateRange');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            function getFormattedDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function toggleCustomDateRange() {
                if (timeFilter.value === 'custom') {
                    customDateRange.classList.remove('hidden');
                    // Hanya isi tanggal jika inputnya kosong ATAU jika
                    // filter sebelumnya bukan 'custom' (artinya user baru beralih ke 'custom')
                    if (!startDateInput.value || !endDateInput.value || (timeFilter.dataset.lastFilter !== 'custom' && timeFilter.value === 'custom')) {
                        const today = new Date();
                        const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                        const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

                        startDateInput.value = getFormattedDate(firstDayOfMonth);
                        endDateInput.value = getFormattedDate(lastDayOfMonth);
                    }
                } else {
                    customDateRange.classList.add('hidden');
                }
                // Simpan filter terakhir yang dipilih untuk digunakan pada perubahan berikutnya
                timeFilter.dataset.lastFilter = timeFilter.value;
            }

            // Panggil saat halaman dimuat untuk menyesuaikan tampilan awal
            toggleCustomDateRange();

            // Panggil saat dropdown filter waktu berubah
            timeFilter.addEventListener('change', toggleCustomDateRange);
        });
    </script>
</x-admin-layout>