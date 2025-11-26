# 🐾 StrayLink

StrayLink adalah platform adopsi hewan berbasis web yang dirancang untuk memudahkan masyarakat menemukan, mempercayai, dan mengadopsi hewan peliharaan dengan aman.  
Proyek ini dibuat sebagai tugas UAS sekaligus latihan membangun aplikasi dengan kombinasi **HTML, CSS, PHP, dan MySQL**.

---

## 🚀 Fitur Utama
- **Halaman Utama**: Tampilan informasi dan navigasi ke fitur lain.
- **Autentikasi Pengguna**: Login & Register dengan validasi database.
- **Database Adopsi**: Menggunakan `straylink.sql` untuk menyimpan data hewan dan pengguna.
- **Desain Responsif**: CSS terpisah di folder `style/` agar tampilan rapi di berbagai perangkat.
- **Struktur Modular**: File dipisahkan (HTML, PHP, CSS, SQL) untuk memudahkan pengembangan.

---

## 📂 Struktur Folder
WEB/            
├── index.html  # Halaman utama  
├── login.php # Form login 
├── register.php # Form registrasi 
├── style/ # File CSS 
├── images/ # Logo & aset gambar 
└── straylink.sql # Dump database MySQL


---

## ⚙️ Cara Menjalankan
1. **Siapkan server lokal**  
   Install XAMPP/Laragon (PHP + MySQL).
2. **Import database**  
   - Buat database baru bernama `straylink`.  
   - Import file `straylink.sql` ke MySQL.
3. **Letakkan project**  
   - Copy folder `WEB` ke `htdocs` (XAMPP) atau `www` (Laragon).
4. **Akses di browser**  
   - Buka `http://localhost/WEB/index.html`  
   - Coba login/register untuk memastikan koneksi database berjalan.

---

## 🛠️ Teknologi yang Digunakan
- **Frontend**: HTML5, CSS3
- **Backend**: PHP 8+
- **Database**: MySQL/MariaDB
- **Tools**: XAMPP / Laragon

---

## 🌟 Rencana Pengembangan
- Tambah fitur **dashboard adopsi** untuk menampilkan daftar hewan.
- Implementasi **hashing password** agar lebih aman.
- Integrasi **notifikasi email** untuk konfirmasi adopsi.
- Desain UI lebih modern dengan framework CSS (Bootstrap/Tailwind).

---

## 📜 Lisensi
Proyek ini dibuat untuk tujuan edukasi.  
Jika ingin mengembangkan lebih lanjut, silakan gunakan dengan bebas (MIT License).

---

## 👨‍💻 Kontributor
- **Maulana** – Developer & Designer  
- Universitas Negeri "Veteran" Jakarta
