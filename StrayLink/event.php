<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';

// Ambil Event yang statusnya 'published'
$stmt = $pdo->query("SELECT * FROM event WHERE status = 'published' ORDER BY tanggal_mulai ASC");
$events = $stmt->fetchAll();
?>

<main>
    <div class="text-center" style="margin-bottom: 30px;">
        <img src="assets/eventlogo.png" style="height: 60px; margin-bottom: 10px;">
        <h1>Agenda Komunitas</h1>
        <p class="text-muted">Ikuti kegiatan seru bersama pecinta hewan lainnya.</p>
    </div>
    <div class="grid-3">
        <?php foreach ($events as $e): ?>
            <?php
            $tgl = date('d M Y', strtotime($e['tanggal_mulai']));
            $jam = date('H:i', strtotime($e['tanggal_mulai']));
            ?>
            <div class="card" style="display: flex; flex-direction: column;">
                <div style="background: var(--bg-body); padding: 10px; border-radius: 8px; text-align: center; margin-bottom: 15px;">
                    <strong style="color: var(--primary-dark); font-size: 1.1rem; display: block;"><?= $tgl ?></strong>
                    <small>Pukul <?= $jam ?></small>
                </div>

                <h3 style="margin: 0 0 10px 0;"><?= htmlspecialchars($e['judul']) ?></h3>

                <div style="font-size: 0.9rem; color: #555; margin-bottom: 15px;">
                    <strong>📍 Lokasi:</strong> <?= htmlspecialchars($e['lokasi']) ?>
                </div>

                <p class="text-muted" style="font-size: 0.9rem; flex: 1;">
                    <?= htmlspecialchars(substr($e['deskripsi'], 0, 100)) ?>...
                </p>

                <button class="btn btn-outline btn-block" style="margin-top: 15px;" disabled>Akan Datang</button>
            </div>
        <?php endforeach; ?>

        <?php if (count($events) == 0): ?>
            <div class="card text-center" style="grid-column: 1/-1;">
                <p>Belum ada event mendatang.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>