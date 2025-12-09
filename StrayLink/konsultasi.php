<?php
session_start();
require_once 'includes/db.php';

// Proses Kirim Konsultasi
$sukses = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $judul = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if (empty($judul) || empty($deskripsi)) {
        $error = "Judul dan keluhan wajib diisi.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO konsultasi (user_id, judul, deskripsi) VALUES (?, ?, ?)");
        if ($stmt->execute([$_SESSION['user_id'], $judul, $deskripsi])) {
            $sukses = "Pertanyaan terkirim! Dokter kami akan segera menjawabnya.";
        } else {
            $error = "Gagal mengirim data.";
        }
    }
}

// Ambil Riwayat Konsultasi User Ini
$riwayat = [];
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM konsultasi WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $riwayat = $stmt->fetchAll();
}

require_once 'includes/header.php';
?>

<main>
    <div class="text-center" style="margin-bottom: 30px;">
        <h1>Konsultasi Dokter Hewan</h1>
        <p class="text-muted">Punya pertanyaan seputar kesehatan anabul? Tanyakan di sini.</p>
    </div>

    <div style="display: flex; flex-wrap: wrap; gap: 30px; align-items: flex-start;">
        
        <div style="flex: 1; min-width: 300px;">
            <div class="card">
                <h3>Buat Pertanyaan Baru</h3>
                
                <?php if ($sukses): ?>
                    <div class="alert alert-success"><?= $sukses ?></div>
                <?php elseif ($error): ?>
                    <div class="alert"><?= $error ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label>Judul / Subjek</label>
                            <input type="text" name="judul" placeholder="Misal: Kucing muntah kuning" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Keluhan</label>
                            <textarea name="deskripsi" rows="5" placeholder="Jelaskan gejalanya, sudah berapa lama, dll..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Kirim Pertanyaan</button>
                    </form>
                <?php else: ?>
                    <div class="text-center" style="padding: 20px;">
                        <p>Anda harus login untuk berkonsultasi.</p>
                        <a href="login.php" class="btn btn-primary">Login</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <h3 style="margin-bottom: 15px;">Riwayat Konsultasi Saya</h3>
            
            <?php if (empty($riwayat)): ?>
                <div class="card text-center text-muted">
                    <p>Belum ada konsultasi.</p>
                </div>
            <?php else: ?>
                <?php foreach ($riwayat as $k): ?>
                    <div class="card" style="padding: 15px; border-left: 5px solid <?= $k['status'] == 'dijawab' ? 'var(--primary)' : '#ccc' ?>;">
                        <div style="display: flex; justify-content: space-between;">
                            <strong><?= htmlspecialchars($k['judul']) ?></strong>
                            <small class="text-muted"><?= date('d M Y', strtotime($k['created_at'])) ?></small>
                        </div>
                        <p style="font-size: 0.9rem; margin-top: 5px;"><?= nl2br(htmlspecialchars($k['deskripsi'])) ?></p>
                        
                        <?php if ($k['status'] == 'dijawab'): ?>
                            <div style="background: #e6fff9; padding: 10px; border-radius: 8px; margin-top: 10px; font-size: 0.9rem;">
                                <strong>Dokter Menjawab:</strong><br>
                                <?= nl2br(htmlspecialchars($k['jawaban'])) ?>
                            </div>
                        <?php else: ?>
                            <div style="margin-top: 10px; font-size: 0.8rem; color: #888; font-style: italic;">
                                Menunggu jawaban dokter...
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php require_once 'includes/footer.php'; ?>