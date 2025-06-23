<?php
include 'config/database.php';

function get_mahasiswa($kon) {
    return mysqli_query($kon, "SELECT id_mahasiswa, nama_mahasiswa FROM mahasiswa");
}
function get_matkul($kon) {
    return mysqli_query($kon, "SELECT id_matkul, nama_matkul, sks FROM mata_kuliah");
}

if (isset($_POST['simpan'])) {
    $cek = mysqli_query($kon, "SELECT MAX(id_nilai) as max_id FROM nilai");
    $data = mysqli_fetch_assoc($cek);
    $id_baru = $data['max_id'] + 1;

    $id_mahasiswa = $_POST['id_mahasiswa'];
    $id_matkul = $_POST['id_matkul'];
    $sks = $_POST['sks'];
    $semester = $_POST['semester'];
    $tahun = $_POST['tahun_akademik'];

    mysqli_query($kon, "INSERT INTO nilai (id_nilai, id_mahasiswa, id_matkul, SKS, semester, tahun_akademik)
                        VALUES ('$id_baru', '$id_mahasiswa', '$id_matkul', '$sks', '$semester', '$tahun')");
    header("Location: nilai.php");
}

if (isset($_POST['update'])) {
    $id = $_POST['id_nilai'];
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $id_matkul = $_POST['id_matkul'];
    $sks = $_POST['sks'];
    $semester = $_POST['semester'];
    $tahun = $_POST['tahun_akademik'];

    mysqli_query($kon, "UPDATE nilai SET id_mahasiswa='$id_mahasiswa', id_matkul='$id_matkul', SKS='$sks',
                        semester='$semester', tahun_akademik='$tahun' WHERE id_nilai='$id'");
    header("Location: nilai.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($kon, "DELETE FROM nilai WHERE id_nilai='$id'");
    header("Location: nilai.php");
}

$edit = false;
$data_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $result = mysqli_query($kon, "SELECT * FROM nilai WHERE id_nilai='$id'");
    $data_edit = mysqli_fetch_assoc($result);
}

$tahun_sekarang = date('Y');
$tahun_list = [];
for ($i = 0; $i < 7; $i++) {
    $tahun = $tahun_sekarang - $i;
    $tahun_list[] = "$tahun/" . ($tahun + 1);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Nilai Mahasiswa</title>
</head>
<body>
    <h2 align="center">Data Nilai Mahasiswa</h2>

    <form method="post" action="">
        <?php if ($edit): ?>
            <input type="hidden" name="id_nilai" value="<?= $data_edit['id_nilai'] ?>">
        <?php endif; ?>
        <table align="center" cellpadding="5">
            <tr>
                <td>Mahasiswa</td>
                <td>
                    <select name="id_mahasiswa" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        <?php
                        $mhs_result = get_mahasiswa($kon);
                        while ($m = mysqli_fetch_assoc($mhs_result)): ?>
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
                        <?php
                        $matkul_result = get_matkul($kon);
                        while ($mat = mysqli_fetch_assoc($matkul_result)): ?>
                            <option value="<?= $mat['id_matkul'] ?>" <?= ($edit && $data_edit['id_matkul'] == $mat['id_matkul']) ? 'selected' : '' ?>>
                                <?= $mat['nama_matkul'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>SKS</td>
                <td><input type="number" name="sks" value="<?= $data_edit['SKS'] ?? '' ?>" required></td>
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
                        <?php foreach ($tahun_list as $t): ?>
                            <option value="<?= $t ?>" <?= ($edit && $data_edit['tahun_akademik'] == $t) ? 'selected' : '' ?>>
                                <?= $t ?>
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
                        <a href="nilai.php">Batal</a>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </form>

    <br>

    <table border="1" align="center" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th><th>Mahasiswa</th><th>Mata Kuliah</th><th>SKS</th><th>Semester</th><th>Tahun Akademik</th><th>Aksi</th>
        </tr>
        <?php
        $q = mysqli_query($kon, "SELECT n.*, m.nama_mahasiswa, mk.nama_matkul
                                FROM nilai n
                                LEFT JOIN mahasiswa m ON n.id_mahasiswa = m.id_mahasiswa
                                LEFT JOIN mata_kuliah mk ON n.id_matkul = mk.id_matkul
                                ORDER BY n.id_nilai ASC");
        while ($row = mysqli_fetch_assoc($q)) {
            echo "<tr>
                <td>{$row['id_nilai']}</td>
                <td>{$row['nama_mahasiswa']}</td>
                <td>{$row['nama_matkul']}</td>
                <td>{$row['SKS']}</td>
                <td>{$row['semester']}</td>
                <td>{$row['tahun_akademik']}</td>
                <td>
                    <a href='?edit={$row['id_nilai']}'>Edit</a> |
                    <a href='?hapus={$row['id_nilai']}' onclick=\"return confirm('Yakin?')\">Hapus</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</body>
</html>
