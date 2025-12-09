<?php
session_start();
require_once 'includes/db.php';

$id_donasi = $_GET['id'] ?? null;
if (!$id_donasi) { header("Location: donasi.php"); exit; }

// Ambil Info Donasi
$stmt = $pdo->prepare("SELECT * FROM donasi WHERE id = ?");
$stmt->execute([$id_donasi]);
$donasi = $stmt->fetch();

if (!$donasi) die("Program donasi tidak ditemukan.");

// Proses Pembayaran (Simulasi)
$sukses = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nominal = (int) ($_POST['nominal'] ?? 0);
    $metode  = $_POST['metode'] ?? 'Transfer Bank';
    $nama_donatur = $_POST['nama_donatur'] ?? 'Hamba Allah'; // Jika tidak login

    // Jika login, gunakan ID user
    $user_id = $_SESSION['user_id'] ?? null;
    
    // Jika user login, nama donatur otomatis dari session (opsional, tapi form override)
    
    if ($nominal < 10000) {
        $error = "Minimal donasi Rp 10.000";
    } else {
        $sql = "INSERT INTO pembayaran_donasi (donasi_id, donor_user_id, nominal, metode_pembayaran, status) VALUES (?, ?, ?, ?, 'pending')";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$id_donasi, $user_id, $nominal, $metode])) {
            $sukses = true;
        }
    }
}

require_once 'includes/header.php';
?>

<main class="flex-center" style="align-items: flex-start;">
    <div class="card" style="width: 100%; max-width: 600px;">
        <?php if ($sukses): ?>
            <div class="text-center">
                <h2 style="color: var(--primary);">Terima Kasih!</h2>
                <p>Niat baik Anda telah kami catat.</p>
                <div class="alert alert-success">
                    Silakan transfer <strong>Rp <?= number_format($nominal, 0, ',', '.') ?></strong><br>
                    ke Rekening BCA: <strong>123-456-7890</strong><br>
                    a.n StrayLink Foundation
                </div>
                <p class="text-muted small">Harap konfirmasi bukti transfer ke WhatsApp admin.</p>
                <a href="donasi.php" class="btn btn-outline">Kembali</a>
            </div>
        <?php else: ?>
            <h2 class="text-center">Donasi untuk: <?= htmlspecialchars($donasi['judul']) ?></h2>
            <p class="text-center text-muted">Setiap rupiah sangat berarti.</p>
            <hr>

            <?php if (isset($error)) echo "<div class='alert'>$error</div>"; ?>

            <form action="" method="POST">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="form-group">
                        <label>Nama Anda (Opsional)</label>
                        <input type="text" name="nama_donatur" placeholder="Hamba Allah">
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Nominal Donasi (Rp)</label>
                    <input type="number" name="nominal" placeholder="Contoh: 50000" min="10000" required>
                </div>

                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select name="metode">
                        <option value="Transfer Bank">Transfer Bank (BCA/Mandiri)</option>
                        <option value="E-Wallet">GoPay / OVO / Dana</option>
                        <option value="QRIS">QRIS</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Lanjut Pembayaran</button>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>