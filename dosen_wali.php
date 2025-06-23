<?php
include 'config/database.php';

$dosen_result = mysqli_query($kon, "SELECT id_dosen, nama_dosen FROM dosen");
$mahasiswa_result = mysqli_query($kon, "SELECT id_mahasiswa, nama_mahasiswa FROM mahasiswa");

if (isset($_POST['simpan'])) {
    $cek = mysqli_query($kon, "SELECT MAX(id_wali) as max_id FROM dosen_wali");
    $data = mysqli_fetch_assoc($cek);
    $id_baru = $data['max_id'] + 1;

    $id_dosen = $_POST['id_dosen'];
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $periode = $_POST['periode'];

    mysqli_query($kon, "INSERT INTO dosen_wali (id_wali, id_dosen, id_mahasiswa, periode)
                        VALUES ('$id_baru', '$id_dosen', '$id_mahasiswa', '$periode')");
    header("Location: dosen_wali.php");
}

if (isset($_POST['update'])) {
    $id = $_POST['id_wali'];
    $id_dosen = $_POST['id_dosen'];
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $periode = $_POST['periode'];

    mysqli_query($kon, "UPDATE dosen_wali SET id_dosen='$id_dosen', id_mahasiswa='$id_mahasiswa', periode='$periode' 
                        WHERE id_wali='$id'");
    header("Location: dosen_wali.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($kon, "DELETE FROM dosen_wali WHERE id_wali='$id'");
    header("Location: dosen_wali.php");
}

$edit = false;
$data_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $result = mysqli_query($kon, "SELECT * FROM dosen_wali WHERE id_wali='$id'");
    $data_edit = mysqli_fetch_assoc($result);
}

$tahun_sekarang = date('Y');
$periode_list = [];
for ($i = 0; $i < 10; $i++) {
    $awal = $tahun_sekarang - $i;
    $akhir = $awal + 6;
    $periode_list[] = "$awal-$akhir";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Dosen Wali</title>
</head>
<body>
<h2 align="center">Data Dosen Wali</h2>

<form method="post" action="">
    <?php if ($edit): ?>
        <input type="hidden" name="id_wali" value="<?= $data_edit['id_wali'] ?>">
    <?php endif; ?>
    <table align="center" cellpadding="5">
        <tr>
            <td>Dosen</td>
            <td>
                <select name="id_dosen" required>
                    <option value="">-- Pilih Dosen --</option>
                    <?php
                    mysqli_data_seek($dosen_result, 0);
                    while ($d = mysqli_fetch_assoc($dosen_result)):
                    ?>
                        <option value="<?= $d['id_dosen'] ?>" <?= ($edit && $data_edit['id_dosen'] == $d['id_dosen']) ? 'selected' : '' ?>>
                            <?= $d['nama_dosen'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>
        </tr>
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
            <td>Periode</td>
            <td>
                <select name="periode" id="periode" required>
                    <option value="">-- Pilih Periode --</option>
                    <?php foreach ($periode_list as $p): ?>
                        <option value="<?= $p ?>" <?= ($edit && $data_edit['periode'] == $p) ? 'selected' : '' ?>>
                            <?= $p ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>

    <tr>
        <td colspan="2" align="right">
            <button type="submit" name="<?= $edit ? 'update' : 'simpan' ?>">
                <?= $edit ? 'Update' : 'Simpan' ?>
                </button>
                <?php if ($edit): ?>
                    <a href="dosen_wali.php">Batal</a>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</form>

<br>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Dosen</th>
        <th>Mahasiswa</th>
        <th>Periode</th>
        <th>Aksi</th>
    </tr>
    <?php
    $q = mysqli_query($kon, "SELECT dw.*, d.nama_dosen, m.nama_mahasiswa
                             FROM dosen_wali dw
                             LEFT JOIN dosen d ON dw.id_dosen = d.id_dosen
                             LEFT JOIN mahasiswa m ON dw.id_mahasiswa = m.id_mahasiswa
                             ORDER BY dw.id_wali ASC");
    while ($row = mysqli_fetch_assoc($q)) {
        echo "<tr>
            <td>{$row['id_wali']}</td>
            <td>{$row['nama_dosen']}</td>
            <td>{$row['nama_mahasiswa']}</td>
            <td>{$row['periode']}</td>
            <td>
                <a href='?edit={$row['id_wali']}'>Edit</a> |
                <a href='?hapus={$row['id_wali']}' onclick=\"return confirm('Yakin?')\">Hapus</a>
            </td>
        </tr>";
    }
    ?>
</table>
</body>
</html>
