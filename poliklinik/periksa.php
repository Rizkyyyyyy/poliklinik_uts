<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header("Location: index.php?page=LoginUser.php");
    exit;
}

include 'koneksi.php';

// Tambah Data Periksa
if (isset($_POST['tambah'])) {
    $id_dokter = $_POST['id_dokter'];
    $id_pasien = $_POST['id_pasien'];
    $obat = $_POST['obat'];
    $tanggal = $_POST['tanggal'];
    $catatan = $_POST['catatan'];
    $query = "INSERT INTO periksa (id_dokter, id_pasien, obat, tanggal, catatan) VALUES ('$id_dokter', '$id_pasien', '$obat', '$tanggal', '$catatan')";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=periksa.php");
}

// Edit Data Periksa
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $id_dokter = $_POST['id_dokter'];
    $id_pasien = $_POST['id_pasien'];
    $obat = $_POST['obat'];
    $tanggal = $_POST['tanggal'];
    $catatan = $_POST['catatan'];
    $query = "UPDATE periksa SET id_dokter='$id_dokter', id_pasien='$id_pasien', obat='$obat', tanggal='$tanggal', catatan='$catatan' WHERE id='$id'";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=periksa.php");
}

// Hapus Data Periksa
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM periksa WHERE id='$id'";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=periksa.php");
}

// Tampil Data Periksa
$query = "SELECT periksa.id, dokter.nama AS nama_dokter, pasien.nama AS nama_pasien, periksa.obat, periksa.tanggal, periksa.catatan 
          FROM periksa
          JOIN dokter ON periksa.id_dokter = dokter.id
          JOIN pasien ON periksa.id_pasien = pasien.id";
$hasil = mysqli_query($koneksi, $query);

// Data Dokter dan Pasien untuk Dropdown
$dokter_query = mysqli_query($koneksi, "SELECT * FROM dokter");
$pasien_query = mysqli_query($koneksi, "SELECT * FROM pasien");

// Jika ada permintaan edit, ambil data periksa yang akan diedit
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query_edit = "SELECT * FROM periksa WHERE id='$id'";
    $result_edit = mysqli_query($koneksi, $query_edit);
    $edit_data = mysqli_fetch_assoc($result_edit);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Periksa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Data Periksa</h2>

    <!-- Form Tambah atau Edit Periksa -->
    <form method="POST" class="w-50 mx-auto mb-4">
        <input type="hidden" name="id" value="<?= isset($edit_data) ? $edit_data['id'] : ''; ?>">
        <div class="mb-3">
            <label>Dokter</label>
            <select name="id_dokter" class="form-select" required>
                <?php
                $dokter_query = mysqli_query($koneksi, "SELECT * FROM dokter");
                while ($dokter = mysqli_fetch_assoc($dokter_query)) {
                    $selected = isset($edit_data) && $edit_data['id_dokter'] == $dokter['id'] ? 'selected' : '';
                    echo "<option value='{$dokter['id']}' $selected>{$dokter['nama']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Pasien</label>
            <select name="id_pasien" class="form-select" required>
                <?php
                $pasien_query = mysqli_query($koneksi, "SELECT * FROM pasien");
                while ($pasien = mysqli_fetch_assoc($pasien_query)) {
                    $selected = isset($edit_data) && $edit_data['id_pasien'] == $pasien['id'] ? 'selected' : '';
                    echo "<option value='{$pasien['id']}' $selected>{$pasien['nama']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Obat</label>
            <textarea name="obat" class="form-control" required><?= isset($edit_data) ? $edit_data['obat'] : ''; ?></textarea>
        </div>
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required value="<?= isset($edit_data) ? $edit_data['tanggal'] : ''; ?>">
        </div>
        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="catatan" class="form-control" required><?= isset($edit_data) ? $edit_data['catatan'] : ''; ?></textarea>
        </div>
        <?php if (isset($edit_data)) { ?>
            <button type="submit" name="update" class="btn btn-success">Update Data</button>
        <?php } else { ?>
            <button type="submit" name="tambah" class="btn btn-primary">Tambah Data</button>
        <?php } ?>
    </form>

    <!-- Tabel Data Periksa -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Dokter</th>
                <th>Pasien</th>
                <th>Obat</th>
                <th>Tanggal</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['nama_dokter']; ?></td>
                    <td><?= $row['nama_pasien']; ?></td>
                    <td><?= $row['obat']; ?></td>
                    <td><?= $row['tanggal']; ?></td>
                    <td><?= $row['catatan']; ?></td>
                    <td>
                        <a href="index.php?page=periksa.php&edit=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="index.php?page=periksa.php&hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
