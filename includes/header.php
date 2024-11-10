<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Aplikasi MFEP</title>

    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <link rel="shortcut icon" href="assets/img/logo.png" type="image/x-icon">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                Aplikasi MFEP
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <?php $current_page = basename($_SERVER['PHP_SELF']); ?>

                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'pengguna-list.php' ? 'active' : ''; ?>" href="pengguna-list.php">Pengguna</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'kriteria-list.php' ? 'active' : ''; ?>" href="kriteria-list.php">Kriteria</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'alternatif-list.php' ? 'active' : ''; ?>" href="alternatif-list.php">Alternatif</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'penilaian-list.php' ? 'active' : ''; ?>" href="penilaian-list.php">Penilaian</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'perhitungan.php' ? 'active' : ''; ?>" href="perhitungan.php">Perhitungan</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>