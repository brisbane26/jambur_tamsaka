<!DOCTYPE html>
<html lang="{{ $page->language ?? 'en' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="referrer" content="always">
    <meta name="turbo-prefetch" content="false">


    <title>{{ config('app.name', 'Jambur Tamsaka') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon-removebg-preview.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    <!-- Turbo.js -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@latest/dist/turbo.es2017-esm.min.js"></script>

    <!-- Loader CSS -->
    <style>
        #page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
        }

        /* Menyesuaikan warna path SVG di loader Anda */
        #page-loader svg path {
            fill: #5C1515 !important;
            /* Menggunakan warna merah gelap sidebar */
        }

        /* Menyesuaikan warna teks "Memuat..." */
        #page-loader p {
            color: #5C1515;
            /* Menggunakan warna merah gelap sidebar */
        }
    </style>

</head>

<body>
    <!-- Loader Element -->
    <div id="page-loader">
        <div class="spinner">
        </div>
        <div class="grid gap-3">
            <div class="flex items-center justify-center gap-2.5">
                <svg class="animate-spin border-indigo-300" xmlns="http://www.w3.org/2000/svg" width="48"
                    height="48" viewBox="0 0 48 48" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M23.0885 0.0518284C22.4003 0.275262 22.0035 0.672476 21.8113 1.32105C21.7028 1.68413 21.6842 2.52821 21.6842 6.93482C21.6842 12.772 21.678 12.6975 22.2732 13.2934C23.0699 14.0878 24.9299 14.0878 25.7266 13.2934C26.3218 12.6975 26.3156 12.772 26.3156 6.93482C26.3156 1.28692 26.3063 1.18761 25.8475 0.641444C25.4631 0.185268 25.0508 0.033209 24.1239 0.00838303C23.7782 -0.0111218 23.4314 0.0034299 23.0885 0.0518284ZM39.0441 6.23348C38.6628 6.41037 38.0118 7.02171 34.9056 10.1405C32.0505 13.0048 31.1794 13.9295 31.0399 14.2336C30.924 14.4858 30.8643 14.7602 30.8648 15.0377C30.8653 15.3153 30.9262 15.5894 31.043 15.8411C31.26 16.3066 31.973 17.039 32.4535 17.2903C32.9402 17.5448 33.6625 17.5417 34.1678 17.2841C34.6855 17.0172 41.8464 9.84876 42.113 9.33052C42.24 9.0637 42.3067 8.77215 42.3083 8.47661C42.3099 8.18108 42.2464 7.88881 42.1223 7.62063C41.7792 7.04633 41.2947 6.56978 40.7149 6.23659C40.4584 6.09786 40.1715 6.02496 39.8799 6.02442C39.5883 6.02387 39.3011 6.09571 39.0441 6.23348ZM35.6093 21.7373C35.1901 21.8529 34.8113 22.0828 34.515 22.4014C34.0903 22.9755 33.957 24.1423 34.2236 24.9646C34.4034 25.5232 34.856 25.9763 35.414 26.1563C35.7922 26.2804 36.4091 26.2959 41.0249 26.2959C45.4548 26.2959 46.2701 26.2773 46.6359 26.1687C47.5504 25.8956 47.9968 25.1757 47.9999 23.9747C47.9999 22.78 47.5442 22.0631 46.5987 21.7776C46.2794 21.6814 45.2781 21.6628 41.0373 21.669C38.1916 21.6752 35.7488 21.7062 35.6093 21.7373ZM32.5341 31.0098C32.0443 31.2332 31.322 31.9407 31.0709 32.4435C30.9428 32.6948 30.873 32.9717 30.8665 33.2538C30.8601 33.5359 30.9172 33.8158 31.0337 34.0727C31.2631 34.5847 38.4675 41.8214 39.0348 42.1101C39.8377 42.5228 40.6622 42.3211 41.493 41.508C42.3114 40.7074 42.5284 39.8385 42.113 39.0285C41.8247 38.4606 34.5956 31.2487 34.0841 31.0191C33.8397 30.9131 33.5764 30.8577 33.3101 30.8561C33.0437 30.8545 32.7798 30.9067 32.5341 31.0098ZM23.2094 34.1471C22.7906 34.2653 22.4121 34.496 22.1151 34.8143C21.6873 35.3915 21.6842 35.4319 21.6842 41.0115C21.6842 45.4553 21.7028 46.2715 21.8113 46.6377C22.0841 47.5531 22.8064 48 23.9999 48C25.1934 48 25.9157 47.5531 26.1885 46.6377C26.3807 45.9798 26.3776 36.0029 26.1823 35.4288C26.1222 35.1973 26.0123 34.9818 25.8602 34.7974C25.7081 34.613 25.5176 34.4641 25.3019 34.3613C24.9609 34.1751 24.7625 34.1285 24.1766 34.1068C23.8537 34.0873 23.5296 34.1009 23.2094 34.1471Z"
                        fill="#4F46E5" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.73444 6.18374C7.24464 6.40718 6.52235 7.11472 6.27125 7.61744C6.14316 7.86875 6.0733 8.14573 6.06686 8.42779C6.06042 8.70985 6.11757 8.98974 6.23405 9.24664C6.46345 9.75868 13.6678 16.9954 14.2351 17.284C15.038 17.6968 15.8626 17.4951 16.6934 16.682C17.5118 15.8814 17.7288 15.0125 17.3134 14.2025C17.0251 13.6346 9.79592 6.42269 9.28442 6.19305C9.04 6.0871 8.77672 6.03166 8.51038 6.03006C8.24403 6.02847 7.98011 6.08074 7.73444 6.18374ZM1.36709 21.7806C0.452596 22.0692 0 22.7985 0 23.9746C0.00309997 25.1756 0.449496 25.8955 1.36399 26.1686C1.72979 26.2772 2.54508 26.2959 6.97494 26.2959C11.5908 26.2959 12.2077 26.2803 12.5859 26.1562C12.8637 26.0648 13.1162 25.9094 13.3229 25.7024C13.5297 25.4954 13.685 25.2427 13.7763 24.9646C14.0429 24.1422 13.9096 22.9754 13.4849 22.3982C13.3147 22.2129 13.1145 22.0577 12.8928 21.9389L12.4681 21.7031L7.09584 21.6844C2.77138 21.672 1.65229 21.6906 1.36709 21.7806ZM14.2444 31.0593C13.8631 31.2362 13.2121 31.8475 10.1059 34.9663C7.25084 37.8306 6.37975 38.7554 6.24025 39.0595C6.12434 39.3116 6.06459 39.586 6.06512 39.8636C6.06566 40.1411 6.12647 40.4153 6.24335 40.667C6.46035 41.1324 7.17334 41.8648 7.65384 42.1162C8.14053 42.3706 8.86283 42.3675 9.36812 42.11C9.88582 41.8431 17.0468 34.6746 17.3134 34.1564C17.4403 33.8895 17.507 33.598 17.5086 33.3025C17.5102 33.0069 17.4467 32.7147 17.3227 32.4465C16.9796 31.8722 16.495 31.3956 15.9153 31.0624C15.6587 30.9237 15.3718 30.8508 15.0802 30.8503C14.7886 30.8497 14.5014 30.9215 14.2444 31.0593Z"
                        fill="#D1D5DB" />
                </svg>
                <p class="text-black text-lg font-medium leading-snug">
                    Memuat<span class="animate-pulse">...</span></p>

            </div>
        </div>
    </div>

    <!-- Toastr Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-200 font-roboto">
        @include('layouts.admin.sidebar') <!-- Pastikan semua <a> di sidebar punya data-turbo-prefetch="false" -->

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('layouts.admin.header')

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="container mx-auto px-6 py-8">
                    {{ $slot }}
                    @livewireScripts
                </div>
            </main>
        </div>
    </div>

    <script type="module">
        document.addEventListener('turbo:click', () => {
            document.getElementById('page-loader').style.display = 'flex';
        });

        document.addEventListener('turbo:before-render', () => {
            document.getElementById('page-loader').style.display = 'flex';
        });

        document.addEventListener('turbo:render', () => {
            document.getElementById('page-loader').style.display = 'none';
        });

        // Fallback jika Turbo gagal
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[data-turbo="true"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (!this.hasAttribute('target')) {
                        e.preventDefault();
                        document.getElementById('page-loader').style.display = 'flex';
                        window.location.href = this.href;
                    }
                });
            });
        });
    </script>


    <!-- Toastr Notification Script -->
    <script>
        function toggleDeleteModal(id) {
            const modalDelete = document.getElementById('deleteModal' + id);
            modalDelete.classList.toggle('hidden');
        }

        function toggleEditModal(id) {
            const modalEdit = document.getElementById('editModal' + id);
            modalEdit.classList.toggle('hidden');
        }

        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}";
            toastr.options.timeOut = 5000;
            toastr[type]("{{ Session::get('message') }}");
            var audio = new Audio('audio.mp3');
            audio.play();
        @endif
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('notification'))
                toastr.options = {
                    "closeButton": true,
                    "progressBar": false
                };
                toastr["{{ session('notification')['alert-type'] }}"]("{{ session('notification')['message'] }}");
            @endif
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-toast', (event) => {
                toastr[event.type](event.message);
            });

            Livewire.on('showToast', (event) => {
                toastr.options = {
                    "closeButton": true,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "timeOut": 5000
                };
                toastr[event.type](event.message);
            });
        });
    </script>
</body>

</html>
