<?php
session_start();
require_once 'includes/db.php';

// Cek Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') { header("Location: index.php"); exit; }

$sukses = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul  = trim($_POST['judul']);
    $lokasi = trim($_POST['lokasi']);
    $tgl_mulai = $_POST['tgl_mulai']; // Format: YYYY-MM-DDTHH:MM
    $deskripsi = trim($_POST['deskripsi']);
    
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));

    $sql = "INSERT INTO event (judul, slug, lokasi, tanggal_mulai, deskripsi, status) VALUES (?, ?, ?, ?, ?, 'published')";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$judul, $slug, $lokasi, $tgl_mulai, $deskripsi])) {
        $sukses = "Event berhasil dipublikasikan!";
    }
}

require_once 'includes/header.php';
?>

<main class="flex-center">
    <div class="card" style="width: 100%; max-width: 600px;">
        <h2>Buat Event Komunitas</h2>
        <a href="admin.php" class="text-muted small">&larr; Kembali ke Dashboard</a>
        <hr>

        <?php if ($sukses) echo "<div class='alert alert-success'>$sukses</div>"; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Event</label>
                <input type="text" name="judul" required>
            </div>

            <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="lokasi" placeholder="Contoh: Taman Kota Depok / Online (Zoom)" required>
            </div>
            
            <div class="form-group">
                <label>Tanggal & Jam Mulai</label>
                <input type="datetime-local" name="tgl_mulai" required>
            </div>

            <div class="form-group">
                <label>Deskripsi Acara</label>
                <textarea name="deskripsi" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Publikasikan Event</button>
        </form>
    </div>
</main>
<?php require_once 'includes/footer.php'; ?>