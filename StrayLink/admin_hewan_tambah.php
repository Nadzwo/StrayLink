<?php
session_start();
require_once 'includes/db.php';

// Cek Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') { header("Location: index.php"); exit; }

$sukses = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil Data Input
    $nama   = trim($_POST['nama']);
    $jenis  = $_POST['jenis'];
    $ras    = trim($_POST['ras']);
    $gender = $_POST['gender'];
    $usia   = (int) $_POST['usia'];
    $warna  = trim($_POST['warna']);
    $deskripsi = trim($_POST['deskripsi']);
    
    // Upload Foto
    $foto_name = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_name = "hewan_" . time() . "." . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], "assets/" . $foto_name);
    }

    // Simpan ke Database
    $sql = "INSERT INTO hewan (nama, jenis, ras, gender, usia_bulan, warna, deskripsi, foto, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'tersedia')";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$nama, $jenis, $ras, $gender, $usia, $warna, $deskripsi, $foto_name])) {
        $sukses = "Hewan berhasil ditambahkan!";
    } else {
        $error = "Gagal menyimpan data.";
    }
}

require_once 'includes/header.php';
?>

<main class="flex-center">
    <div class="card" style="width: 100%; max-width: 600px;">
        <h2>Tambah Hewan Adopsi</h2>
        <a href="admin.php" class="text-muted small">&larr; Kembali ke Dashboard</a>
        <hr>

        <?php if ($sukses) echo "<div class='alert alert-success'>$sukses</div>"; ?>
        <?php if ($error) echo "<div class='alert'>$error</div>"; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Hewan</label>
                <input type="text" name="nama" required>
            </div>
            
            <div class="grid-3" style="grid-template-columns: 1fr 1fr;">
                <div class="form-group">
                    <label>Jenis</label>
                    <select name="jenis">
                        <option value="kucing">Kucing</option>
                        <option value="anjing">Anjing</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender">
                        <option value="jantan">Jantan</option>
                        <option value="betina">Betina</option>
                    </select>
                </div>
            </div>

            <div class="grid-3" style="grid-template-columns: 1fr 1fr 1fr;">
                <div class="form-group">
                    <label>Ras</label>
                    <input type="text" name="ras" placeholder="Contoh: Domestik">
                </div>
                <div class="form-group">
                    <label>Usia (Bulan)</label>
                    <input type="number" name="usia" placeholder="2">
                </div>
                <div class="form-group">
                    <label>Warna</label>
                    <input type="text" name="warna" placeholder="Oren/Putih">
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi & Kondisi</label>
                <textarea name="deskripsi" rows="4" placeholder="Sifatnya manja, sudah vaksin..."></textarea>
            </div>

            <div class="form-group">
                <label>Foto Hewan</label>
                <input type="file" name="foto" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Simpan Hewan</button>
        </form>
    </div>
</main>
<?php require_once 'includes/footer.php'; ?>