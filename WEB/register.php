<?php

declare(strict_types=1); // Memastikan pengetikan ketat (maksudnya tipe data harus sesuai)
session_start(); // memulai session dan menyimpan data user

// Konfigurasi Database
$dsn = 'mysql:host=localhost;dbname=straylink;charset=utf8mb4';
$dbUser = 'root';
$dbPass = '';

// Membuat koneksi ke database menggunakan PDO (PDO adalah cara yang lebih aman dan fleksibel untuk mengakses database di PHP)
try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Gagal koneksi ke database";
    exit;
}

//array untuk menyompan error tujuannya agar mudah untuk ditampilkan
$errors = [];


// Jika Form dikirm, proses datanya
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ambil input lalu trim
    $nama = trim((string)($_POST['nama'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $password = trim((string)($_POST['password'] ?? ''));
    $password_confirm = trim((string)($_POST['password_confirm'] ?? ''));

    // Validasi input
    if (empty($nama) || empty($email) || empty($password) || empty($password_confirm)) {
        $errors[] = 'Semua field harus diisi.';
    }
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password harus minimal 8 karakter.';
    }
    if (!empty($password) && $password !== $password_confirm) {
        $errors[] = 'Konfirmasi password tidak sesuai.';
    }

    if (empty($errors)) {
        //cek apakah email sudah terdaftar
        $check =$pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $check->execute([$email]);

        if ($check->fetch()) {
            $errors[] = 'Email sudah terdaftar.';
        } else {
            //Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            //Simpan user
            $insert = $pdo->prepare('INSERT INTO users (nama, email, password) VALUES (?, ?, ?)');
            if ($insert->execute([$nama, $email, $password_hash])) {
                // Redirect ke halaman login setelah registrasi sukses
                echo "<script>alert('Registrasi Berhasil'); window.location.href='login.php';</script>";
                exit;
            } else {
                $errors[] = 'Gagal menyimpan ke database, Silakan coba lagi.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/register.css">
    <link rel="icon" type="image" href="logo.png">
    <title>StrayLink</title>
</head>

<body>
    <div class="container">
        <section class="right-side">
            <div class="right-content">
                <h1>Selamat datang, <span class="user-rcon">Pahlawan!</span></h1>
                <p>Berani menyelamatkan, berani mencintai.</p>
            </div>
        </section>
        <section class="left-side">
            <div class="left-content">
                <img class="logo" src="Logo StrayLink.png" alt="Logo">                
                <form class="daftar-akun" method="post" action="">
                    <h2>Daftar Akun</h2>
                    <input id="name"  name="nama" type="text" placeholder="Nama">
                    <input id="email" name="email" type="email" placeholder="Email">
                    <input id="password" name="password" type="password" placeholder="Password">
                    <input id="password-confirm" name="password_confirm" type="password" placeholder="Konfirmasi Password">
                                    <?php if (!empty($errors)): ?>
                    <div class="error-box">
                        <ul>
                            <?php foreach ($errors as $e): ?>
                                <li><?=  htmlspecialchars($e) ?></li>
                                <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                    <button>Daftar</button>

                    <p class="login-text">Sudah punya akun? <a class="login-link" href="login.php">Login</a></p>
                </form>
            </div>
        </section>

    </div>
</body>

</html>