# Aplikasi Peminjaman Lab - Gedung PPBS D UNPAD

Sistem informasi berbasis web yang dirancang untuk mempermudah proses peminjaman laboratorium di Gedung PPBS D, Universitas Padjadjaran (UNPAD). Aplikasi ini memungkinkan pengguna (mahasiswa/dosen) untuk melihat jadwal kosong, melakukan *booking* untuk satu atau beberapa lab sekaligus, serta memudahkan admin dalam mengelola dan menyetujui jadwal secara cerdas.

## 🌟 Fitur Utama

### 1. Manajemen Jadwal Cerdas & Kalender Interaktif
* **Tampilan Kalender (FullCalendar):** Melihat seluruh jadwal pemakaian lab dalam tampilan kalender interaktif bulanan/mingguan/harian.
* **Hover Info:** Menampilkan detail peminjam, jam, dan keterangan acara ketika jadwal di kalender disorot.
* **Filter Gedung/Lab:** Memudahkan pencarian jadwal spesifik per lab.

### 2. Multi-Booking Fleksibel
* Peminjam dapat memesan **hingga 3 lab sekaligus** dalam satu formulir peminjaman.
* Mendukung **peminjaman multi-hari** (misal: 3-5 Oktober).

### 3. Smart Conflict Resolution (Khusus Admin)
Fitur unggulan untuk mengatasi bentrok jadwal dengan mulus tanpa mengubah halaman:
* **Deteksi Otomatis:** Sistem mendeteksi otomatis jika admin mencoba menyetujui jadwal yang bertabrakan dengan jadwal yang sudah ada.
* **Pop-up Keputusan:** Menampilkan *SweetAlert* secara dinamis untuk mengambil keputusan:
  * **Timpa Lab yang Bentrok Saja:** Sistem akan membatalkan *hanya* lab yang bersinggungan di jadwal lama dan membuat sisa lab di jadwal lama tetap tersimpan (Split Scheduling).
  * **Kosongkan Seluruh Gedung:** Fitur VVIP yang akan membabat habis seluruh jadwal di semua lab pada jam/tanggal prioritas tersebut.

### 4. Sistem Role & Autentikasi
* **Admin:** Memiliki kontrol penuh untuk menyetujui/menolak jadwal, menambah lab, dan mengatur sistem.
* **User (Pemohon):** Dapat membuat permohonan baru, melihat status permohonan, dan mengunggah dokumen (Surat Peminjaman).

### 5. Laporan & Manajemen
* Ekspor laporan data peminjaman untuk arsip dan administrasi.
* Manajemen *database* lab (fasilitas, gambar, kapasitas, dll).

## 🛠 Teknologi yang Digunakan

Aplikasi ini dibangun di atas ekosistem Laravel modern:
* **Framework:** Laravel 11
* **Database:** PostgreSQL
* **Frontend/Styling:** Tailwind CSS, Blade Templating
* **JavaScript Libraries:** FullCalendar (Manajemen Kalender), SweetAlert2 (Pop-up interaktif)

## 🚀 Instalasi & Cara Menjalankan

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi secara lokal.

1. **Kloning Repositori**
   ```bash
   git clone https://github.com/aayramdan05/peminjaman-lab.git
   cd peminjaman-lab
   ```

2. **Install Dependensi PHP & Node.js**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin *file* `.env.example` menjadi `.env` dan atur koneksi database (PostgreSQL):
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan sesuaikan blok database:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=nama_database_anda
   DB_USERNAME=username_database
   DB_PASSWORD=password_database
   ```

4. **Generate App Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeding**
   Menjalankan skema database dan membuat akun admin bawaan:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Kompilasi Aset Frontend**
   ```bash
   npm run build
   ```
   *Atau jalankan `npm run dev` jika sedang mengembangkan.*

7. **Jalankan Server Lokal**
   ```bash
   php artisan serve
   ```
   Buka browser dan akses aplikasi di `http://localhost:8000`.

## 🤝 Berkontribusi

Bagi pihak UNPAD atau developer yang ingin mengembangkan fitur lebih lanjut, silakan *fork* repositori ini dan kirimkan *pull request*.

---
*Dibuat untuk kelancaran administrasi Laboratorium Gedung PPBS D, Universitas Padjadjaran.*
