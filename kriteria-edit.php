<?php
session_start();
include 'config.php';

if (!isset($_SESSION['pengguna'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM kriteria WHERE id = ?");
    $stmt->execute([$id]);
    $kriteria = $stmt->fetch();

    if (!$kriteria) {
        echo "Kriteria tidak ditemukan.";
        exit;
    }
} else {
    echo "ID kriteria tidak valid.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_kriteria = $_POST['kode_kriteria'];
    $nama_kriteria = $_POST['nama_kriteria'];
    $bobot_kriteria = $_POST['bobot_kriteria'];

    $stmt = $pdo->prepare("UPDATE kriteria SET kode_kriteria = ?, nama_kriteria = ?, bobot_kriteria = ? WHERE id = ?");
    $stmt->execute([$kode_kriteria, $nama_kriteria, $bobot_kriteria, $id]);

    header("Location: kriteria-list.php");
    exit;
}

include 'includes/header.php';
?>

<div class="container">
    <div class="card text-dark bg-light">
        <div class="card-header">
            Ubah Kriteria
        </div>
        <div class="card-body">
            <form method="post" action="kriteria-edit.php?id=<?php echo $id; ?>">
                <div class="mb-3">
                    <label for="kode_kriteria" class="form-label">Kode Kriteria</label>
                    <input type="text" class="form-control" name="kode_kriteria" id="kode_kriteria" value="<?php echo $kriteria['kode_kriteria']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="nama_kriteria" class="form-label">Nama Kriteria</label>
                    <input type="text" class="form-control" name="nama_kriteria" id="nama_kriteria" value="<?php echo $kriteria['nama_kriteria']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="bobot_kriteria" class="form-label">Bobot Kriteria</label>
                    <input type="number" step="0.01" class="form-control" name="bobot_kriteria" id="bobot_kriteria" value="<?php echo $kriteria['bobot_kriteria']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="kriteria-list.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/js.php'; ?>
<?php include 'includes/footer.php'; ?>