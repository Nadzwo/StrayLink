<?php
session_start();
require_once 'includes/db.php';

// Cek Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sukses = "";
$error = "";

// --- 1. PROSES UPDATE PROFIL (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    
    // Upload Foto (Sederhana)
    $foto_sql = ""; 
    $params = [$nama, $telepon, $alamat];

    // Cek apakah ada file foto yang diupload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $filename = "user_" . $user_id . "_" . time() . "." . $ext;
        $target = "assets/" . $filename;
        
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target)) {
            $foto_sql = ", profile_photo = ?";
            $params[] = $filename;
        }
    }

    $params[] = $user_id; // Untuk WHERE clause

    $sql = "UPDATE users SET nama = ?, telepon = ?, alamat = ? $foto_sql WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute($params)) {
        $sukses = "Profil berhasil diperbarui!";
        $_SESSION['user_nama'] = $nama; // Update session nama juga
    } else {
        $error = "Gagal memperbarui profil.";
    }
}

// --- 2. AMBIL DATA USER ---
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// --- 3. AMBIL RIWAYAT AKTIVITAS ---

// A. Riwayat Adopsi (Join dengan tabel hewan untuk dapat nama hewan)
$stmt = $pdo->prepare("
    SELECT a.*, h.nama as nama_hewan, h.jenis 
    FROM adopsi a 
    JOIN hewan h ON a.hewan_id = h.id 
    WHERE a.pengaju_user_id = ? 
    ORDER BY a.tanggal_diajukan DESC
");
$stmt->execute([$user_id]);
$riwayat_adopsi = $stmt->fetchAll();

// B. Riwayat Donasi (Join dengan tabel donasi untuk dapat judul program)
$stmt = $pdo->prepare("
    SELECT p.*, d.judul 
    FROM pembayaran_donasi p 
    JOIN donasi d ON p.donasi_id = d.id 
    WHERE p.donor_user_id = ? 
    ORDER BY p.tanggal_pembayaran DESC
");
$stmt->execute([$user_id]);
$riwayat_donasi = $stmt->fetchAll();

// C. Riwayat Konsultasi
$stmt = $pdo->prepare("SELECT * FROM konsultasi WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$riwayat_konsul = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<main>
    <div class="card">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
            <div style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; border: 4px solid var(--primary); margin-bottom: 15px;">
                <img src="<?= !empty($user['profile_photo']) ? 'assets/' . htmlspecialchars($user['profile_photo']) : 'assets/brandlogo.png' ?>" 
                     style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            
            <h2 style="margin: 0; color: var(--primary-dark);"><?= htmlspecialchars($user['nama']) ?></h2>
            <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
            
            <div style="margin-top: 15px;">
                <a href="logout.php" class="btn btn-danger" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
            </div>
        </div>

        <hr style="margin: 25px 0; border: 0; border-top: 1px solid #eee;">

        <h3 style="margin-bottom: 15px;">Edit Data Diri</h3>
        
        <?php if ($sukses) echo "<div class='alert alert-success'>$sukses</div>"; ?>
        <?php if ($error) echo "<div class='alert'>$error</div>"; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="grid-3" style="grid-template-columns: repeat(2, 1fr);">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="text" name="telepon" value="<?= htmlspecialchars($user['telepon'] ?? '') ?>" placeholder="08xxx">
                </div>
            </div>
            
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" rows="2" placeholder="Alamat lengkap..."><?= htmlspecialchars($user['alamat'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Ganti Foto Profil</label>
                <input type="file" name="avatar" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>

    <div class="grid-3">
        
        <div class="card">
            <h3 style="border-bottom: 2px solid var(--bg-body); padding-bottom: 10px;">🐾 Riwayat Adopsi</h3>
            <?php if (empty($riwayat_adopsi)): ?>
                <p class="text-muted small">Belum ada pengajuan.</p>
            <?php else: ?>
                <ul style="padding-left: 20px;">
                    <?php foreach ($riwayat_adopsi as $r): ?>
                        <li style="margin-bottom: 10px;">
                            <strong><?= htmlspecialchars($r['nama_hewan']) ?></strong> (<?= $r['jenis'] ?>)<br>
                            <span class="text-muted small">Status: <strong><?= $r['status'] ?></strong></span><br>
                            <span class="text-muted small"><?= date('d M Y', strtotime($r['tanggal_diajukan'])) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3 style="border-bottom: 2px solid var(--bg-body); padding-bottom: 10px;">💖 Riwayat Donasi</h3>
            <?php if (empty($riwayat_donasi)): ?>
                <p class="text-muted small">Belum ada donasi.</p>
            <?php else: ?>
                <ul style="padding-left: 20px;">
                    <?php foreach ($riwayat_donasi as $d): ?>
                        <li style="margin-bottom: 10px;">
                            <strong>Rp <?= number_format($d['nominal'], 0, ',', '.') ?></strong><br>
                            <span class="small"><?= htmlspecialchars($d['judul']) ?></span><br>
                            <span class="text-muted small">Status: <?= $d['status'] ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3 style="border-bottom: 2px solid var(--bg-body); padding-bottom: 10px;">💬 Riwayat Konsultasi</h3>
            <?php if (empty($riwayat_konsul)): ?>
                <p class="text-muted small">Belum ada konsultasi.</p>
            <?php else: ?>
                <ul style="padding-left: 20px;">
                    <?php foreach ($riwayat_konsul as $k): ?>
                        <li style="margin-bottom: 10px;">
                            <strong><?= htmlspecialchars($k['judul']) ?></strong><br>
                            <span class="text-muted small">Status: <?= $k['status'] ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php require_once 'includes/footer.php'; ?>