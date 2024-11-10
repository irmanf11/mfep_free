<?php
session_start();
include 'config.php';

if (!isset($_SESSION['pengguna'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE id = ?");
    $stmt->execute([$id]);
    $pengguna = $stmt->fetch();

    if (!$pengguna) {
        echo "Pengguna tidak ditemukan.";
        exit;
    }
} else {
    echo "ID pengguna tidak valid.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $pengguna['password'];

    $stmt = $pdo->prepare("UPDATE pengguna SET nama_lengkap = ?, username = ?, password = ? WHERE id = ?");
    $stmt->execute([$nama_lengkap, $username, $password, $id]);

    header("Location: pengguna-list.php");
    exit;
}

include 'includes/header.php';
?>

<div class="container">
    <div class="card text-dark bg-light">
        <div class="card-header">
            Ubah Pengguna
        </div>
        <div class="card-body">
            <form method="post" action="pengguna-edit.php?id=<?php echo $id; ?>">
                <div class="mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" value="<?php echo $pengguna['nama_lengkap']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" value="<?php echo $pengguna['username']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password (kosongkan jika tidak ingin diubah)</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="pengguna-list.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/js.php'; ?>
<?php include 'includes/footer.php'; ?>