<?php
include 'config/database.php';

$mahasiswa_result = mysqli_query($kon, "SELECT id_mahasiswa, nama_mahasiswa FROM mahasiswa");
$matkul_result = mysqli_query($kon, "SELECT id_matkul, nama_matkul FROM mata_kuliah");

if (isset($_POST['simpan'])) {
    $cek = mysqli_query($kon, "SELECT MAX(id_krs) as max_id FROM krs");
    $data = mysqli_fetch_assoc($cek);
    $id_baru = $data['max_id'] + 1;

    $id_mahasiswa = $_POST['id_mahasiswa'];
    $id_matkul = $_POST['id_matkul'];
    $semester = $_POST['semester'];
    $tahun_akademik = $_POST['tahun_akademik'];
    $status = $_POST['status_pengambilan'];

    mysqli_query($kon, "INSERT INTO krs (id_krs, id_mahasiswa, id_matkul, semester, tahun_akademik, status_pengambilan)
                        VALUES ('$id_baru', '$id_mahasiswa', '$id_matkul', '$semester', '$tahun_akademik', '$status')");

    header("Location: krs.php");
}

if (isset($_POST['update'])) {
    $id = $_POST['id_krs'];
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $id_matkul = $_POST['id_matkul'];
    $semester = $_POST['semester'];
    $tahun_akademik = $_POST['tahun_akademik'];
    $status = $_POST['status_pengambilan'];

    mysqli_query($kon, "UPDATE krs SET 
        id_mahasiswa='$id_mahasiswa', id_matkul='$id_matkul', semester='$semester',
        tahun_akademik='$tahun_akademik', status_pengambilan='$status' WHERE id_krs='$id'");

    header("Location: krs.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($kon, "DELETE FROM krs WHERE id_krs='$id'");
    header("Location: krs.php");
}

$edit = false;
$data_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $result = mysqli_query($kon, "SELECT * FROM krs WHERE id_krs='$id'");
    $data_edit = mysqli_fetch_assoc($result);
}

$tahun_sekarang = date('Y');
$tahun_akademik_list = [];
for ($i = 0; $i < 7; $i++) {
    $awal = $tahun_sekarang - $i;
    $akhir = $awal + 1;
    $tahun_akademik_list[] = "$awal/$akhir";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data KRS</title>
</head>
<body>
<h2 align="center">Data KRS Mahasiswa</h2>

<form method="post" action="">
    <?php if ($edit): ?>
        <input type="hidden" name="id_krs" value="<?= $data_edit['id_krs'] ?>">
    <?php endif; ?>
    <table align="center" cellpadding="5">
        <tr>
            <td>Mahasiswa</td>
            <td>
                <select name="id_mahasiswa" required>
                    <option value="">-- Pilih Mahasiswa --</option>
                    <?php mysqli_data_seek($mahasiswa_result, 0);
                    while ($m = mysqli_fetch_assoc($mahasiswa_result)): ?>
                        <option value="<?= $m['id_mahasiswa'] ?>" <?= ($edit && $data_edit['id_mahasiswa'] == $m['id_mahasiswa']) ? 'selected' : '' ?>>
                            <?= $m['nama_mahasiswa'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Mata Kuliah</td>
            <td>
                <select name="id_matkul" required>
                    <option value="">-- Pilih Mata Kuliah --</option>
                    <?php mysqli_data_seek($matkul_result, 0);
                    while ($m = mysqli_fetch_assoc($matkul_result)): ?>
                        <option value="<?= $m['id_matkul'] ?>" <?= ($edit && $data_edit['id_matkul'] == $m['id_matkul']) ? 'selected' : '' ?>>
                            <?= $m['nama_matkul'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Semester</td>
            <td>
                <select name="semester" required>
                    <option value="">-- Pilih Semester --</option>
                    <option value="Ganjil" <?= ($edit && $data_edit['semester'] == 'Ganjil') ? 'selected' : '' ?>>Ganjil</option>
                    <option value="Genap" <?= ($edit && $data_edit['semester'] == 'Genap') ? 'selected' : '' ?>>Genap</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Tahun Akademik</td>
            <td>
                <select name="tahun_akademik" required>
                    <option value="">-- Pilih Tahun Akademik --</option>
                    <?php foreach ($tahun_akademik_list as $ta): ?>
                        <option value="<?= $ta ?>" <?= ($edit && $data_edit['tahun_akademik'] == $ta) ? 'selected' : '' ?>>
                            <?= $ta ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Status</td>
            <td>
                <select name="status_pengambilan" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="Ambil" <?= ($edit && $data_edit['status_pengambilan'] == 'Ambil') ? 'selected' : '' ?>>Ambil</option>
                    <option value="Drop" <?= ($edit && $data_edit['status_pengambilan'] == 'Drop') ? 'selected' : '' ?>>Drop</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <button type="submit" name="<?= $edit ? 'update' : 'simpan' ?>">
                    <?= $edit ? 'Update' : 'Simpan' ?>
                </button>
                <?php if ($edit): ?>
                    <a href="krs.php">Batal</a>
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
        <th>Mata Kuliah</th>
        <th>Semester</th>
        <th>Tahun Akademik</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php
    $q = mysqli_query($kon, "SELECT k.*, m.nama_mahasiswa, mk.nama_matkul 
                             FROM krs k
                             LEFT JOIN mahasiswa m ON k.id_mahasiswa = m.id_mahasiswa
                             LEFT JOIN mata_kuliah mk ON k.id_matkul = mk.id_matkul
                             ORDER BY k.id_krs ASC");
    while ($row = mysqli_fetch_assoc($q)) {
        echo "<tr>
            <td>{$row['id_krs']}</td>
            <td>{$row['nama_mahasiswa']}</td>
            <td>{$row['nama_matkul']}</td>
            <td>{$row['semester']}</td>
            <td>{$row['tahun_akademik']}</td>
            <td>{$row['status_pengambilan']}</td>
            <td>
                <a href='?edit={$row['id_krs']}'>Edit</a> |
                <a href='?hapus={$row['id_krs']}' onclick=\"return confirm('Yakin?')\">Hapus</a>
            </td>
        </tr>";
    }
    ?>
</table>
</body>
</html>
