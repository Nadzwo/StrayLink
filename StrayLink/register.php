<?php
// 1. Mulai Session & Koneksi
session_start();
require_once 'includes/db.php';

// Jika sudah login, ngapain daftar lagi?
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$errors = [];

// 2. Proses Form Register
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirm'] ?? '';

    // Validasi
    if (empty($nama) || empty($email) || empty($password)) {
        $errors[] = "Semua kolom wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password minimal 8 karakter.";
    } elseif ($password !== $confirm) {
        $errors[] = "Konfirmasi password tidak cocok.";
    } else {
        // Cek apakah email sudah ada
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Email ini sudah terdaftar.";
        } else {
            // Simpan ke Database
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'user')");
            
            if ($stmt->execute([$nama, $email, $hash])) {
                // Sukses -> Redirect ke login
                header("Location: login.php?registered=1");
                exit;
            } else {
                $errors[] = "Gagal mendaftar. Silakan coba lagi.";
            }
        }
    }
}

// 3. Panggil Header
require_once 'includes/header.php';
?>

<main class="flex-center">
    <div class="card" style="width: 100%; max-width: 500px;">
        <h2 class="text-center" style="margin-bottom: 20px;">Daftar Akun Baru</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" placeholder="Nama Panggilan / Lengkap" required value="<?= htmlspecialchars($nama ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="contoh@email.com" required value="<?= htmlspecialchars($email ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" required>
            </div>

            <div class="form-group">
                <label for="password_confirm">Konfirmasi Password</label>
                <input type="password" name="password_confirm" id="password_confirm" placeholder="Ulangi password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 10px;">Daftar Sekarang</button>
        </form>

        <p class="text-center" style="margin-top: 20px; font-size: 0.9rem;">
            Sudah punya akun? <a href="login.php" style="color: var(--primary); font-weight: bold;">Login di sini</a>
        </p>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>