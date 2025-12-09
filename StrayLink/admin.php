<?php
session_start();
require_once 'includes/db.php';

// 1. CEK HAK AKSES (Wajib Admin)
// Pastikan di database user kamu row 'role'-nya diubah jadi 'admin' lewat phpMyAdmin dulu untuk user pertamamu.
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo "<script>alert('Hanya admin yang boleh masuk sini!'); window.location='index.php';</script>";
    exit;
}

require_once 'includes/header.php';
?>

<main>
    <div class="text-center" style="margin-bottom: 30px;">
        <h1 style="color: var(--primary-dark);">Panel Admin</h1>
        <p class="text-muted">Kelola konten website StrayLink dengan mudah.</p>
    </div>

    <div class="grid-3">
        <div class="card text-center">
            <h3>🐾 Data Hewan</h3>
            <p class="text-muted">Tambah hewan baru untuk diadopsi.</p>
            <a href="admin_hewan_tambah.php" class="btn btn-primary btn-block">+ Tambah Hewan</a>
            <a href="adopsi.php" class="btn btn-outline btn-block" style="margin-top: 10px;">Lihat Katalog</a>
        </div>

        <div class="card text-center">
            <h3>💰 Data Donasi</h3>
            <p class="text-muted">Buat kampanye galang dana baru.</p>
            <a href="admin_donasi_tambah.php" class="btn btn-primary btn-block">+ Buat Donasi</a>
            <a href="donasi.php" class="btn btn-outline btn-block" style="margin-top: 10px;">Lihat Donasi</a>
        </div>

        <div class="card text-center">
            <h3>📅 Data Event</h3>
            <p class="text-muted">Publikasikan kegiatan komunitas.</p>
            <a href="admin_event_tambah.php" class="btn btn-primary btn-block">+ Buat Event</a>
            <a href="event.php" class="btn btn-outline btn-block" style="margin-top: 10px;">Lihat Event</a>
        </div>
    </div>
    
    <div class="card" style="margin-top: 20px;">
        <h3>Tips untuk Admin</h3>
        <ul style="padding-left: 20px; line-height: 1.6; color: #555;">
            <li>Pastikan foto yang diupload berformat <strong>JPG/PNG</strong> dan ukurannya tidak terlalu besar (maks 2MB).</li>
            <li>Judul dan deskripsi yang menarik akan meningkatkan peluang adopsi/donasi.</li>
            <li>Untuk melihat hasil inputan, klik tombol "Lihat" di masing-masing kartu di atas.</li>
        </ul>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>