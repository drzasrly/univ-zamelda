<?php
include 'config/database.php';

$mahasiswa_result = mysqli_query($kon, "SELECT id_mahasiswa, nama_mahasiswa FROM mahasiswa");

if (isset($_POST['simpan'])) {
    $cek = mysqli_query($kon, "SELECT MAX(id_pembayaran) as max_id FROM pembayaran");
    $data = mysqli_fetch_assoc($cek);
    $id_baru = $data['max_id'] + 1;

    $id_mahasiswa = $_POST['id_mahasiswa'];
    $jenis = $_POST['jenis_pembayaran'];
    $jumlah = $_POST['jumlah'];
    $tgl = $_POST['tgl_bayar'];
    $status = $_POST['status'];

    mysqli_query($kon, "INSERT INTO pembayaran 
        (id_pembayaran, id_mahasiswa, jenis_pembayaran, jumlah, tgl_bayar, status)
        VALUES 
        ('$id_baru', '$id_mahasiswa', '$jenis', '$jumlah', '$tgl', '$status')");

    header("Location: pembayaran.php");
}

if (isset($_POST['update'])) {
    $id = $_POST['id_pembayaran'];
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $jenis = $_POST['jenis_pembayaran'];
    $jumlah = $_POST['jumlah'];
    $tgl = $_POST['tgl_bayar'];
    $status = $_POST['status'];

    mysqli_query($kon, "UPDATE pembayaran SET 
        id_mahasiswa='$id_mahasiswa', jenis_pembayaran='$jenis', jumlah='$jumlah',
        tgl_bayar='$tgl', status='$status'
        WHERE id_pembayaran='$id'");

    header("Location: pembayaran.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($kon, "DELETE FROM pembayaran WHERE id_pembayaran='$id'");
    header("Location: pembayaran.php");
}

$edit = false;
$data_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $result = mysqli_query($kon, "SELECT * FROM pembayaran WHERE id_pembayaran='$id'");
    $data_edit = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Pembayaran</title>
</head>
<body>
<h2 align="center">Data Pembayaran Mahasiswa</h2>

<form method="post" action="">
    <?php if ($edit): ?>
        <input type="hidden" name="id_pembayaran" value="<?= $data_edit['id_pembayaran'] ?>">
    <?php endif; ?>
    <table align="center" cellpadding="5">
        <tr>
            <td>Mahasiswa</td>
            <td>
                <select name="id_mahasiswa" required>
                    <option value="">-- Pilih Mahasiswa --</option>
                    <?php
                    mysqli_data_seek($mahasiswa_result, 0);
                    while ($m = mysqli_fetch_assoc($mahasiswa_result)):
                    ?>
                        <option value="<?= $m['id_mahasiswa'] ?>" <?= ($edit && $data_edit['id_mahasiswa'] == $m['id_mahasiswa']) ? 'selected' : '' ?>>
                            <?= $m['nama_mahasiswa'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Jenis Pembayaran</td>
            <td>
                <select name="jenis_pembayaran" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="SPP" <?= ($edit && $data_edit['jenis_pembayaran'] == 'SPP') ? 'selected' : '' ?>>SPP</option>
                    <option value="UKT" <?= ($edit && $data_edit['jenis_pembayaran'] == 'UKT') ? 'selected' : '' ?>>UKT</option>
                    <option value="Lain" <?= ($edit && $data_edit['jenis_pembayaran'] == 'Lain') ? 'selected' : '' ?>>Lain</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Jumlah</td>
            <td><input type="number" step="0.01" name="jumlah" required value="<?= $data_edit['jumlah'] ?? '' ?>"></td>
        </tr>
        <tr>
            <td>Tanggal Bayar</td>
            <td><input type="date" name="tgl_bayar" required value="<?= $data_edit['tgl_bayar'] ?? '' ?>"></td>
        </tr>
        <tr>
            <td>Status</td>
            <td>
                <select name="status" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="Lunas" <?= ($edit && $data_edit['status'] == 'Lunas') ? 'selected' : '' ?>>Lunas</option>
                    <option value="Belum Lunas" <?= ($edit && $data_edit['status'] == 'Belum Lunas') ? 'selected' : '' ?>>Belum Lunas</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <button type="submit" name="<?= $edit ? 'update' : 'simpan' ?>">
                    <?= $edit ? 'Update' : 'Simpan' ?>
                </button>
                <?php if ($edit): ?>
                    <a href="pembayaran.php">Batal</a>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</form>

<br>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Nama Mahasiswa</th>
        <th>Jenis</th>
        <th>Jumlah</th>
        <th>Tanggal</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php
    $q = mysqli_query($kon, "SELECT p.*, m.nama_mahasiswa 
                             FROM pembayaran p
                             LEFT JOIN mahasiswa m ON p.id_mahasiswa = m.id_mahasiswa
                             ORDER BY p.id_pembayaran ASC");
    while ($row = mysqli_fetch_assoc($q)) {
        echo "<tr>
            <td>{$row['id_pembayaran']}</td>
            <td>{$row['nama_mahasiswa']}</td>
            <td>{$row['jenis_pembayaran']}</td>
            <td>Rp " . number_format($row['jumlah'], 2, ',', '.') . "</td>
            <td>{$row['tgl_bayar']}</td>
            <td>{$row['status']}</td>
            <td>
                <a href='?edit={$row['id_pembayaran']}'>Edit</a> |
                <a href='?hapus={$row['id_pembayaran']}' onclick=\"return confirm('Yakin ingin hapus?')\">Hapus</a>
            </td>
        </tr>";
    }
    ?>
</table>
</body>
</html>
