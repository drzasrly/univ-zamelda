<?php
include 'config/database.php';

$dosen_result = mysqli_query($kon, "SELECT nama_dosen FROM dosen");

if (isset($_POST['simpan'])) {
    $cek = mysqli_query($kon, "SELECT MAX(id_fakultas) as max_id FROM fakultas");
    $data = mysqli_fetch_assoc($cek);
    $id_baru = $data['max_id'] + 1;

    $nama_fakultas = $_POST['nama_fakultas'];
    $nama_dekan = $_POST['nama_dekan'];

    mysqli_query($kon, "INSERT INTO fakultas (id_fakultas, nama_fakultas, nama_dekan)
                        VALUES ('$id_baru', '$nama_fakultas', '$nama_dekan')");
    header("Location: fakultas.php");
}

if (isset($_POST['update'])) {
    $id = $_POST['id_fakultas'];
    $nama_fakultas = $_POST['nama_fakultas'];
    $nama_dekan = $_POST['nama_dekan'];

    mysqli_query($kon, "UPDATE fakultas SET nama_fakultas='$nama_fakultas', nama_dekan='$nama_dekan' 
                        WHERE id_fakultas='$id'");
    header("Location: fakultas.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($kon, "DELETE FROM fakultas WHERE id_fakultas='$id'");
    header("Location: fakultas.php");
}

$edit = false;
$data_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $result = mysqli_query($kon, "SELECT * FROM fakultas WHERE id_fakultas='$id'");
    $data_edit = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Fakultas</title>
</head>
<body>
<h2 align="center">Data Fakultas</h2>

<form method="post" action="">
    <?php if ($edit): ?>
        <input type="hidden" name="id_fakultas" value="<?= $data_edit['id_fakultas'] ?>">
    <?php endif; ?>
    <table align="center" cellpadding="5">
        <tr>
            <td>Nama Fakultas</td>
            <td><input type="text" name="nama_fakultas" required value="<?= $data_edit['nama_fakultas'] ?? '' ?>"></td>
        </tr>
        <tr>
            <td>Nama Dekan</td>
            <td>
                <select name="nama_dekan" required>
                    <option value="">-- Pilih Dosen sebagai Dekan --</option>
                    <?php
                    mysqli_data_seek($dosen_result, 0);
                    while ($d = mysqli_fetch_assoc($dosen_result)):
                        $selected = ($edit && $data_edit['nama_dekan'] == $d['nama_dosen']) ? 'selected' : '';
                    ?>
                        <option value="<?= $d['nama_dosen'] ?>" <?= $selected ?>>
                            <?= $d['nama_dosen'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <button type="submit" name="<?= $edit ? 'update' : 'simpan' ?>">
                    <?= $edit ? 'Update' : 'Simpan' ?>
                </button>
                <?php if ($edit): ?>
                    <a href="fakultas.php">Batal</a>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</form>

<br>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Nama Fakultas</th>
        <th>Nama Dekan</th>
        <th>Aksi</th>
    </tr>
    <?php
    $query = mysqli_query($kon, "SELECT * FROM fakultas ORDER BY id_fakultas ASC");
    while ($row = mysqli_fetch_assoc($query)) {
        echo "<tr>
            <td>{$row['id_fakultas']}</td>
            <td>{$row['nama_fakultas']}</td>
            <td>{$row['nama_dekan']}</td>
            <td>
                <a href='?edit={$row['id_fakultas']}'>Edit</a> |
                <a href='?hapus={$row['id_fakultas']}' onclick=\"return confirm('Yakin ingin menghapus?')\">Hapus</a>
            </td>
        </tr>";
    }
    ?>
</table>
</body>
</html>
