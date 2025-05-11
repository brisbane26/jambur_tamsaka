<x-admin-layout>
    <h3 class="text-gray-700 text-3xl font-medium">Tables</h3>

    <div class="mt-8">
        <div class="flex justify-between items-center">
            <h4 class="text-gray-600">Daftar Paket</h4>

            <button class="px-6 py-3 bg-blue-600 rounded-md text-white font-medium tracking-wide hover:bg-blue-500">
                <a href="{{ route('paket.create') }}">Tambah Paket</a>
            </button>
        </div>
      
        <div class="flex flex-col mt-6">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Nama Paket</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white">
                            @foreach ($paket as $pkt)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ $loop->iteration }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-32 w-32 bg-gray-100 rounded-md flex items-center justify-center overflow-hidden">
                                            @if ($pkt->gambar_url)
                                                <img src="{{ $pkt->gambar_url }}" alt="Gambar Paket" class="object-cover w-full h-full">
                                            @else
                                                <span class="text-xs text-gray-500">No Image</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="text-sm leading-5 text-gray-500">{{ $pkt->nama_paket }}</div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-500">{{ $pkt->kategori->nama_kategori }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-gray-500">{{ $pkt->deskripsi }}</span>
                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">{{ $pkt->harga_jual }}</td>

                                <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                                    <button onclick="toggleDeleteModal({{ $pkt->id }})" class="text-2xl text-red-500 hover:text-red-700">
                                        <i class="bi bi-trash-fill"></i></button>

                                <div id="deleteModal{{ $pkt->id }}" class="fixed inset-0 hidden overflow-y-auto bg-black/50">
                                    <div class="flex items-center justify-center min-h-screen px-4">
                                        <div class="relative w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
                                            <h2 class="text-lg mb-4 font-semibold">Apakah kamu yakin untuk menghapus?</h2>
                                            <div class="flex justify-end space-x-2">
                                                <button type="button" onclick="toggleDeleteModal({{ $pkt->id }})" 
                                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                                                <form action="{{ route('paket.destroy', $pkt->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                    class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-700">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
