<?php
session_start();
require_once 'includes/header.php';
?>

<div class="hero" style="
    background: url('assets/background.png') center/cover no-repeat fixed;
    height: 500px;
    display: flex;
    align-items: center;
    position: relative;
    color: white; /* Fallback text color */
">
    <div style="
        position: absolute; inset: 0; 
        background: linear-gradient(to right, rgba(0,0,0,0.6), rgba(0,0,0,0.1));
    "></div>

    <div style="position: relative; z-index: 1; max-width: 1100px; margin: 0 auto; padding: 0 30px; width: 100%;">
        <h1 style="font-size: 3.5rem; margin-bottom: 10px; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
            Find your rescue,<br>find your Love.
        </h1>
        <p style="font-size: 1.2rem; max-width: 600px; line-height: 1.6; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
            Mari bantu mereka mendapatkan hidup layak dan bangun ikatan berharga. 
            Bersama, kita beri harapan baru bagi hewan terlantar.
        </p>
        <a href="adopsi.php" class="btn btn-primary" style="margin-top: 20px; padding: 12px 24px; font-size: 1.1rem;">
            Mulai Adopsi Sekarang
        </a>
    </div>
</div>

<main>
    <div class="text-center" style="margin: 40px 0;">
        <h2 style="color: var(--primary-dark);">Layanan Kami</h2>
        <p class="text-muted">Satu platform untuk segala kebutuhan peduli hewan.</p>
    </div>

    <div class="grid-3">
        <div class="card text-center" style="transition: transform 0.2s;">
            <img src="assets/adoptlogo.png" alt="Adopsi" style="height: 80px; margin: 0 auto 15px;">
            <h3>Adopsi</h3>
            <p class="text-muted">Temukan sahabat baru yang siap diadopsi.</p>
            <a href="adopsi.php" class="btn btn-outline btn-block">Cari Hewan</a>
        </div>

        <div class="card text-center">
            <img src="assets/donatelogo.png" alt="Donasi" style="height: 80px; margin: 0 auto 15px;">
            <h3>Donasi</h3>
            <p class="text-muted">Bantu biaya perawatan shelter.</p>
            <a href="donasi.php" class="btn btn-outline btn-block">Berdonasi</a>
        </div>

        <div class="card text-center">
            <img src="assets/logokonsultasi.png" alt="Konsultasi" style="height: 80px; margin: 0 auto 15px;">
            <h3>Konsultasi</h3>
            <p class="text-muted">Tanya jawab kesehatan hewan.</p>
            <a href="konsultasi.php" class="btn btn-outline btn-block">Tanya Dokter</a>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>