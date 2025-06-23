<?php
include 'config/database.php';

$mahasiswa_result = mysqli_query($kon, "SELECT id_mahasiswa, nama_mahasiswa FROM mahasiswa");
$dosen_result = mysqli_query($kon, "SELECT id_dosen, nama_dosen FROM dosen");

if (isset($_POST['simpan'])) {
    $cek = mysqli_query($kon, "SELECT MAX(id_bimbingan) as max_id FROM bimbingan_akademik");
    $data = mysqli_fetch_assoc($cek);
    $id_baru = $data['max_id'] + 1;

    $id_mahasiswa = $_POST['id_mahasiswa'];
    $id_dosen = $_POST['id_dosen'];
    $tanggal = $_POST['tanggal'];
    $catatan = $_POST['catatan'];

    mysqli_query($kon, "INSERT INTO bimbingan_akademik (id_bimbingan, id_mahasiswa, id_dosen, tanggal, catatan) 
                        VALUES ('$id_baru', '$id_mahasiswa', '$id_dosen', '$tanggal', '$catatan')");
    header("Location: bimbingan_akademik.php");
}

if (isset($_POST['update'])) {
    $id = $_POST['id_bimbingan'];
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $id_dosen = $_POST['id_dosen'];
    $tanggal = $_POST['tanggal'];
    $catatan = $_POST['catatan'];

    mysqli_query($kon, "UPDATE bimbingan_akademik SET 
        id_mahasiswa='$id_mahasiswa', id_dosen='$id_dosen', tanggal='$tanggal', catatan='$catatan' 
        WHERE id_bimbingan='$id'");
    header("Location: bimbingan_akademik.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($kon, "DELETE FROM bimbingan_akademik WHERE id_bimbingan='$id'");
    header("Location: bimbingan_akademik.php");
}

$edit = false;
$data_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $result = mysqli_query($kon, "SELECT * FROM bimbingan_akademik WHERE id_bimbingan='$id'");
    $data_edit = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head><title>Data Bimbingan Akademik</title></head>
<body>
<h2 align="center">Data Bimbingan Akademik</h2>

<form method="post" action="">
    <?php if ($edit): ?>
        <input type="hidden" name="id_bimbingan" value="<?= $data_edit['id_bimbingan'] ?>">
    <?php endif; ?>
    <table align="center" cellpadding="5">
        <tr>
            <td>Mahasiswa</td>
            <td>
                <select name="id_mahasiswa" required>
                    <option value="">-- Pilih Mahasiswa --</option>
                    <?php
                    mysqli_data_seek($mahasiswa_result, 0);
                    while ($m = mysqli_fetch_assoc($mahasiswa_result)): ?>
                        <option value="<?= $m['id_mahasiswa'] ?>" <?= ($edit && $data_edit['id_mahasiswa'] == $m['id_mahasiswa']) ? 'selected' : '' ?>>
                            <?= $m['nama_mahasiswa'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Dosen</td>
            <td>
                <select name="id_dosen" required>
                    <option value="">-- Pilih Dosen --</option>
                    <?php
                    mysqli_data_seek($dosen_result, 0);
                    while ($d = mysqli_fetch_assoc($dosen_result)): ?>
                        <option value="<?= $d['id_dosen'] ?>" <?= ($edit && $data_edit['id_dosen'] == $d['id_dosen']) ? 'selected' : '' ?>>
                            <?= $d['nama_dosen'] ?>
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
            <td>Catatan</td>
            <td><textarea name="catatan" required><?= $data_edit['catatan'] ?? '' ?></textarea></td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <button type="submit" name="<?= $edit ? 'update' : 'simpan' ?>"><?= $edit ? 'Update' : 'Simpan' ?></button>
                <?php if ($edit): ?><a href="bimbingan.php">Batal</a><?php endif; ?>
            </td>
        </tr>
    </table>
</form>

<br>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th><th>Nama Mahasiswa</th><th>Dosen</th><th>Tanggal</th><th>Catatan</th><th>Aksi</th>
    </tr>
    <?php
    $q = mysqli_query($kon, "SELECT b.*, m.nama_mahasiswa, d.nama_dosen
                             FROM bimbingan_akademik b
                             LEFT JOIN mahasiswa m ON b.id_mahasiswa = m.id_mahasiswa
                             LEFT JOIN dosen d ON b.id_dosen = d.id_dosen
                             ORDER BY b.id_bimbingan ASC");
    while ($row = mysqli_fetch_assoc($q)) {
        echo "<tr>
            <td>{$row['id_bimbingan']}</td>
            <td>{$row['nama_mahasiswa']}</td>
            <td>{$row['nama_dosen']}</td>
            <td>{$row['tanggal']}</td>
            <td>{$row['catatan']}</td>
            <td>
                <a href='?edit={$row['id_bimbingan']}'>Edit</a> |
                <a href='?hapus={$row['id_bimbingan']}' onclick=\"return confirm('Yakin?')\">Hapus</a>
            </td>
        </tr>";
    }
    ?>
</table>
</body>
</html>
