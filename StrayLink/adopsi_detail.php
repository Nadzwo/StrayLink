<?php
session_start();
require_once 'includes/db.php';

// 1. Ambil ID Hewan dari URL
$id_hewan = $_GET['id'] ?? null;

if (!$id_hewan) {
    header("Location: adopsi.php");
    exit;
}

// 2. Ambil Data Hewan
$stmt = $pdo->prepare("SELECT * FROM hewan WHERE id = ?");
$stmt->execute([$id_hewan]);
$hewan = $stmt->fetch();

if (!$hewan) {
    die("Hewan tidak ditemukan.");
}

// 3. Proses Pengajuan Adopsi (POST)
$pesan_sukses = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek Login
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $alasan = trim($_POST['alasan'] ?? '');

    if (empty($alasan)) {
        $error = "Mohon isi alasan Anda ingin mengadopsi.";
    } else {
        // Cek apakah sudah pernah mengajukan untuk hewan ini?
        $cek = $pdo->prepare("SELECT id FROM adopsi WHERE hewan_id = ? AND pengaju_user_id = ?");
        $cek->execute([$id_hewan, $_SESSION['user_id']]);

        if ($cek->fetch()) {
            $error = "Anda sudah pernah mengajukan adopsi untuk hewan ini.";
        } else {
            // Simpan ke Database
            $insert = $pdo->prepare("INSERT INTO adopsi (hewan_id, pengaju_user_id, aplikasi_text) VALUES (?, ?, ?)");
            if ($insert->execute([$id_hewan, $_SESSION['user_id'], $alasan])) {
                $pesan_sukses = "Pengajuan berhasil dikirim! Shelter akan menghubungi Anda.";
                // Update status hewan jadi 'proses_adopsi' (Opsional, tergantung alur bisnis)
                // $pdo->prepare("UPDATE hewan SET status = 'proses_adopsi' WHERE id = ?")->execute([$id_hewan]);
            } else {
                $error = "Terjadi kesalahan sistem.";
            }
        }
    }
}

require_once 'includes/header.php';
?>

<main>
    <a href="adopsi.php" class="btn btn-outline" style="margin-bottom: 20px;">&larr; Kembali ke Daftar</a>

    <div class="card">
        <div style="display: flex; flex-wrap: wrap; gap: 30px;">

            <div style="flex: 1; min-width: 300px;">
                <img src="<?= !empty($hewan['foto']) ? 'assets/' . htmlspecialchars($hewan['foto']) : 'assets/adoptlogo.png' ?>"
                    alt="<?= htmlspecialchars($hewan['nama']) ?>"
                    style="width: 100%; border-radius: var(--radius); object-fit: cover;">
            </div>

            <div style="flex: 1.5; min-width: 300px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h1 style="margin: 0; color: var(--primary-dark);"><?= htmlspecialchars($hewan['nama']) ?></h1>
                    <span style="background: var(--bg-body); padding: 5px 12px; border-radius: 20px; font-weight: bold;">
                        <?= ucfirst($hewan['status']) ?>
                    </span>
                </div>

                <p class="text-muted" style="font-size: 1.1rem; margin-top: 5px;">
                    <?= ucfirst($hewan['jenis']) ?> • <?= htmlspecialchars($hewan['ras']) ?>
                </p>

                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

                <div class="grid-3" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 20px;">
                    <div><strong>Gender:</strong><br> <?= ucfirst($hewan['gender']) ?></div>
                    <div><strong>Umur:</strong><br> <?= $hewan['usia_bulan'] ?> Bulan</div>
                    <div><strong>Ukuran:</strong><br> <?= ucfirst($hewan['ukuran']) ?></div>
                    <div><strong>Warna:</strong><br> <?= htmlspecialchars($hewan['warna']) ?></div>
                    <div><strong>Vaksin:</strong><br> <?= htmlspecialchars($hewan['vaksinasi'] ?? '-') ?></div>
                    <div><strong>Steril:</strong><br> <?= $hewan['sterilized'] ? 'Sudah' : 'Belum' ?></div>
                </div>

                <h3>Tentang <?= htmlspecialchars($hewan['nama']) ?></h3>
                <p style="line-height: 1.6; color: #444;">
                    <?= nl2br(htmlspecialchars($hewan['deskripsi'])) ?>
                </p>

                <div style="margin-top: 15px; background: #f9f9f9; padding: 15px; border-radius: 8px;">
                    <strong>Kondisi Kesehatan:</strong><br>
                    <?= nl2br(htmlspecialchars($hewan['kondisi_kesehatan'] ?? '-')) ?>
                </div>

            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">

        <div id="form-adopsi">
            <h3>Tertarik Mengadopsi?</h3>

            <?php if ($pesan_sukses): ?>
                <div class="alert alert-success"><?= $pesan_sukses ?></div>
            <?php elseif ($error): ?>
                <div class="alert"><?= $error ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($hewan['status'] == 'tersedia'): ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="alasan">Mengapa Anda ingin mengadopsi <?= htmlspecialchars($hewan['nama']) ?>?</label>
                            <textarea name="alasan" id="alasan" rows="4" placeholder="Ceritakan sedikit tentang diri Anda dan lingkungan rumah Anda..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Permohonan Adopsi</button>
                    </form>
                <?php else: ?>
                    <div class="alert">Maaf, hewan ini sedang dalam proses adopsi atau sudah diadopsi.</div>
                <?php endif; ?>

            <?php else: ?>
                <p>Silakan login terlebih dahulu untuk mengajukan adopsi.</p>
                <a href="login.php" class="btn btn-primary">Login Sekarang</a>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>