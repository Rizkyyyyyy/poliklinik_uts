<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header("Location: index.php?page=LoginUser.php");
    exit;
}

include 'koneksi.php';

// Tambah Data Pasien
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $umur = $_POST['umur'];
    $query = "INSERT INTO pasien (nama, alamat, umur) VALUES ('$nama', '$alamat', '$umur')";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=pasien.php");
}

// Edit Data Pasien
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $umur = $_POST['umur'];
    $query = "UPDATE pasien SET nama='$nama', alamat='$alamat', umur='$umur' WHERE id='$id'";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=pasien.php");
}

// Hapus Data Pasien
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM pasien WHERE id='$id'";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=pasien.php");
}

// Tampil Data Pasien
$query = "SELECT * FROM pasien";
$hasil = mysqli_query($koneksi, $query);

// Jika ada permintaan edit, ambil data pasien yang akan diedit
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query_edit = "SELECT * FROM pasien WHERE id='$id'";
    $result_edit = mysqli_query($koneksi, $query_edit);
    $edit_data = mysqli_fetch_assoc($result_edit);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Data Pasien</h2>

    <!-- Form Tambah atau Edit Pasien -->
    <form method="POST" class="w-50 mx-auto mb-4">
        <input type="hidden" name="id" value="<?= isset($edit_data) ? $edit_data['id'] : ''; ?>">
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required value="<?= isset($edit_data) ? $edit_data['nama'] : ''; ?>">
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required><?= isset($edit_data) ? $edit_data['alamat'] : ''; ?></textarea>
        </div>
        <div class="mb-3">
            <label>Umur</label>
            <input type="number" name="umur" class="form-control" required value="<?= isset($edit_data) ? $edit_data['umur'] : ''; ?>">
        </div>
        <?php if (isset($edit_data)) { ?>
            <button type="submit" name="update" class="btn btn-success">Update Pasien</button>
        <?php } else { ?>
            <button type="submit" name="tambah" class="btn btn-primary">Tambah Pasien</button>
        <?php } ?>
    </form>

    <!-- Tabel Data Pasien -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Umur</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['alamat']; ?></td>
                    <td><?= $row['umur']; ?></td>
                    <td>
                        <a href="index.php?page=pasien.php&edit=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="index.php?page=pasien.php&hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
