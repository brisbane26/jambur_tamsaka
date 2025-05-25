@section('title', 'Contact Us')
  <link rel="icon" type="image/png" href="{{ asset('images/favicon-removebg-preview.png') }}" />
<x-admin-layout>
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Success Message -->
            @if(session()->has('message'))
                <div class="mb-8 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{!! session('message') !!}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Header Section -->
            <div class="text-center mb-12">
                <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Hubungi Kami
                </h1>
                <p class="mt-4 text-xl text-gray-600">
                    Kami ada untuk membantu Anda dalam konsultasi, pertanyaan, dan informasi lebih lanjut tentang layanan kami.
                </p>
            </div>

            <!-- Contact Cards Grid -->
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 mb-12">
                <!-- WhatsApp Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg transition-transform duration-300 hover:scale-105">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 p-3 rounded-full">
                                <i class="fab fa-whatsapp text-green-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">WhatsApp Chat</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Untuk respon cepat dan praktis
                                </p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <a href="https://wa.me/+6281260679408?text=I%20would%20like%20to%20inquire%20about%20your%20products%20and%20services." 
                               target="_blank" rel="noopener noreferrer"
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors duration-300">
                               <i class="fab fa-whatsapp mr-2"></i> Chat Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Email Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg transition-transform duration-300 hover:scale-105">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-envelope text-blue-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Email Kami</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Untuk detail dan dokumentasi lebih lanjut
                                </p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <a href="mailto:tamsaka@company.com" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-300">
                               <i class="fas fa-envelope mr-2"></i> Email Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white shadow overflow-hidden rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Kontak Informasi Kami</h3>
                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Alamat</p>
                                <p class="text-sm text-gray-900">Jalan Jamin Ginting, Simpang Selayang, Medan, Indonesia</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-phone-alt text-gray-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Telepon</p>
                                <p class="text-sm text-gray-900">+62 812-6067-9408</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-gray-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Jam Kerja</p>
                                <p class="text-sm text-gray-900">Mon-Fri: 9AM - 6PM<br>Sat: 10AM - 3PM</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Font Awesome -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" defer></script>
    @endpush
</x-admin-layout>