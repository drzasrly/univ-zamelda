<?php
include 'config/database.php';

$mahasiswa_result = mysqli_query($kon, "SELECT id_mahasiswa, nama_mahasiswa FROM mahasiswa");

if (isset($_POST['simpan'])) {
    $cek = mysqli_query($kon, "SELECT MAX(id_registrasi) as max_id FROM registrasi");
    $data = mysqli_fetch_assoc($cek);
    $id_baru = $data['max_id'] + 1;

    $id_mahasiswa = $_POST['id_mahasiswa'];
    $tahun_akademik = $_POST['tahun_akademik'];
    $tgl_registrasi = $_POST['tgl_registrasi'];
    $status = $_POST['status_registrasi'];

    mysqli_query($kon, "INSERT INTO registrasi (id_registrasi, id_mahasiswa, tahun_akademik, tgl_registrasi, status_registrasi)
                        VALUES ('$id_baru', '$id_mahasiswa', '$tahun_akademik', '$tgl_registrasi', '$status')");
    header("Location: registrasi.php");
}

if (isset($_POST['update'])) {
    $id = $_POST['id_registrasi'];
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $tahun_akademik = $_POST['tahun_akademik'];
    $tgl_registrasi = $_POST['tgl_registrasi'];
    $status = $_POST['status_registrasi'];

    mysqli_query($kon, "UPDATE registrasi SET 
        id_mahasiswa='$id_mahasiswa', tahun_akademik='$tahun_akademik', 
        tgl_registrasi='$tgl_registrasi', status_registrasi='$status'
        WHERE id_registrasi='$id'");
    header("Location: registrasi.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($kon, "DELETE FROM registrasi WHERE id_registrasi='$id'");
    header("Location: registrasi.php");
}

$edit = false;
$data_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $result = mysqli_query($kon, "SELECT * FROM registrasi WHERE id_registrasi='$id'");
    $data_edit = mysqli_fetch_assoc($result);
}

$tahun_sekarang = date('Y');
$tahun_list = [];
for ($i = 0; $i < 7; $i++) {
    $tahun = $tahun_sekarang - $i;
    $tahun_list[] = $tahun . '/' . ($tahun + 1);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Registrasi Mahasiswa</title>
</head>
<body>
<h2 align="center">Data Registrasi Mahasiswa</h2>

<form method="post" action="">
    <?php if ($edit): ?>
        <input type="hidden" name="id_registrasi" value="<?= $data_edit['id_registrasi'] ?>">
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
            <td>Tahun Akademik</td>
            <td>
                <select name="tahun_akademik" required>
                    <option value="">-- Pilih Tahun Akademik --</option>
                    <?php foreach ($tahun_list as $tahun): ?>
                        <option value="<?= explode('/', $tahun)[0] ?>" <?= ($edit && $data_edit['tahun_akademik'] == explode('/', $tahun)[0]) ? 'selected' : '' ?>>
                            <?= $tahun ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Tanggal Registrasi</td>
            <td><input type="date" name="tgl_registrasi" required value="<?= $data_edit['tgl_registrasi'] ?? '' ?>"></td>
        </tr>
        <tr>
            <td>Status</td>
            <td>
                <select name="status_registrasi" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="Terdaftar" <?= ($edit && $data_edit['status_registrasi'] == 'Terdaftar') ? 'selected' : '' ?>>Terdaftar</option>
                    <option value="Tidak Terdaftar" <?= ($edit && $data_edit['status_registrasi'] == 'Tidak Terdaftar') ? 'selected' : '' ?>>Tidak Terdaftar</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <button type="submit" name="<?= $edit ? 'update' : 'simpan' ?>"><?= $edit ? 'Update' : 'Simpan' ?></button>
                <?php if ($edit): ?><a href="registrasi.php">Batal</a><?php endif; ?>
            </td>
        </tr>
    </table>
</form>

<br>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Nama Mahasiswa</th>
        <th>Tahun Akademik</th>
        <th>Tanggal Registrasi</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php
    $q = mysqli_query($kon, "SELECT r.*, m.nama_mahasiswa 
                             FROM registrasi r
                             LEFT JOIN mahasiswa m ON r.id_mahasiswa = m.id_mahasiswa
                             ORDER BY r.id_registrasi ASC");
    while ($row = mysqli_fetch_assoc($q)) {
        echo "<tr>
            <td>{$row['id_registrasi']}</td>
            <td>{$row['nama_mahasiswa']}</td>
            <td>{$row['tahun_akademik']}/" . ($row['tahun_akademik'] + 1) . "</td>
            <td>{$row['tgl_registrasi']}</td>
            <td>{$row['status_registrasi']}</td>
            <td>
                <a href='?edit={$row['id_registrasi']}'>Edit</a> |
                <a href='?hapus={$row['id_registrasi']}' onclick=\"return confirm('Yakin ingin hapus?')\">Hapus</a>
            </td>
        </tr>";
    }
    ?>
</table>
</body>
</html>
