<link rel="icon" type="image/png" href="{{ asset('images/favicon-removebg-preview.png') }}" />
<x-admin-layout>
    <h3 class="text-gray-700 text-3xl font-medium">Detail User</h3>
    <br>
    <div class="bg-white dark:bg-white p-6 rounded-lg shadow-md">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-800">Informasi User</h2>
            <div class="mt-2 space-y-2 text-gray-600 dark:text-gray-800">
                <div class="grid grid-cols-3 gap-2">
                    <span>Username</span>
                    <span class="col-span-2">: <span class="font-medium">{{ $user->username }}</span></span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <span>Nama Lengkap</span>
                    <span class="col-span-2">: <span class="font-medium">{{ $user->nama_lengkap }}</span></span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <span>Email</span>
                    <span class="col-span-2">: <span class="font-medium">{{ $user->email }}</span></span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <span>Nomor Telepon</span>
                    <span class="col-span-2">: <span class="font-medium">{{ $user->telepon }}</span></span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <span>Email diverifikasi</span>
                    <span class="col-span-2">: <span class="font-medium">{{ $user->email_verified_at }}</span></span>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-800">Roles</h2>
            @if($user->roles->isEmpty())
                <p class="mt-2 text-gray-600 dark:text-gray-800">No roles assigned.</p>
            @else
                <ul class="list-disc list-inside mt-2 text-gray-600 dark:text-gray-800">
                    @foreach($user->roles as $role)
                        <li>{{ $role->name }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('admin.users.index') }}" class="text-gray-600 dark:text-gray-800 hover:text-gray-900 dark:hover:text-gray-500">
                Kembali 
            </a>
        </div>
    </div>
</x-admin-layout>
