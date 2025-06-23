<?php
include 'config/database.php';

$fakultas_result = mysqli_query($kon, "SELECT * FROM fakultas");

if (isset($_POST['simpan'])) {
    $cek = mysqli_query($kon, "SELECT MAX(id_prodi) as max_id FROM program_studi");
    $data = mysqli_fetch_assoc($cek);
    $id_baru = $data['max_id'] + 1;

    $nama_prodi = $_POST['nama_prodi'];
    $jenjang = $_POST['jenjang'];
    $id_fakultas = $_POST['id_fakultas'];

    mysqli_query($kon, "INSERT INTO program_studi (id_prodi, nama_prodi, jenjang, id_fakultas)
                        VALUES ('$id_baru', '$nama_prodi', '$jenjang', '$id_fakultas')");
    header("Location: program_studi.php");
}

if (isset($_POST['update'])) {
    $id = $_POST['id_prodi'];
    $nama_prodi = $_POST['nama_prodi'];
    $jenjang = $_POST['jenjang'];
    $id_fakultas = $_POST['id_fakultas'];

    mysqli_query($kon, "UPDATE program_studi SET 
        nama_prodi='$nama_prodi', jenjang='$jenjang', id_fakultas='$id_fakultas'
        WHERE id_prodi='$id'");
    header("Location: program_studi.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($kon, "DELETE FROM program_studi WHERE id_prodi='$id'");
    header("Location: program_studi.php");
}

$edit = false;
$data_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $result = mysqli_query($kon, "SELECT * FROM program_studi WHERE id_prodi='$id'");
    $data_edit = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Program Studi</title>
</head>
<body>
<h2 align="center">Data Program Studi</h2>

<form method="post" action="">
    <?php if ($edit): ?>
        <input type="hidden" name="id_prodi" value="<?= $data_edit['id_prodi'] ?>">
    <?php endif; ?>
    <table align="center" cellpadding="5">
        <tr>
            <td>Nama Prodi</td>
            <td><input type="text" name="nama_prodi" required value="<?= $data_edit['nama_prodi'] ?? '' ?>"></td>
        </tr>
        <tr>
            <td>Jenjang</td>
            <td>
                <select name="jenjang" required>
                    <option value="">-- Pilih Jenjang --</option>
                    <option value="D3" <?= ($edit && $data_edit['jenjang'] == 'D3') ? 'selected' : '' ?>>D3</option>
                    <option value="S1" <?= ($edit && $data_edit['jenjang'] == 'S1') ? 'selected' : '' ?>>S1</option>
                    <option value="S2" <?= ($edit && $data_edit['jenjang'] == 'S2') ? 'selected' : '' ?>>S2</option>
                    <option value="S3" <?= ($edit && $data_edit['jenjang'] == 'S3') ? 'selected' : '' ?>>S3</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Fakultas</td>
            <td>
                <select name="id_fakultas" required>
                    <option value="">-- Pilih Fakultas --</option>
                    <?php
                    mysqli_data_seek($fakultas_result, 0);
                    while ($f = mysqli_fetch_assoc($fakultas_result)):
                    ?>
                        <option value="<?= $f['id_fakultas'] ?>" <?= ($edit && $data_edit['id_fakultas'] == $f['id_fakultas']) ? 'selected' : '' ?>>
                            <?= $f['nama_fakultas'] ?>
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
                    <a href="program_studi.php">Batal</a>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</form>

<br>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Nama Prodi</th>
        <th>Jenjang</th>
        <th>Fakultas</th>
        <th>Aksi</th>
    </tr>
    <?php
    $q = mysqli_query($kon, "SELECT ps.*, f.nama_fakultas 
                             FROM program_studi ps
                             LEFT JOIN fakultas f ON ps.id_fakultas = f.id_fakultas
                             ORDER BY ps.id_prodi ASC");
    while ($row = mysqli_fetch_assoc($q)) {
        echo "<tr>
            <td>{$row['id_prodi']}</td>
            <td>{$row['nama_prodi']}</td>
            <td>{$row['jenjang']}</td>
            <td>{$row['nama_fakultas']}</td>
            <td>
                <a href='?edit={$row['id_prodi']}'>Edit</a> |
                <a href='?hapus={$row['id_prodi']}' onclick=\"return confirm('Yakin ingin hapus?')\">Hapus</a>
            </td>
        </tr>";
    }
    ?>
</table>
</body>
</html>
