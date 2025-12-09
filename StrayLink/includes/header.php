<?php
// Deteksi halaman aktif
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StrayLink - Penghubung Pecinta Hewan</title>
    
    <link rel="stylesheet" href="css/main.css">
    
    <link rel="icon" href="assets/brandlogo.png">
</head>
<body>

<header>
    <a href="index.php" class="logo">
        <img src="assets/brandlogo.png" alt="Logo">
        <span>StrayLink</span>
    </a>

    <nav>
        <a href="adopsi.php" class="<?= $current_page == 'adopsi.php' ? 'active' : '' ?>">Adopsi</a>
        <a href="donasi.php" class="<?= $current_page == 'donasi.php' ? 'active' : '' ?>">Donasi</a>
        <a href="konsultasi.php" class="<?= $current_page == 'konsultasi.php' ? 'active' : '' ?>">Konsultasi</a>
        <a href="event.php" class="<?= $current_page == 'event.php' ? 'active' : '' ?>">Event</a>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="profile.php" class="<?= $current_page == 'profile.php' ? 'active' : '' ?>">Profil Saya</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary" style="padding: 6px 12px; color: white;">Login</a>
        <?php endif; ?>
    </nav>
</header>