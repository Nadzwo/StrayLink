<?php
session_start();
require_once 'includes/db.php';

// Cek Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') { header("Location: index.php"); exit; }

$sukses = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul  = trim($_POST['judul']);
    $target = (int) $_POST['target'];
    $deskripsi = trim($_POST['deskripsi']);
    
    // Buat Slug otomatis (judul-donasi-keren)
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));

    $sql = "INSERT INTO donasi (judul, slug, deskripsi, target_amount, status) VALUES (?, ?, ?, ?, 'open')";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$judul, $slug, $deskripsi, $target])) {
        $sukses = "Program donasi berhasil dibuat!";
    }
}

require_once 'includes/header.php';
?>

<main class="flex-center">
    <div class="card" style="width: 100%; max-width: 600px;">
        <h2>Buat Program Donasi</h2>
        <a href="admin.php" class="text-muted small">&larr; Kembali ke Dashboard</a>
        <hr>

        <?php if ($sukses) echo "<div class='alert alert-success'>$sukses</div>"; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Judul Program</label>
                <input type="text" name="judul" placeholder="Contoh: Bantu Shelter X..." required>
            </div>
            
            <div class="form-group">
                <label>Target Dana (Rp)</label>
                <input type="number" name="target" placeholder="Contoh: 5000000" required>
            </div>

            <div class="form-group">
                <label>Cerita / Deskripsi Lengkap</label>
                <textarea name="deskripsi" rows="5" placeholder="Jelaskan tujuan donasi ini..." required></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Terbitkan Program</button>
        </form>
    </div>
</main>
<?php require_once 'includes/footer.php'; ?>