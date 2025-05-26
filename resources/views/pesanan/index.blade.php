  <link rel="icon" type="image/png" href="{{ asset('images/favicon-removebg-preview.png') }}" />
<x-admin-layout>
    <div class="p-1">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-gray-700 text-3xl font-medium">Daftar Pesanan</h3>
            @role('customer')
            <a href="{{ route('paket.index') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                Buat Pesanan Baru
            </a>
            @endrole
        </div>


        {{-- @if (session('message'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 border border-green-300">
                {{ session('message') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4 border border-red-300">
                {{ session('error') }}
            </div>
        @endif --}}

        <livewire:show-pesanans />
    </div>
</x-admin-layout>
