<x-admin-layout>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-6">Daftar Pesanan</h2>

        @if (session('message'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 border border-green-300">
                {{ session('message') }}
            </div>
        @endif

        @forelse ($pesanans as $index => $pesanan)
            <div class="bg-white shadow-md rounded-lg mb-6 border border-gray-200">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h5 class="text-lg font-semibold">
                        Pesanan #{{ $pesanan->id }} - {{ \Carbon\Carbon::parse($pesanan->created_at)->format('d M Y') }}
                        <span class="ml-2 px-3 py-1 text-sm rounded-full 
                            @if($pesanan->status === 'menunggu') bg-yellow-100 text-yellow-800
                            @elseif($pesanan->status === 'disetujui') bg-blue-100 text-blue-800
                            @elseif($pesanan->status === 'ditolak') bg-red-100 text-red-800
                            @elseif($pesanan->status === 'selesai') bg-green-100 text-green-800
                            @elseif($pesanan->status === 'dibatalkan') bg-gray-100 text-gray-600
                            @endif">
                            {{ ucfirst($pesanan->status) }}
                        </span>
                    </h5>
                    @if ($isAdmin)
                        <span class="text-sm text-gray-500">Customer: {{ $pesanan->user->name }}</span>
                    @endif
                </div>

                <div class="px-6 py-4">
                    <p><strong>Acara:</strong> {{ $pesanan->jadwal->nama_acara }}</p>
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pesanan->jadwal->tanggal)->format('d M Y') }}</p>
                    <p><strong>Total Harga:</strong> Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                </div>

                <div class="px-6 py-3 border-t flex justify-between items-center">
                    <div>
                        @if ($isAdmin)
                            <form action="{{ route('pesanan.updateStatus', $pesanan->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" 
                                    class="border rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach (['menunggu', 'disetujui', 'ditolak', 'selesai', 'dibatalkan'] as $status)
                                        <option value="{{ $status }}" {{ $pesanan->status === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            @if ($pesanan->status === 'menunggu')
                                <form action="{{ route('pesanan.konfirmasi', $pesanan->id) }}" method="POST" class="inline ml-2">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                        Konfirmasi
                                    </button>
                                </form>
                            @endif
                        @else
                            @if (in_array($pesanan->status, ['menunggu', 'disetujui']))
                                <form action="{{ route('pesanan.cancel', $pesanan->id) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                        Batalkan
                                    </button>
                                </form>
                            @else
                                <span class="text-sm text-gray-500">-</span>
                            @endif
                        @endif
                    </div>

                    <div class="text-sm text-gray-500">
                        Terakhir diubah: {{ \Carbon\Carbon::parse($pesanan->updated_at)->format('d M Y H:i') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="text-gray-500 text-center py-10">
                Belum ada pesanan.
            </div>
        @endforelse
    </div>
</x-admin-layout>
