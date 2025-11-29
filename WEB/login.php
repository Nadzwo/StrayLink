<?php 
declare(strict_types=1);
session_start();

// --- 1. Konfigurasi Database ---
$dsn = 'mysql:host=localhost;dbname=straylink;charset=utf8mb4';
$dbUser = 'root';
$dbPass = '';

$errors = []; // Siapkan array untuk menampung pesan error

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch(PDOException $e) {
    // Jika koneksi gagal, masukkan ke error array, jangan langsung die()
    $errors[] = "Gagal koneksi ke Database.";
}

// --- 2. Proses Logika Login ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    // Validasi Input
    if ($email === '' || $password === '') {
        $errors[] = "Email atau password tidak boleh kosong.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    } else {
        // Jika validasi input lolos, cek ke database
        // Cek apakah $pdo berhasil connect sebelumnya
        if (isset($pdo)) {
            try {
                $stmt = $pdo->prepare('SELECT id, password, nama, role FROM users WHERE email = ? LIMIT 1');
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                // Verifikasi Password
                if ($user && password_verify($password, $user['password'])) {
                    // --- LOGIN SUKSES ---
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_nama'] = $user['nama']; // Perbaikan: pakai 'nama' sesuai DB
                    $_SESSION['user_role'] = $user['role'];

                    // Opsional: Redirect ke halaman dashboard/home
                    // header("Location: index.php"); 
                    // exit;

                    // Untuk sementara kita tampilkan pesan sukses di sini:
                    $successMessage = "Login berhasil! Selamat datang, " . htmlspecialchars($user['nama']);
                } else {
                    // Password salah atau user tidak ditemukan
                    $errors[] = "Email atau password salah.";
                }
            } catch (PDOException $e) {
                $errors[] = "Terjadi kesalahan sistem database.";
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
    <link rel="stylesheet" href="style/login.css">
    <title>Login - StrayLink</title>
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
                <img class="logo" src="logo.png" alt="Logo StrayLink" style="max-height: 80px;">     
                
                <form class="daftar-akun" method="post" action="">
                    <h2>Login</h2>

                    <?php if (isset($successMessage)): ?>
                        <div style="background-color: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 12px; text-align: center;">
                            <?= $successMessage ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="error-box">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li>• <?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <input id="email" name="email" type="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    <input id="password" name="password" type="password" placeholder="Password" required>

                    <button type="submit">Masuk</button>

                    <p class="login-text">Belum punya akun? <a class="login-link" href="register.php">Daftar</a></p>
                </form>
            </div>
        </section>
    </div>
</body>
</html>


// cobbaaaaaa
