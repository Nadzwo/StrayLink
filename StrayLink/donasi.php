<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';

// Ambil data donasi yang statusnya 'open'
$stmt = $pdo->query("SELECT * FROM donasi WHERE status = 'open' ORDER BY created_at DESC");
$list_donasi = $stmt->fetchAll();
?>

<main>
    <div class="text-center" style="margin-bottom: 30px;">
        <h1>Bantu Mereka Bertahan Hidup</h1>
        <p class="text-muted">Donasi Anda sangat berarti untuk pakan, obat, dan tempat tinggal yang layak.</p>
    </div>

    <div class="grid-3">
        <?php foreach ($list_donasi as $d): ?>
            <?php 
                // Hitung Persentase Terkumpul
                $persen = 0;
                if ($d['target_amount'] > 0) {
                    $persen = ($d['collected_amount'] / $d['target_amount']) * 100;
                    if ($persen > 100) $persen = 100;
                }
            ?>
            <div class="card" style="display: flex; flex-direction: column;">
                <h3 style="margin-top: 0; color: var(--primary-dark);"><?= htmlspecialchars($d['judul']) ?></h3>
                
                <p class="text-muted" style="font-size: 0.9rem; flex: 1;">
                    <?= htmlspecialchars(substr($d['deskripsi'], 0, 100)) ?>...
                </p>

                <div style="margin: 15px 0;">
                    <div style="background: #eee; height: 10px; border-radius: 5px; overflow: hidden;">
                        <div style="background: var(--primary); width: <?= $persen ?>%; height: 100%;"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-top: 5px;">
                        <span>Terkumpul: <strong>Rp <?= number_format($d['collected_amount'], 0, ',', '.') ?></strong></span>
                        <span>Target: <strong>Rp <?= number_format($d['target_amount'], 0, ',', '.') ?></strong></span>
                    </div>
                </div>

                <a href="donasi_detail.php?id=<?= $d['id'] ?>" class="btn btn-primary btn-block">Donasi Sekarang</a>
            </div>
        <?php endforeach; ?>

        <?php if (count($list_donasi) == 0): ?>
            <p class="text-center" style="grid-column: 1/-1;">Belum ada program donasi yang aktif saat ini.</p>
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>