<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Paket;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Jadwal;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; // Digunakan untuk memeriksa ketersediaan gedung

class PesananComboSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Customer Users
        $customerUsers = [
            [
                'email' => 'customer@example.com',
                'username' => 'customer',
                'nama_lengkap' => 'Customer Jambur',
                'telepon' => '081234567891',
                'password' => Hash::make('customer123'),
                'email_verified_at' => now(),
            ],
            [
                'email' => 'petong@example.com',
                'username' => 'petong',
                'nama_lengkap' => 'Petra Igor',
                'telepon' => '081234567892',
                'password' => Hash::make('petong123'),
                'email_verified_at' => now(),
            ],
            [
                'email' => 'bane@example.com',
                'username' => 'bane',
                'nama_lengkap' => 'Bane Jovan',
                'telepon' => '081234567893',
                'password' => Hash::make('bane123'),
                'email_verified_at' => now(),
            ],
        ];

        // Buat atau dapatkan user customer
        foreach ($customerUsers as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $customer1 = User::where('email', 'customer@example.com')->first();
        $customer2 = User::where('email', 'petong@example.com')->first();
        $customer3 = User::where('email', 'bane@example.com')->first();

        // Ambil data paket yang sudah ada dari database
        $paketGedungUtama = Paket::where('nama_paket', 'Gedung Utama')->first();
        $paketGedungResepsi = Paket::where('nama_paket', 'Gedung Resepsi')->first();
        $paketCatering1 = Paket::where('nama_paket', 'Catering 1')->first();
        $paketCatering2 = Paket::where('nama_paket', 'Catering 2')->first();
        $paketMusikBiasa = Paket::where('nama_paket', 'Musik Biasa')->first();
        $paketMUA1 = Paket::where('nama_paket', 'MUA 1')->first();
        $paketDekorasiBungaPlastik = Paket::where('nama_paket', 'Dekorasi Bunga Plastik')->first();

        // Pastikan paket-paket gedung ditemukan
        if (!$paketGedungUtama || !$paketGedungResepsi) {
            $this->command->error("Pastikan 'Gedung Utama' dan 'Gedung Resepsi' ada di tabel 'pakets' Anda.");
            return;
        }

        // Buat atau dapatkan jadwal placeholder untuk pesanan 'menunggu'
        // ID 1 akan digunakan sebagai jadwal dummy/placeholder
        // Jika Anda menjalankan seeder ini berulang kali dengan `migrate:fresh`, ID 1 mungkin sudah ada.
        // Pastikan Anda membersihkan tabel 'jadwals' jika perlu.
        $placeholderJadwal = Jadwal::firstOrCreate(
            ['id' => 1], // Coba temukan atau buat dengan ID 1
            [
                'tanggal' => Carbon::parse('2025-05-31'), // Tanggal dummy yang sangat lampau
                'nama_acara' => 'Adat (Pesanan Menunggu)',
                'user_id' => $customer1->id ?? User::first()->id, // Assign ke customer pertama atau user mana saja
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $today = Carbon::create(2025, 5, 27); // Tanggal 27 Mei 2025

        // Array untuk melacak ketersediaan gedung per tanggal
        // Key: tanggal (YYYY-MM-DD), Value: array ['gedung_utama' => bool, 'gedung_resepsi' => bool]
        $gedungAvailability = [];

        // --- Pesanan untuk customer utama (customer1) ---
        if ($customer1) {
            // Pesanan 1: 24 Mei 2025 (disetujui) - Gedung Utama
            $this->createOrderWithGedungCheck(
                $customer1,
                Carbon::create(2025, 5, 24)->toDateString(),
                'Acara Pernikahan ' . $customer1->nama_lengkap . ' (Disetujui)',
                'disetujui',
                [$paketGedungUtama, $paketCatering1],
                $gedungAvailability,
                $placeholderJadwal->id
            );

            // Pesanan 2: 28 Mei 2025 (disetujui) - Gedung Resepsi
            $this->createOrderWithGedungCheck(
                $customer1,
                Carbon::create(2025, 5, 28)->toDateString(),
                'Acara Resepsi ' . $customer1->nama_lengkap . ' (Disetujui)',
                'disetujui',
                [$paketGedungResepsi, $paketMUA1],
                $gedungAvailability,
                $placeholderJadwal->id
            );

            // Pesanan 3: 31 Mei 2025 (menunggu) - (Tidak perlu cek gedung karena tidak masuk jadwal)
            $this->createCustomerOrder(
                $customer1,
                Carbon::create(2025, 5, 31)->toDateString(),
                'Acara Lamaran ' . $customer1->nama_lengkap . ' (Menunggu)',
                'menunggu',
                $paketGedungUtama, // Tetap sertakan paket gedung di detail pesanan
                $paketCatering2,
                $placeholderJadwal->id
            );

            // Pesanan 4: 1 Juni 2025 (menunggu) - (Tidak perlu cek gedung karena tidak masuk jadwal)
            $this->createCustomerOrder(
                $customer1,
                Carbon::create(2025, 6, 1)->toDateString(),
                'Acara Santai ' . $customer1->nama_lengkap . ' (Menunggu)',
                'menunggu',
                $paketGedungResepsi, // Tetap sertakan paket gedung di detail pesanan
                $paketMusikBiasa,
                $placeholderJadwal->id
            );
        }

        // --- Pesanan untuk customer2 ---
        if ($customer2) {
            // Pesanan 1: 24 Mei 2025 (disetujui) - Gedung Resepsi (Bisa karena customer1 pesan Gedung Utama)
            $this->createOrderWithGedungCheck(
                $customer2,
                Carbon::create(2025, 5, 24)->toDateString(),
                'Pemberkatan ' . $customer2->nama_lengkap . ' (Disetujui)',
                'disetujui',
                [$paketGedungResepsi, $paketCatering2],
                $gedungAvailability,
                $placeholderJadwal->id
            );

            // Pesanan 2: 29 Mei 2025 (disetujui) - Gedung Utama
            $this->createOrderWithGedungCheck(
                $customer2,
                Carbon::create(2025, 5, 29)->toDateString(),
                'Gathering ' . $customer2->nama_lengkap . ' (Disetujui)',
                'disetujui',
                [$paketGedungUtama, $paketMUA1],
                $gedungAvailability,
                $placeholderJadwal->id
            );

            // Pesanan 3: 5 Juni 2025 (menunggu)
            $this->createCustomerOrder(
                $customer2,
                Carbon::create(2025, 6, 5)->toDateString(),
                'Wisuda ' . $customer2->nama_lengkap . ' (Menunggu)',
                'menunggu',
                $paketCatering1,
                $paketMusikBiasa,
                $placeholderJadwal->id
            );
        }

        // --- Pesanan untuk customer3 ---
        if ($customer3) {
            // Pesanan 1: 27 Mei 2025 (disetujui) - Gedung Utama & Resepsi (user pesan 2 gedung sekaligus pada 1 tanggal)
            $this->createOrderWithGedungCheck(
                $customer3,
                $today->toDateString(), // Tanggal 27 Mei 2025
                'Syukuran Bayi ' . $customer3->nama_lengkap . ' (Disetujui)',
                'disetujui',
                [$paketGedungUtama, $paketGedungResepsi, $paketDekorasiBungaPlastik], // 2 gedung
                $gedungAvailability,
                $placeholderJadwal->id
            );

            // Pesanan 2: 30 Mei 2025 (disetujui) - Gedung Resepsi
            $this->createOrderWithGedungCheck(
                $customer3,
                Carbon::create(2025, 5, 30)->toDateString(),
                'Ultah ' . $customer3->nama_lengkap . ' (Disetujui)',
                'disetujui',
                [$paketGedungResepsi, $paketCatering1],
                $gedungAvailability,
                $placeholderJadwal->id
            );

            // Pesanan 3: 6 Juni 2025 (menunggu)
            $this->createCustomerOrder(
                $customer3,
                Carbon::create(2025, 6, 6)->toDateString(),
                'Seminar ' . $customer3->nama_lengkap . ' (Menunggu)',
                'menunggu',
                $paketMUA1,
                $paketMusikBiasa,
                $placeholderJadwal->id
            );
        }

        // Contoh skenario tabrakan (tidak akan dibuat jika logika pengecekan berfungsi)
        // Jika Anda ingin menguji skenario tabrakan, Anda bisa mencoba ini dan melihat output pesan error.
        /*
        $this->command->info("\nMencoba membuat pesanan yang seharusnya tabrakan:");
        $this->createOrderWithGedungCheck(
            $customer1,
            Carbon::create(2025, 5, 27)->toDateString(), // Tanggal 27 Mei, sudah dipesan oleh customer3 untuk 2 gedung
            'Tabrakan Gedung Utama (Seharusnya Gagal)',
            'disetujui',
            [$paketGedungUtama, $paketCatering1],
            $gedungAvailability,
            $placeholderJadwal->id
        );
        */
    }

    /**
     * Helper function to create an order with an availability check for 'Gedung Utama' and 'Gedung Resepsi'.
     * Only creates a Jadwal entry if the order status is 'disetujui'.
     *
     * @param User $customer
     * @param string $date
     * @param string $eventName
     * @param string $status 'disetujui' or 'menunggu'
     * @param array $packages Array of Paket models
     * @param array &$gedungAvailability Pass by reference to track availability
     * @param int $placeholderJadwalId
     * @return bool True if order was created, false otherwise (e.g., due to clash)
     */
    protected function createOrderWithGedungCheck($customer, $date, $eventName, $status, array $packages, array &$gedungAvailability, $placeholderJadwalId): bool
    {
        $gedungUtamaBooked = false;
        $gedungResepsiBooked = false;

        foreach ($packages as $package) {
            if ($package && $package->nama_paket === 'Gedung Utama') {
                $gedungUtamaBooked = true;
            }
            if ($package && $package->nama_paket === 'Gedung Resepsi') {
                $gedungResepsiBooked = true;
            }
        }

        if ($status === 'disetujui') {
            if (!isset($gedungAvailability[$date])) {
                $gedungAvailability[$date] = [
                    'Gedung Utama' => false,
                    'Gedung Resepsi' => false,
                ];
            }

            // Cek ketersediaan
            if ($gedungUtamaBooked && $gedungAvailability[$date]['Gedung Utama']) {
                $this->command->warn("Gagal membuat pesanan untuk " . $customer->nama_lengkap . " pada " . $date . ": Gedung Utama sudah dipesan. (Logika Seeder)");
                return false;
            }
            if ($gedungResepsiBooked && $gedungAvailability[$date]['Gedung Resepsi']) {
                $this->command->warn("Gagal membuat pesanan untuk " . $customer->nama_lengkap . " pada " . $date . ": Gedung Resepsi sudah dipesan. (Logika Seeder)");
                return false;
            }

            // Jika memesan kedua gedung, pastikan gedung lain tidak sedang dipesan oleh orang lain.
            if ($gedungUtamaBooked && $gedungResepsiBooked) {
                // Untuk kesederhanaan seeder, ini akan dianggap mengunci keduanya untuk tanggal itu.
                // Logika real-world akan lebih kompleks dengan user_id.
                if ($gedungAvailability[$date]['Gedung Utama'] || $gedungAvailability[$date]['Gedung Resepsi']) {
                    $this->command->warn("Gagal membuat pesanan untuk " . $customer->nama_lengkap . " pada " . $date . ": Kedua gedung sudah dipesan sebagian/seluruhnya. (Logika Seeder)");
                    return false;
                }
            }


            // Tandai gedung yang dipesan sebagai tidak tersedia
            if ($gedungUtamaBooked) {
                $gedungAvailability[$date]['Gedung Utama'] = true;
            }
            if ($gedungResepsiBooked) {
                $gedungAvailability[$date]['Gedung Resepsi'] = true;
            }

            // Jika kedua gedung dipesan oleh satu user, user lain tidak bisa memesan di tanggal yang sama.
            // Implementasi sederhana: jika salah satu gedung sudah dipesan, maka gedung lainnya juga dianggap tidak tersedia
            // untuk user lain pada tanggal yang sama di seeder ini.
            // Untuk skenario lebih kompleks, Anda butuh tabel terpisah untuk ketersediaan gedung per tanggal.
            if ($gedungUtamaBooked && $gedungResepsiBooked) {
                 // Tandai kedua gedung tidak tersedia sepenuhnya untuk tanggal ini
                $gedungAvailability[$date]['Gedung Utama'] = true;
                $gedungAvailability[$date]['Gedung Resepsi'] = true;
            }
        }

        // Lanjutkan membuat pesanan jika lolos cek ketersediaan atau jika statusnya 'menunggu'
        $jadwalIdToUse = $placeholderJadwalId;

        if ($status === 'disetujui') {
            $jadwal = Jadwal::create([
                'tanggal' => $date,
                'nama_acara' => $eventName,
                'user_id' => $customer->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $jadwalIdToUse = $jadwal->id;
        }

        $pesanan = Pesanan::create([
            'user_id' => $customer->id,
            'jadwal_id' => $jadwalIdToUse,
            'status' => $status,
            'bukti_transaksi' => ($status === 'disetujui') ? 'bukti_' . $customer->username . '_' . Str::random(5) . '.jpg' : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($packages as $package) {
            if ($package) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'paket_id' => $package->id,
                    'kuantitas' => 1, // Kuantitas gedung selalu 1
                    'harga' => $package->harga_jual,
                    'catatan' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'metode_bayar' => $this->randomPaymentMethod(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Invoice::create([
            'pesanan_id' => $pesanan->id,
            'nomor_invoice' => 'INV/' . date('Ymd') . '/' . Str::upper(Str::random(6)),
            'status_pembayaran' => ($status === 'disetujui') ? 'lunas' : 'menunggu',
            'tanggal_terbit' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return true;
    }

    /**
     * Helper function to create a single order without gedung availability check.
     * Used for 'menunggu' status.
     *
     * @param User $customer
     * @param string $date
     * @param string $eventName
     * @param string $status
     * @param Paket $mainPackage
     * @param Paket|null $additionalPackage
     * @param int $placeholderJadwalId
     */
    protected function createCustomerOrder($customer, $date, $eventName, $status, $mainPackage, $additionalPackage = null, $placeholderJadwalId)
    {
        // Pastikan paket tidak null sebelum digunakan
        if (!$mainPackage) {
            $this->command->error("Paket utama tidak ditemukan untuk event: " . $eventName . "\n");
            return;
        }

        $jadwalIdToUse = $placeholderJadwalId; // Default ke jadwal placeholder

        // Untuk status 'menunggu', kita tidak membuat entri jadwal baru
        // dan hanya menggunakan jadwal placeholder.
        // Oleh karena itu, bagian if ($status === 'disetujui') yang membuat jadwal baru dihapus dari sini.

        $pesanan = Pesanan::create([
            'user_id' => $customer->id,
            'jadwal_id' => $jadwalIdToUse, // Selalu terisi dengan ID placeholder
            'status' => $status,
            'bukti_transaksi' => null, // Bukti transaksi biasanya tidak ada untuk pesanan menunggu
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'paket_id' => $mainPackage->id,
            'kuantitas' => 1,
            'harga' => $mainPackage->harga_jual,
            'catatan' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($additionalPackage) {
            DetailPesanan::create([
                'pesanan_id' => $pesanan->id,
                'paket_id' => $additionalPackage->id,
                'kuantitas' => rand(1, 10),
                'harga' => $additionalPackage->harga_jual,
                'catatan' => 'Additional service for event',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'metode_bayar' => $this->randomPaymentMethod(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Invoice::create([
            'pesanan_id' => $pesanan->id,
            'nomor_invoice' => 'INV/' . date('Ymd') . '/' . Str::upper(Str::random(6)),
            'status_pembayaran' => 'menunggu', // Selalu menunggu untuk pesanan menunggu
            'tanggal_terbit' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Helper function to get a random payment method.
     */
    protected function randomPaymentMethod()
    {
        $methods = ['Mandiri', 'BRI', 'BCA', 'BNI'];
        return $methods[array_rand($methods)];
    }
}