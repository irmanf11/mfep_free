<?php
session_start();
include 'config.php';

if (!isset($_SESSION['pengguna'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM kriteria");
$kriteria = $stmt->fetchAll();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $alternatif_id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM alternatif WHERE id = ?");
    $stmt->execute([$alternatif_id]);
    $alternatif = $stmt->fetch();

    if (!$alternatif) {
        echo "Alternatif tidak ditemukan.";
        exit;
    }

    // Ambil semua penilaian untuk alternatif_id tertentu dalam satu query
    $stmt = $pdo->prepare("SELECT * FROM penilaian WHERE alternatif_id = ?");
    $stmt->execute([$alternatif_id]);
    $penilaianData = $stmt->fetchAll();

    // Siapkan array penilaian yang diindeks berdasarkan kriteria_id
    $penilaian = [];
    foreach ($penilaianData as $data) {
        $penilaian[$data['kriteria_id']] = $data['nilai'];
    }
} else {
    echo "ID alternatif tidak valid.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($kriteria as $row) {
        $nilai = $_POST['kriteria_' . $row['id']];

        // cek apakah sudah ada data penilaian untuk alternatif dan kriteria masing-masing
        $stmt = $pdo->prepare("SELECT * FROM penilaian WHERE alternatif_id = ? AND kriteria_id = ?");
        $stmt->execute([$alternatif_id, $row['id']]);
        $result = $stmt->fetch();

        if ($result) {
            // Data sudah ada, lakukan update nilai
            $stmt = $pdo->prepare("UPDATE penilaian SET nilai = ? WHERE id = ?");
            $stmt->execute([$nilai, $result['id']]);
        } else {
            // Data belum ada, lakukan insert baru
            $stmt = $pdo->prepare("INSERT INTO penilaian (alternatif_id, kriteria_id, nilai) VALUES (?, ?, ?)");
            $stmt->execute([$alternatif_id, $row['id'], $nilai]);
        }
    }

    header("Location: penilaian-list.php");
    exit;
}

include 'includes/header.php';
?>

<div class="container">
    <div class="card text-dark bg-light">
        <div class="card-header">
            Ubah Penilaian
        </div>
        <div class="card-body">
            <form method="post" action="penilaian-edit.php?id=<?php echo $alternatif_id; ?>">
                <div class="mb-3">
                    <label for="alternatif" class="form-label">Alternatif</label>
                    <input type="text" class="form-control" name="alternatif" id="alternatif" value="<?php echo $alternatif['kode_alternatif'] . ' - ' . $alternatif['nama_alternatif']; ?>" readonly>
                </div>

                <?php foreach ($kriteria as $row): ?>
                    <div class="mb-3">
                        <label for="kriteria_<?php echo $row['id'] ?>" class="form-label"><?php echo $row['nama_kriteria'] ?></label>
                        <input type="number" class="form-control" name="kriteria_<?php echo $row['id'] ?>" id="kriteria_<?php echo $row['id'] ?>" value="<?php echo $penilaian[$row['id']] ?>" required>
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