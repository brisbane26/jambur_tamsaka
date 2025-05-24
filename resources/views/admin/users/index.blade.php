<x-admin-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold">Users List</h1>
    </x-slot>

    <div class="p-6">
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 font-medium">Username</th>
                        <th class="px-6 py-3 font-medium">Email</th>
                        <th class="px-6 py-3 font-medium">Role</th>
                        <th class="px-6 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-4">{{ $user->username }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @foreach($user->roles as $role)
                                    <span class="inline-block px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
<td class="px-6 py-4">
    <div class="flex space-x-4">
        <a href="{{ route('admin.users.show', $user) }}"
           class="text-blue-600 hover:text-blue-800 font-medium">Lihat</a>

        <!-- Hanya tampilkan tombol hapus jika user BUKAN admin -->
        @if(!$user->hasRole('admin'))
            <button type="button"
                    data-user-id="{{ $user->id }}"
                    data-user-name="{{ $user->username }}"
                    class="text-red-600 hover:text-red-800 font-medium"
                    onclick="openModal({{ $user->id }}, '{{ $user->username }}')">
                Hapus
            </button>
        @endif
    </div>

    <!-- Modal Delete - Hanya ditampilkan jika user BUKAN admin -->
    @if(!$user->hasRole('admin'))
    <div id="modal-{{ $user->id }}" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">Konfirmasi Penghapusan</h2>
            <p>Apakah Anda yakin ingin menghapus pengguna <strong>{{ $user->username }}</strong>?</p>

            <div class="mt-6 flex justify-end space-x-4">
                <button onclick="closeModal({{ $user->id }})"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 text-sm">
                    Batal
                </button>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function openModal(id, name) {
            document.getElementById(`modal-${id}`).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(`modal-${id}`).classList.add('hidden');
        }
    </script>
</x-admin-layout>
