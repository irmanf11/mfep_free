<?php
session_start();
include 'config.php';

if (!isset($_SESSION['pengguna'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM alternatif");
$alternatif = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM kriteria");
$kriteria = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $alternatif_id = $_POST['alternatif_id'];

    // Pengecekan apakah alternatif_id sudah ada di tabel penilaian
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM penilaian WHERE alternatif_id = ?");
    $checkStmt->execute([$alternatif_id]);
    $count = $checkStmt->fetchColumn();

    if ($count > 0) {
        $error = "Penilaian untuk alternatif tersebut sudah ada";
    } else {
        foreach ($kriteria as $row) {
            $nilai = $_POST['kriteria_' . $row['id']];

            $stmt = $pdo->prepare("INSERT INTO penilaian (alternatif_id, kriteria_id, nilai) VALUES (?, ?, ?)");
            $stmt->execute([$alternatif_id, $row['id'], $nilai]);
        }

        header("Location: penilaian-list.php");
        exit;
    }
}

include 'includes/header.php';
?>

<div class="container">
    <div class="card text-dark bg-light">
        <div class="card-header">
            Tambah Penilaian
        </div>
        <div class="card-body">

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="post" action="penilaian-create.php">
                <div class="mb-3">
                    <label for="alternatif_id" class="form-label">Alternatif</label>
                    <select name="alternatif_id" id="alternatif_id" class="form-select" required>
                        <option value="">- Pilih -</option>
                        <?php foreach ($alternatif as $row): ?>
                            <option value="<?php echo $row['id'] ?>"><?php echo $row['kode_alternatif'] . ' - ' . $row['nama_alternatif']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php foreach ($kriteria as $row): ?>
                    <div class="mb-3">
                        <label for="kriteria_<?php echo $row['id'] ?>" class="form-label"><?php echo $row['nama_kriteria'] ?></label>
                        <input type="number" class="form-control" name="kriteria_<?php echo $row['id'] ?>" id="kriteria_<?php echo $row['id'] ?>" required>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="penilaian-list.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/js.php'; ?>
<?php include 'includes/footer.php'; ?>