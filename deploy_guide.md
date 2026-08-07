# Panduan Panduan Deployment & Konfigurasi Server
## Paradise of Math (POM)

Dokumen ini memandu Anda dalam melakukan instalasi, konfigurasi, dan pemeliharaan fitur aplikasi (seperti Export PDF, Excel, Notifikasi, dan Realtime Chat) di lingkungan produksi (Server VPS, Cloud Hosting, atau Localhost Server).

---

### 1. Ekspor PDF & Excel (Cara Kerja & Kebutuhan Server)

Fitur **Ekspor PDF & Excel** di aplikasi Paradise of Math dirancang secara **Client-Side (Sisi Browser)**:
* **Ekspor Excel**: Menggunakan pustaka **SheetJS (XLSX.js)** yang dimuat secara dinamis via CDN. Konversi tabel HTML menjadi file `.xlsx` diproses langsung oleh mesin JavaScript di peramban pengguna.
* **Ekspor PDF**: Menggunakan metode **CSS @media print** bawaan browser (`window.print()`) yang dikombinasikan dengan stylesheet khusus cetak untuk menyembunyikan navigasi, sidebar, dan memformat lembar laporan secara rapi.

> [!TIP]
> **Kabar Baik**: Karena proses pemrosesan PDF & Excel didelegasikan sepenuhnya ke browser klien, **tidak ada pustaka server tambahan yang berat (seperti PhpSpreadsheet, dompdf, wkhtmltopdf, atau Node Headless Chrome) yang perlu diinstal di server**. Hal ini menghemat RAM server, CPU, dan menyederhanakan konfigurasi.

---

### 2. Persyaratan Sistem Server (Prerequisites)

Sebelum menjalankan aplikasi, pastikan server Anda memiliki spesifikasi minimum berikut:

* **PHP**: Versi `8.2` atau lebih baru.
* **Ekstensi PHP Wajib**:
  * `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `curl`.
* **Database**: MySQL `8.0+` atau MariaDB `10.4+`.
* **Node.js & NPM**: Versi LTS (untuk memproses kompilasi aset frontend via Vite).
* **Composer**: Versi `2.x`.

---

### 3. Langkah-Langkah Deployment di Server

Jalankan perintah-perintah berikut di direktori root project Anda pada server:

#### Langkah 1: Instal Dependensi PHP (Composer)
Instal seluruh paket dependensi Laravel dengan mengoptimalkan performa autoloading:
```bash
composer install --no-dev --optimize-autoloader
```

#### Langkah 2: Salin & Sesuaikan Konfigurasi Environment (`.env`)
Salin file konfigurasi contoh dan buka untuk diedit:
```bash
cp .env.example .env
nano .env
```
*Atur koneksi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`), URL aplikasi (`APP_URL`), dan variabel lainnya.*

#### Langkah 3: Generate Application Key
```bash
php artisan key:generate
```

#### Langkah 4: Jalankan Migrasi Database
Buat seluruh tabel database (termasuk tabel riwayat transaksi, biodata pengajar, dan tabel percakapan realtime):
```bash
php artisan migrate --force
```

#### Langkah 5: Instal Dependensi Frontend & Kompilasi Aset (Vite)
Kompilasi seluruh file JavaScript dan CSS Tailwind agar siap digunakan di mode produksi:
```bash
npm install
npm run build
```

#### Langkah 6: Atur Izin Folder (Permissions)
Folder penyimpanan dan cache harus dapat ditulis oleh web server (misal: Apache/Nginx dengan user `www-data`):
```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

---

### 4. Menjalankan Fitur Latar Belakang (Realtime & Background Jobs)

#### A. Antrean Notifikasi (Queue Worker)
Beberapa notifikasi dan fitur asinkronus menggunakan sistem antrean (*queue*). Untuk memproses antrean di server produksi, jalankan worker:
```bash
php artisan queue:work --daemon --tries=3
```
*Saran: Gunakan aplikasi monitoring proses seperti **Supervisor** di Linux agar perintah `php artisan queue:work` selalu berjalan otomatis di latar belakang dan melakukan restart jika terjadi crash.*

#### B. Fitur Chat Realtime
Sistem **Chat Realtime** menggunakan metode **Database AJAX Polling** secara berkala (2-3 detik sekali).
* **Kebutuhan**: Fitur ini bekerja secara *out-of-the-box* menggunakan server web standar Anda (Nginx/Apache + MySQL).
* **Keuntungan**: **Tidak perlu** menjalankan daemon Websocket terpisah (seperti Laravel Reverb, Pusher, atau Socket.io) yang memakan RAM tinggi.

#### C. Alur Kerja Sistem Chat
Sistem chat pada aplikasi ini bekerja dengan pola **visitor -> database -> admin -> database -> visitor**.

1. **Visitor membuka landing page**
    - Widget chat tampil di halaman publik melalui komponen Blade pada landing page.
    - Pengunjung dapat membuka jendela chat, membaca pesan sambutan, memilih opsi cepat, atau mengetik pesan manual.

2. **Pesan pengunjung dikirim ke server**
    - Saat pesan dikirim, frontend mengirim request ke endpoint backend.
    - Pesan disimpan ke database sehingga percakapan tidak hilang saat halaman dimuat ulang.
    - Setiap percakapan biasanya punya identitas sesi agar admin bisa membedakan satu pengunjung dengan pengunjung lain.

3. **Admin memantau percakapan**
    - Panel admin menampilkan daftar sesi chat masuk.
    - Daftar sesi dan isi pesan dimuat ulang secara berkala menggunakan polling AJAX.
    - Admin dapat memilih sesi tertentu untuk melihat seluruh riwayat percakapan.

4. **Admin membalas pesan**
    - Saat admin mengirim balasan, pesan juga disimpan ke database.
    - Balasan akan muncul pada panel admin dan kemudian terbaca oleh widget chat pengunjung saat polling berikutnya berjalan.

5. **Pesan sinkron tanpa websocket**
    - Karena sistem memakai polling database, sinkronisasi terjadi pada interval tertentu, bukan secara push real-time penuh.
    - Ini membuat setup lebih sederhana dan cocok untuk hosting biasa, selama database dan server web stabil.

#### D. Komponen Yang Terlibat
- **Frontend publik**: widget chat di landing page.
- **Frontend admin**: halaman daftar percakapan dan panel balasan.
- **Backend Laravel**: menerima pesan, menyimpan sesi, dan menyediakan data percakapan.
- **Database MySQL/MariaDB**: menyimpan sesi chat, pesan, dan status baca/belum dibaca.

#### E. Catatan Operasional
- Pastikan koneksi database stabil karena chat bergantung pada pembacaan data berkala.
- Jika pesan terasa lambat muncul, cek interval polling, beban database, dan apakah endpoint chat merespons dengan benar.
- Jika widget chat tidak muncul di browser, cek pemuatan aset icon, JavaScript, dan tidak ada error pada konsol browser.

---

### 5. Konfigurasi Web Server

Pastikan konfigurasi virtual host web server Anda (Nginx atau Apache) menunjuk direktori root ke folder **/public** dari project Laravel, bukan ke folder utama project.

**Contoh Blok Konfigurasi Nginx:**
```nginx
server {
    listen 80;
    server_name paradiseofmath.com;
    root /var/www/html/ParasideOfMath/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
