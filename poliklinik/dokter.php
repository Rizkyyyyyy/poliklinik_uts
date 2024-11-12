<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header("Location: index.php?page=LoginUser.php");
    exit;
}

include 'koneksi.php';

// Tambah data dokter
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $spesialis = $_POST['spesialis'];
    $telepon = $_POST['telepon'];
    $query = "INSERT INTO dokter (nama, spesialis, telepon) VALUES ('$nama', '$spesialis', '$telepon')";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=dokter.php");
    exit;
}

// Ambil data dokter untuk di-edit
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM dokter WHERE id='$id'";
    $hasil_edit = mysqli_query($koneksi, $query);
    $dokter = mysqli_fetch_assoc($hasil_edit);
}

// Update data dokter
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $spesialis = $_POST['spesialis'];
    $telepon = $_POST['telepon'];
    $query = "UPDATE dokter SET nama='$nama', spesialis='$spesialis', telepon='$telepon' WHERE id='$id'";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=dokter.php");
    exit;
}

// Hapus data dokter
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM dokter WHERE id='$id'";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=dokter.php");
    exit;
}

$query = "SELECT * FROM dokter";
$hasil = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center"><?= isset($dokter) ? 'Edit Dokter' : 'Tambah Dokter'; ?></h2>
    <form method="POST" class="w-50 mx-auto mb-4">
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="<?= isset($dokter) ? $dokter['nama'] : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label>Spesialis</label>
            <input type="text" name="spesialis" class="form-control" value="<?= isset($dokter) ? $dokter['spesialis'] : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label>Telepon</label>
            <input type="text" name="telepon" class="form-control" value="<?= isset($dokter) ? $dokter['telepon'] : ''; ?>" required>
        </div>
        <?php if (isset($dokter)): ?>
            <!-- Jika sedang mengedit, kirimkan ID dokter yang akan diedit -->
            <input type="hidden" name="id" value="<?= $dokter['id']; ?>">
            <button type="submit" name="update" class="btn btn-success">Update Dokter</button>
        <?php else: ?>
            <button type="submit" name="tambah" class="btn btn-primary">Tambah Dokter</button>
        <?php endif; ?>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Spesialis</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['spesialis']; ?></td>
                    <td><?= $row['telepon']; ?></td>
                    <td>
                        <a href="index.php?page=dokter.php&edit=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="index.php?page=dokter.php&hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
