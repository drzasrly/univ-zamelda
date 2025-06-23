<?php
include 'config/database.php';

// Ambil data mahasiswa & jadwal untuk dropdown
$mahasiswa_result = mysqli_query($kon, "SELECT id_mahasiswa, nama_mahasiswa FROM mahasiswa");
$jadwal_result = mysqli_query($kon, "SELECT id_jadwal, nama_matkul FROM jadwal"); // Pastikan tabel jadwal dan nama_matkul ada

// Tambah absensi
if (isset($_POST['simpan'])) {
    $cek = mysqli_query($kon, "SELECT MAX(id_absensi) as max_id FROM absensi");
    $data = mysqli_fetch_assoc($cek);
    $id_baru = $data['max_id'] + 1;

    $id_jadwal = $_POST['id_jadwal'];
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $tanggal = $_POST['tanggal'];
    $status = $_POST['status_kehadiran'];

    mysqli_query($kon, "INSERT INTO absensi (id_absensi, id_jadwal, id_mahasiswa, tanggal, status_kehadiran)
        VALUES ('$id_baru', '$id_jadwal', '$id_mahasiswa', '$tanggal', '$status')");

    header("Location: absensi.php");
}

// Update absensi
if (isset($_POST['update'])) {
    $id = $_POST['id_absensi'];
    $id_jadwal = $_POST['id_jadwal'];
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $tanggal = $_POST['tanggal'];
    $status = $_POST['status_kehadiran'];

    mysqli_query($kon, "UPDATE absensi SET id_jadwal='$id_jadwal', id_mahasiswa='$id_mahasiswa',
        tanggal='$tanggal', status_kehadiran='$status' WHERE id_absensi='$id'");

    header("Location: absensi.php");
}

// Hapus absensi
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($kon, "DELETE FROM absensi WHERE id_absensi='$id'");
    header("Location: absensi.php");
}

// Edit absensi
$edit = false;
$data_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $result = mysqli_query($kon, "SELECT * FROM absensi WHERE id_absensi='$id'");
    $data_edit = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head><title>Data Absensi Mahasiswa</title></head>
<body>
<h2 align="center">Data Absensi Mahasiswa</h2>

<form method="post" action="">
    <?php if ($edit): ?>
        <input type="hidden" name="id_absensi" value="<?= $data_edit['id_absensi'] ?>">
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
            <td>Jadwal</td>
            <td>
                <select name="id_jadwal" required>
                    <option value="">-- Pilih Jadwal --</option>
                    <?php while ($j = mysqli_fetch_assoc($jadwal_result)): ?>
                        <option value="<?= $j['id_jadwal'] ?>" <?= ($edit && $data_edit['id_jadwal'] == $j['id_jadwal']) ? 'selected' : '' ?>>
                            <?= $j['nama_matkul'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td><input type="date" name="tanggal" required value="<?= $data_edit['tanggal'] ?? '' ?>"></td>
        </tr>
        <tr>
            <td>Status Kehadiran</td>
            <td>
                <select name="status_kehadiran" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="Hadir" <?= ($edit && $data_edit['status_kehadiran'] == 'Hadir') ? 'selected' : '' ?>>Hadir</option>
                    <option value="Izin" <?= ($edit && $data_edit['status_kehadiran'] == 'Izin') ? 'selected' : '' ?>>Izin</option>
                    <option value="Sakit" <?= ($edit && $data_edit['status_kehadiran'] == 'Sakit') ? 'selected' : '' ?>>Sakit</option>
                    <option value="Alpa" <?= ($edit && $data_edit['status_kehadiran'] == 'Alpa') ? 'selected' : '' ?>>Alpa</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <button type="submit" name="<?= $edit ? 'update' : 'simpan' ?>"><?= $edit ? 'Update' : 'Simpan' ?></button>
                <?php if ($edit): ?><a href="absensi.php">Batal</a><?php endif; ?>
            </td>
        </tr>
    </table>
</form>

<br>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Nama Mahasiswa</th>
        <th>Mata Kuliah</th>
        <th>Tanggal</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php
    $q = mysqli_query($kon, "SELECT a.*, m.nama_mahasiswa, j.nama_matkul
                             FROM absensi a
                             LEFT JOIN mahasiswa m ON a.id_mahasiswa = m.id_mahasiswa
                             LEFT JOIN jadwal j ON a.id_jadwal = j.id_jadwal
                             ORDER BY a.id_absensi ASC");
    while ($row = mysqli_fetch_assoc($q)) {
        echo "<tr>
            <td>{$row['id_absensi']}</td>
            <td>{$row['nama_mahasiswa']}</td>
            <td>{$row['nama_matkul']}</td>
            <td>{$row['tanggal']}</td>
            <td>{$row['status_kehadiran']}</td>
            <td>
                <a href='?edit={$row['id_absensi']}'>Edit</a> |
                <a href='?hapus={$row['id_absensi']}' onclick=\"return confirm('Yakin?')\">Hapus</a>
            </td>
        </tr>";
    }
    ?>
</table>
</body>
</html>
