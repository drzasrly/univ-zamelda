<?php
include 'config/database.php';

$prodi_result = mysqli_query($kon, "SELECT id_prodi, nama_prodi FROM program_studi");

if (isset($_POST['simpan'])) {
    $cek = mysqli_query($kon, "SELECT MAX(id_matkul) as max_id FROM mata_kuliah");
    $data = mysqli_fetch_assoc($cek);
    $id_baru = $data['max_id'] + 1;

    $kode = $_POST['kode_matkul'];
    $nama = $_POST['nama_matkul'];
    $sks = $_POST['sks'];
    $semester = $_POST['semester'];
    $id_prodi = $_POST['id_prodi'];

    mysqli_query($kon, "INSERT INTO mata_kuliah 
        (id_matkul, kode_matkul, nama_matkul, sks, semester, id_prodi)
        VALUES 
        ('$id_baru', '$kode', '$nama', '$sks', '$semester', '$id_prodi')");

    header("Location: mata_kuliah.php");
}

if (isset($_POST['update'])) {
    $id = $_POST['id_matkul'];
    $kode = $_POST['kode_matkul'];
    $nama = $_POST['nama_matkul'];
    $sks = $_POST['sks'];
    $semester = $_POST['semester'];
    $id_prodi = $_POST['id_prodi'];

    mysqli_query($kon, "UPDATE mata_kuliah SET 
        kode_matkul='$kode', nama_matkul='$nama', sks='$sks',
        semester='$semester', id_prodi='$id_prodi'
        WHERE id_matkul='$id'");

    header("Location: mata_kuliah.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($kon, "DELETE FROM mata_kuliah WHERE id_matkul='$id'");
    header("Location: mata_kuliah.php");
}

$edit = false;
$data_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $result = mysqli_query($kon, "SELECT * FROM mata_kuliah WHERE id_matkul='$id'");
    $data_edit = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Mata Kuliah</title>
</head>
<body>
<h2 align="center">Data Mata Kuliah</h2>

<form method="post" action="">
    <?php if ($edit): ?>
        <input type="hidden" name="id_matkul" value="<?= $data_edit['id_matkul'] ?>">
    <?php endif; ?>
    <table align="center" cellpadding="5">
        <tr><td>Kode Matkul</td><td><input type="text" name="kode_matkul" required value="<?= $data_edit['kode_matkul'] ?? '' ?>"></td></tr>
        <tr><td>Nama Matkul</td><td><input type="text" name="nama_matkul" required value="<?= $data_edit['nama_matkul'] ?? '' ?>"></td></tr>
        <tr><td>SKS</td><td><input type="number" name="sks" required value="<?= $data_edit['sks'] ?? '' ?>"></td></tr>
        <tr>
            <td>Semester</td>
            <td>
                <select name="semester" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    for ($i = 1; $i <= 8; $i++) {
                        $selected = ($edit && $data_edit['semester'] == "Semester $i") ? "selected" : "";
                        echo "<option value='Semester $i' $selected>Semester $i</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Prodi</td>
            <td>
                <select name="id_prodi">
                    <option value="">-- Pilih Prodi --</option>
                    <?php
                    mysqli_data_seek($prodi_result, 0);
                    while ($p = mysqli_fetch_assoc($prodi_result)): ?>
                        <option value="<?= $p['id_prodi'] ?>" <?= ($edit && $data_edit['id_prodi'] == $p['id_prodi']) ? 'selected' : '' ?>>
                            <?= $p['nama_prodi'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <button type="submit" name="<?= $edit ? 'update' : 'simpan' ?>"><?= $edit ? 'Update' : 'Simpan' ?></button>
                <?php if ($edit): ?><a href="mata_kuliah.php">Batal</a><?php endif; ?>
            </td>
        </tr>
    </table>
</form>

<br>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Kode</th>
        <th>Nama</th>
        <th>SKS</th>
        <th>Semester</th>
        <th>Prodi</th>
        <th>Aksi</th>
    </tr>
    <?php
    $q = mysqli_query($kon, "SELECT mk.*, p.nama_prodi 
                             FROM mata_kuliah mk
                             LEFT JOIN program_studi p ON mk.id_prodi = p.id_prodi
                             ORDER BY mk.id_matkul ASC");
    while ($row = mysqli_fetch_assoc($q)) {
        echo "<tr>
            <td>{$row['id_matkul']}</td>
            <td>{$row['kode_matkul']}</td>
            <td>{$row['nama_matkul']}</td>
            <td>{$row['sks']}</td>
            <td>{$row['semester']}</td>
            <td>{$row['nama_prodi']}</td>
            <td>
                <a href='?edit={$row['id_matkul']}'>Edit</a> |
                <a href='?hapus={$row['id_matkul']}' onclick=\"return confirm('Yakin ingin menghapus?')\">Hapus</a>
            </td>
        </tr>";
    }
    ?>
</table>
</body>
</html>
