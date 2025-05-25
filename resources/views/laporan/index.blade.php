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
    <div class="p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold">Laporan Keuntungan</h2>
            <div class="flex items-center justify-end mb-5">
            </div>
            <form action="{{ route('laporan.index') }}" method="GET" class="mb-4 flex items-center gap-4">
                <label for="status" class="font-medium">Filter Waktu:</label>
                <select name="filter" onchange="this.form.submit()"
                    class="border rounded px-3 py-1 w-40">
                    <option value="">Semua</option>
                    <option value="minggu" {{ request('filter') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan" {{ request('filter') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun" {{ request('filter') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </form>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
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

                            {{-- Tambahkan baris total di sini --}}
                            <tr class="bg-gray-100 ">
                                <td colspan="5" class="px-6 py-4 text-right">
                                    Total Pendapatan Bersih:
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tambahan tombol print di kiri dan total pendapatan di kanan, align sesuai kolom --}}
        <div class="flex justify-between items-center mt-4">
            <button onclick="window.print()" class="ml-6 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 print:hidden">
                Cetak
            </button>
        </div>
    </div>
</x-admin-layout>
