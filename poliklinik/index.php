<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

// Periksa apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poliklinik</title>
    <!-- Tambahkan CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Selamat Datang di Aplikasi Poliklinik</h1>

        <!-- Navigasi -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Poliklinik</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <?php if ($isLoggedIn): ?>
                            <!-- Tampilkan menu jika sudah login -->
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=dokter.php">Data Dokter</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=pasien.php">Data Pasien</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=periksa.php">Periksa</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=logout.php">Logout</a>
                            </li>
                        <?php else: ?>
                            <!-- Tampilkan menu login dan register jika belum login -->
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=LoginUser.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=RegistrasiUser.php">Register</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Konten Halaman -->
        <div class="mt-4">
            <?php
            // Load halaman berdasarkan parameter 'page' di URL
            if (isset($_GET['page'])) {
                $page = $_GET['page'];

                // Validasi file halaman yang tersedia
                $allowedPages = ['dokter.php', 'pasien.php', 'periksa.php', 'LoginUser.php', 'RegistrasiUser.php', 'logout.php'];
                if (in_array($page, $allowedPages)) {
                    include $page;
                } else {
                    echo "<p>Halaman tidak ditemukan!</p>";
                }
            } else {
                echo "<p>Selamat datang di aplikasi poliklinik. Silakan login atau register untuk mengakses fitur.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Tambahkan JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
