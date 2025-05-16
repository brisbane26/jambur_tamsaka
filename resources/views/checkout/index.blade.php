<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        @if(session('message'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Sukses!</strong>
        <span class="block sm:inline">{{ session('message') }}</span>
    </div>
@endif

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Sukses!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif


        <h1 class="text-2xl font-bold mb-6">Isi Data Untuk Pemesanan</h1>
        
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Form Data Diri -->
            <div class="w-full md:w-1/2 bg-white p-6 rounded-lg shadow-md">
                <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="total_harga" value="{{ $totalHarga }}">
                    
                    <div class="mb-4">
                        <label for="nama_acara" class="block text-gray-700 mb-2">Nama Acara</label>
                        <input type="text" id="nama_acara" name="nama_acara" 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                    
                    <div class="mb-6">
                        <label for="tanggal_acara" class="block text-gray-700 mb-2">Tanggal Acara</label>
                        <input type="date" id="tanggal_acara" name="tanggal_acara" 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            min="{{ date('Y-m-d') }}" required>
                        @error('tanggal_acara')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="mb-6">
                        <label class="block text-gray-700 mb-2">Metode Pembayaran</label>
                        
                        <div class="space-y-3">
                            <!-- Cash -->
                            <div class="flex items-center">
                                <input type="radio" id="cash" name="metode_bayar" value="cash" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" checked>
                                <label for="cash" class="ml-2 block text-gray-700">Bayar di Tempat/Cash</label>
                            </div>
                            
                            <!-- Transfer Bank -->
                            <div class="flex items-center">
                                <input type="radio" id="transfer" name="metode_bayar" value="transfer" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="transfer" class="ml-2 block text-gray-700">Transfer Bank</label>
                            </div>
                            
                        <!-- Bank Information (hidden by default) -->
                        <div id="bankInfo" class="hidden ml-6 mt-2 space-y-4">
                            <div class="border rounded-lg p-4">
                                <h4 class="font-medium mb-2">Informasi Rekening</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    @foreach($banks as $bank)
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 pt-1 p-3 border rounded-lg bg-gray-50">
                                            @if($bank->logo)
                                            <img src="{{ asset('images/banks/' . $bank->logo) }}" alt="{{ $bank->nama_bank }} logo" class="h-3 w-8 object-contain">
                                            @else
                                            <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                <span class="text-xs">{{ substr($bank->nama_bank, 0, 2) }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">{{ $bank->nama_bank }}</p>
                                            <p class="text-lg font-semibold">{{ $bank->nomor_akun }}</p>
                                            <p class="text-sm text-gray-600">a.n. {{ $bank->nama_pemilik }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                                
                                <!-- Bukti Transfer -->
                                <div id="buktiTransfer" class="mt-4">
                                    <label for="bukti_transfer" class="block text-gray-700 mb-2">Upload Bukti Transfer</label>
                                    <input type="file" id="bukti_transfer" name="bukti_transfer" 
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Maks. 2MB)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" 
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                        Bayar
                    </button>
                </form>
            </div>
            
            <!-- Detail Keranjang -->
            <div class="w-full md:w-1/2 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Detail Keranjang</h2>
                
                <div class="space-y-4">
                    @foreach($keranjangs as $item)
                    <div class="border-b pb-4">
                        <h3 class="font-medium">{{ $item->paket->nama_paket }}</h3>
                        <p class="text-gray-600 text-sm">{{ $item->paket->deskripsi }}</p>
                        <div class="flex justify-between mt-2">
                            <span>Jumlah: {{ $item->kuantitas }}</span>
                            <span>Rp {{ number_format($item->paket->harga_jual * $item->kuantitas, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-6 pt-4 border-t">
                    <div class="flex justify-between font-semibold">
                        <span>Total Harga:</span>
                        <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const transferRadio = document.getElementById('transfer');
            const cashRadio = document.getElementById('cash');
            const bankInfo = document.getElementById('bankInfo');
            const buktiTransfer = document.getElementById('buktiTransfer');
            
            transferRadio.addEventListener('change', function() {
                if(this.checked) {
                    bankInfo.classList.remove('hidden');
                    buktiTransfer.classList.remove('hidden');
                }
            });
            
            cashRadio.addEventListener('change', function() {
                if(this.checked) {
                    bankInfo.classList.add('hidden');
                    buktiTransfer.classList.add('hidden');
                }
            });
        });
    </script>
</x-admin-layout>