<x-admin-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Daftar Pesanan</h2>
            @role('customer')
            <a href="{{ route('paket.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Buat Pesanan Baru
            </a>
            @endrole
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

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @role('admin')
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer
                            </th>
                            @endrole
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID Pesanan
                            </th>
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
                                @role('admin')
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $pesanan->user->nama_lengkap }}
                                </td>
                                @endrole
                                <td class="px-6 py-4 whitespace-nowrap">
                                    #{{ $pesanan->id }}
                                </td>
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
                                <td colspan="@role('admin') 8 @else 7 @endrole" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada pesanan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>