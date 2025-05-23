<div>
        <div class="overflow-x-auto">
            

<div class="mb-4">
            <label for="status" class="text-sm font-medium text-gray-700">Filter Status:</label>
            <select wire:model.live="status"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring focus:ring-blue-200">
                <option value="">Semua</option>
                <option value="selesai">Selesai</option>
                <option value="menunggu">Menunggu</option>
                <option value="disetujui">Disetujui</option>
                <option value="ditolak">Ditolak</option>
                <option value="dibatalkan">Dibatalkan</option>
            </select>
        </div>

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
                                <button wire:click="confirmCancel({{ $pesanan->id }})"
                                    class="text-red-600 hover:text-red-900">
                                    Batalkan
                                </button>

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
        @if($showCancelModal)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">Konfirmasi Pembatalan</h2>
                <p class="mb-4">Apakah Anda yakin ingin membatalkan pesanan ini?</p>
                <div class="flex justify-end space-x-2">
                    <button wire:click="$set('showCancelModal', false)"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Batal</button>
                    <button wire:click="cancelPesanan"
                        class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded">Ya, Batalkan</button>
                </div>
            </div>
        </div>
        @endif
        
    </div>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $pesanans->links() }}
    </div>
        
</div>
