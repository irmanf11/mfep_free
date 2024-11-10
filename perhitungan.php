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

$stmt = $pdo->query("SELECT * FROM penilaian");
$penilaianData = $stmt->fetchAll();

$penilaian = [];
foreach ($penilaianData as $data) {
    $penilaian[$data['alternatif_id']][$data['kriteria_id']] = $data['nilai'];
}

// perhtungan metode MFEP
$hasil = [];
$total = [];
foreach ($alternatif as $a) {
    $nilai_total = 0;
    foreach ($kriteria as $k) {
        $nilai_alt_kriteria = $penilaian[$a['id']][$k['id']] ?? 0;
        $nilai = $k['bobot_kriteria'] * $nilai_alt_kriteria;

        $hasil[$a['id']][$k['id']] = $nilai;
        $nilai_total += $nilai;
    }

    $total[$a['id']] = $nilai_total;

    // simpan data ke tabel hasil
    // cek apakah sudah ada data hasil untuk alternatif masing-masing
    $stmt = $pdo->prepare("SELECT * FROM hasil WHERE alternatif_id = ?");
    $stmt->execute([$a['id']]);
    $result = $stmt->fetch();

    if ($result) {
        // Data sudah ada, lakukan update nilai
        $stmt = $pdo->prepare("UPDATE hasil SET nilai_mfep = ? WHERE id = ?");
        $stmt->execute([$nilai_total, $result['id']]);
    } else {
        // Data belum ada, lakukan insert baru
        $stmt = $pdo->prepare("INSERT INTO hasil (alternatif_id, nilai_mfep) VALUES (?, ?)");
        $stmt->execute([$a['id'], $nilai_total]);
    }
}

// ambil hasil dan diurutkan dari nilai_mfep terbesar
$stmt = $pdo->query("SELECT hasil.*,alternatif.kode_alternatif,alternatif.nama_alternatif FROM hasil
    JOIN alternatif ON alternatif.id=hasil.alternatif_id
    ORDER BY nilai_mfep DESC");
$ranking = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container">
    <div class="card text-dark bg-light">
        <div class="card-header">
            Perhitungan Metode MFEP
        </div>
        <div class="card-body">

            <div class="table-responsive mb-4">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Alternatif</th>
                            <?php foreach ($kriteria as $k): ?>
                                <th class="text-center"><?php echo $k['kode_kriteria'] ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($alternatif as $a): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $a['nama_alternatif']; ?></td>
                                <?php foreach ($kriteria as $k): ?>
                                    <td class="text-center"><?php echo $penilaian[$a['id']][$k['id']] ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Alternatif</th>
                            <?php foreach ($kriteria as $k): ?>
                                <th class="text-center"><?php echo $k['kode_kriteria'] ?></th>
                            <?php endforeach; ?>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($alternatif as $a): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $a['nama_alternatif']; ?></td>
                                <?php foreach ($kriteria as $k): ?>
                                    <td class="text-center"><?php echo $hasil[$a['id']][$k['id']] ?></td>
                                <?php endforeach; ?>
                                <td class="text-center"><?php echo $total[$a['id']] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Alternatif</th>
                            <th class="text-center">Nilai MFEP</th>
                            <th class="text-center">Ranking</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $rank = 1; ?>
                        <?php foreach ($ranking as $row): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['nama_alternatif']; ?></td>
                                <td class="text-center"><?php echo $row['nilai_mfep'] ?></td>
                                <td class="text-center"><?php echo $rank++; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include 'includes/js.php'; ?>
<?php include 'includes/footer.php'; ?>