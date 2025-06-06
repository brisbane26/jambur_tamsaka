  <link rel="icon" type="image/png" href="{{ asset('images/favicon-removebg-preview.png') }}" />
<x-admin-layout>
    <div class="p-1">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-gray-700 text-3xl font-medium">Riwayat Pesanan</h3>
            @role('customer')
            <a href="{{ route('paket.index') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                Buat Pesanan Baru
            </a>
            @endrole
        </div>
        <form method="GET" class="mb-4 flex items-center gap-4">
    <label for="status" class="font-medium">Filter Status:</label>
    <select name="status" id="status" onchange="this.form.submit()" class="border rounded px-3 py-1 w-40">
        <option value="">Semua</option>
        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
    </select>
</form>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID Pesanan
                            </th>
                            @role('admin')
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pemesan
                            </th>
                            @endrole
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Pesan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Acara
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Acara
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Harga
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                @role('admin')
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $pesanan->user->nama_lengkap }}
                                </td>
                                @endrole
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $pesanan->created_at->format('d M Y') }}
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
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($pesanan->status === 'menunggu') bg-yellow-100 text-yellow-800
                                        @elseif($pesanan->status === 'disetujui') bg-blue-100 text-blue-800
                                        @elseif($pesanan->status === 'ditolak') bg-red-100 text-red-800
                                        @elseif($pesanan->status === 'selesai') bg-green-100 text-green-800
                                        @elseif($pesanan->status === 'dibatalkan') bg-gray-100 text-gray-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($pesanan->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('pesanan.show', $pesanan->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">
                                        Detail
                                    </a>
                                    @role('customer')
                                    @if(in_array($pesanan->status, ['menunggu', 'disetujui']))
                                        <form action="{{ route('pesanan.cancel', $pesanan->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                                onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                    @endrole
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    Tidak ada pesanan dengan status selesai atau dibatalkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
