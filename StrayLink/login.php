<?php
// 1. Mulai Session & Koneksi
session_start();
require_once 'includes/db.php';

// Jika user sudah login, lempar ke index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$errors = [];

// 2. Proses Form Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi Sederhana
    if (empty($email) || empty($password)) {
        $errors[] = "Email dan password wajib diisi.";
    } else {
        // Cek User di Database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Verifikasi Password
        if ($user && password_verify($password, $user['password'])) {
            // Login Sukses
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nama'] = $user['nama'];
            $_SESSION['user_role'] = $user['role'];

            // Redirect ke halaman utama
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Email atau password salah.";
        }
    }
}

// 3. Panggil Header (Otomatis muat CSS & Navbar)
require_once 'includes/header.php';
?>

<main class="flex-center">
    <div class="card" style="width: 100%; max-width: 400px;">
        <h2 class="text-center" style="margin-bottom: 20px;">Masuk ke StrayLink</h2>

        <?php if (isset($_GET['registered'])): ?>
            <div class="alert alert-success text-center">
                Registrasi berhasil! Silakan login.
            </div>
        <?php endif; ?>

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
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="contoh@email.com" required value="<?= htmlspecialchars($email ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 10px;">Masuk</button>
        </form>

        <p class="text-center" style="margin-top: 20px; font-size: 0.9rem;">
            Belum punya akun? <a href="register.php" style="color: var(--primary); font-weight: bold;">Daftar Sekarang</a>
        </p>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>