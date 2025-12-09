<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';

// 1. Logika Pencarian & Filter
$keyword = $_GET['q'] ?? '';
$jenis   = $_GET['jenis'] ?? '';

// Query dasar: Hanya tampilkan yang statusnya 'tersedia'
$sql = "SELECT * FROM hewan WHERE status = 'tersedia'";
$params = [];

// Tambahkan filter jika user mencari sesuatu
if (!empty($keyword)) {
    $sql .= " AND (nama LIKE ? OR ras LIKE ? OR deskripsi LIKE ?)";
    $term = "%$keyword%";
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
}
if (!empty($jenis) && $jenis !== 'all') {
    $sql .= " AND jenis = ?";
    $params[] = $jenis;
}

$sql .= " ORDER BY created_at DESC";

// Eksekusi Query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$daftar_hewan = $stmt->fetchAll();
?>

<main>
    <h1 style="margin-bottom: 20px;">Temukan Sahabat Barumu</h1>

    <div class="card" style="padding: 15px;">
        <form action="" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <input type="text" name="q" placeholder="Cari nama atau ras..." value="<?= htmlspecialchars($keyword) ?>" style="flex: 2; min-width: 200px;">

            <select name="jenis" style="flex: 1; min-width: 150px;">
                <option value="all">Semua Jenis</option>
                <option value="kucing" <?= $jenis == 'kucing' ? 'selected' : '' ?>>Kucing</option>
                <option value="anjing" <?= $jenis == 'anjing' ? 'selected' : '' ?>>Anjing</option>
            </select>

            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if (!empty($keyword) || !empty($jenis)): ?>
                <a href="adopsi.php" class="btn btn-outline">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="grid-3">
        <?php if (count($daftar_hewan) > 0): ?>
            <?php foreach ($daftar_hewan as $h): ?>
                <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                    <div style="height: 200px; overflow: hidden; background: #eee;">
                        <img src="<?= !empty($h['foto']) ? 'assets/' . htmlspecialchars($h['foto']) : 'assets/adoptlogo.png' ?>"
                            alt="<?= htmlspecialchars($h['nama']) ?>"
                            style="width: 100%; height: 100%; object-fit: cover;">
                    </div>

                    <div style="padding: 15px; display: flex; flex-direction: column; flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <h3 style="margin: 0 0 5px 0;"><?= htmlspecialchars($h['nama']) ?></h3>
                            <span style="font-size: 0.8rem; background: #e0f2f1; color: var(--primary-dark); padding: 2px 8px; border-radius: 4px;">
                                <?= ucfirst($h['jenis']) ?>
                            </span>
                        </div>

                        <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 10px;">
                            <?= htmlspecialchars($h['ras']) ?> • <?= $h['usia_bulan'] ?> Bulan • <?= ucfirst($h['gender']) ?>
                        </p>

                        <p style="font-size: 0.9rem; color: #555; flex: 1;">
                            <?= htmlspecialchars(substr($h['deskripsi'], 0, 80)) ?>...
                        </p>

                        <a href="adopsi_detail.php?id=<?= $h['id'] ?>" class="btn btn-primary btn-block" style="margin-top: 15px;">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                <h3 class="text-muted">Yah, belum ada hewan yang cocok...</h3>
                <p>Coba ubah kata kunci pencarian atau lihat semua jenis.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>