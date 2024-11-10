<?php
session_start();
include 'config.php';

if (!isset($_SESSION['pengguna'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_alternatif = $_POST['kode_alternatif'];
    $nama_alternatif = $_POST['nama_alternatif'];

    $stmt = $pdo->prepare("INSERT INTO alternatif (kode_alternatif, nama_alternatif) VALUES (?, ?)");
    $stmt->execute([$kode_alternatif, $nama_alternatif]);

    header("Location: alternatif-list.php");
    exit;
}

include 'includes/header.php';
?>

<div class="container">
    <div class="card text-dark bg-light">
        <div class="card-header">
            Tambah Alternatif
        </div>
        <div class="card-body">
            <form method="post" action="alternatif-create.php">
                <div class="mb-3">
                    <label for="kode_alternatif" class="form-label">Kode Alternatif</label>
                    <input type="text" class="form-control" name="kode_alternatif" id="kode_alternatif" required>
                </div>
                <div class="mb-3">
                    <label for="nama_alternatif" class="form-label">Nama Alternatif</label>
                    <input type="text" class="form-control" name="nama_alternatif" id="nama_alternatif" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="alternatif-list.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/js.php'; ?>
<?php include 'includes/footer.php'; ?>