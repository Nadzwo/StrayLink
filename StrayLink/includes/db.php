<?php
// Mencegah akses langsung ke file ini
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Akses langsung tidak diizinkan.');
}

$dsn = 'mysql:host=localhost;dbname=straylink;charset=utf8mb4';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // Jangan tampilkan detail error ke user di production
    die("Koneksi Database Gagal. Silakan coba lagi nanti.");
}
?>