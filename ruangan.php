<?php
include 'config/database.php';

if (isset($_POST['simpan'])) {
    $cek = mysqli_query($kon, "SELECT MAX(id_ruangan) as max_id FROM ruangan");
    $data = mysqli_fetch_assoc($cek);
    $id_baru = $data['max_id'] + 1;

    $nama_ruangan = $_POST['nama_ruangan'];
    $gedung = $_POST['gedung'];
    $kapasitas = $_POST['kapasitas'];

    mysqli_query($kon, "INSERT INTO ruangan (id_ruangan, nama_ruangan, gedung, kapasitas)
                        VALUES ('$id_baru', '$nama_ruangan', '$gedung', '$kapasitas')");
    header("Location: ruangan.php");
}

if (isset($_POST['update'])) {
    $id = $_POST['id_ruangan'];
    $nama_ruangan = $_POST['nama_ruangan'];
    $gedung = $_POST['gedung'];
    $kapasitas = $_POST['kapasitas'];

    mysqli_query($kon, "UPDATE ruangan SET 
        nama_ruangan='$nama_ruangan', gedung='$gedung', kapasitas='$kapasitas'
        WHERE id_ruangan='$id'");
    header("Location: ruangan.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($kon, "DELETE FROM ruangan WHERE id_ruangan='$id'");
    header("Location: ruangan.php");
}

$edit = false;
$data_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $result = mysqli_query($kon, "SELECT * FROM ruangan WHERE id_ruangan='$id'");
    $data_edit = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Ruangan</title>
</head>
<body>
<h2 align="center">Data Ruangan</h2>

<form method="post" action="">
    <?php if ($edit): ?>
        <input type="hidden" name="id_ruangan" value="<?= $data_edit['id_ruangan'] ?>">
    <?php endif; ?>
    <table align="center" cellpadding="5">
        <tr>
            <td>Nama Ruangan</td>
            <td><input type="text" name="nama_ruangan" required value="<?= $data_edit['nama_ruangan'] ?? '' ?>"></td>
        </tr>
        <tr>
            <td>Gedung</td>
            <td>
                <select name="gedung" required>
                    <option value="">-- Pilih Gedung --</option>
                    <?php
                    $gedung_list = ['Gedung A', 'Gedung B', 'Gedung C', 'Gedung D'];
                    foreach ($gedung_list as $gedung) {
                        $selected = ($edit && $data_edit['gedung'] == $gedung) ? 'selected' : '';
                        echo "<option value=\"$gedung\" $selected>$gedung</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Kapasitas</td>
            <td><input type="number" name="kapasitas" value="<?= $data_edit['kapasitas'] ?? '' ?>"></td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <button type="submit" name="<?= $edit ? 'update' : 'simpan' ?>">
                    <?= $edit ? 'Update' : 'Simpan' ?>
                </button>
                <?php if ($edit): ?>
                    <a href="ruangan.php">Batal</a>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</form>

<br>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Nama Ruangan</th>
        <th>Gedung</th>
        <th>Kapasitas</th>
        <th>Aksi</th>
    </tr>
    <?php
    $q = mysqli_query($kon, "SELECT * FROM ruangan ORDER BY id_ruangan ASC");
    while ($row = mysqli_fetch_assoc($q)) {
        echo "<tr>
            <td>{$row['id_ruangan']}</td>
            <td>{$row['nama_ruangan']}</td>
            <td>{$row['gedung']}</td>
            <td>{$row['kapasitas']}</td>
            <td>
                <a href='?edit={$row['id_ruangan']}'>Edit</a> |
                <a href='?hapus={$row['id_ruangan']}' onclick=\"return confirm('Yakin ingin hapus?')\">Hapus</a>
            </td>
        </tr>";
    }
    ?>
</table>
</body>
</html>
