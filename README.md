## Jambur Tamsaka

## Info

<p>Nama aplikasi: Jambur Tamsaka</p>
<p></p>Tim pengembang: Kelompok 3</p>

- Brisbane Jovan Rivaldi Sihombing - 231402001<br> 
- Carlos Donal Halomoan Sirait- 231402031<br>  
- Yehezkiel Situmorang - 231402061<br>  
- Petra Igor Keliat- 231402070<br>
- Pangeran Rae Ebenezer Siahaan - 231402079<br>

## Desc

Aplikasi Web Jambur Tamsaka adalah sistem informasi yang dirancang untuk mempermudah dan mendigitalisasi seluruh proses pemesanan serta manajemen operasional Jambur Tamsaka. Sistem ini memungkinkan calon penyewa untuk mengakses informasi ketersediaan gedung, melihat detail paket layanan, dan melakukan pemesanan secara online dengan mudah. Bagi pengelola, aplikasi ini menyediakan fitur untuk mengelola jadwal penggunaan jambur, mendata customer, mencatat transaksi, dan menghasilkan laporan secara otomatis, menggantikan sistem manual yang sebelumnya kurang efisien. Dengan fokus pada antarmuka yang intuitif dan pengalaman pengguna yang optimal, aplikasi ini bertujuan untuk meningkatkan efisiensi, transparansi, dan kualitas layanan Jambur Tamsaka.  

## Installation

Untuk menjalankan website Jambur Tamsaka di device anda, lakukan:

1. Clone this repository:

   ```bash
   git clone https://github.com/brisbane26/jambur_tamsaka.git
   ```
2. Change to the project directory
    ```bash
    cd jambur_tamsaka
    ```
3. Install the project dependencies
    ```bash
    composer install
    npm install
    ```
4. Copy the .env.example file to .env and configure your environment variables, including your database settings and any other necessary configuration.
    ```bash
    copy .env.example .env
    ```
    ```bash
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sadmin
   DB_USERNAME=root
   DB_PASSWORD=
    ```
   Configure your mail settings to mailtrap
    ```bash
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=
   MAIL_PASSWORD=
   MAIL_ENCRYPTION=null
   MAIL_FROM_ADDRESS="jamburtamsaka@gmail.com"
   MAIL_FROM_NAME="Jambur Tamsaka"
    ```
5. Generate application key
   ```bash
   php artisan key:generate
   ```
6. Migrate the database
    ```bash
    php artisan migrate
    php artisan migrate:fresh --seed
    ```
7. Create a symbolic link for the storage directory
   ```bash
   php artisan storage:link
   ```
8. Start development server
    ```bash
    npm run dev
    php artisan serve
    ```


## Technologies
- Laravel 12 
- Tailwind
- Livewire, Spatie
- Laragon/XAMPP
