<?php
include 'config/database.php';

if (isset($_POST['simpan'])) {
    $cek = mysqli_query($kon, "SELECT MAX(id_khs) as max_id FROM khs");
    $data = mysqli_fetch_assoc($cek);
    $id_khs_baru = $data['max_id'] + 1;

    $id_mahasiswa = $_POST['id_mahasiswa'];
    $semester = $_POST['semester'];
    $tahun_akademik = $_POST['tahun_akademik'];
    $IPS = $_POST['IPS'];

    mysqli_query($kon, "INSERT INTO khs (id_khs, id_mahasiswa, semester, tahun_akademik, IPS) 
                    VALUES ('$id_khs_baru', '$id_mahasiswa', '$semester', '$tahun_akademik', '$IPS')");
    header("Location: khs.php");
}
if (isset($_POST['update'])) {
    $id_khs = $_POST['id_khs'];
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $semester = $_POST['semester'];
    $tahun_akademik = $_POST['tahun_akademik'];
    $IPS = $_POST['IPS'];

    mysqli_query($kon, "UPDATE khs SET id_mahasiswa='$id_mahasiswa', semester='$semester', tahun_akademik='$tahun_akademik', IPS='$IPS' 
                        WHERE id_khs='$id_khs'");
    header("Location: khs.php");
}
if (isset($_GET['hapus'])) {
    $id_khs = $_GET['hapus'];
    mysqli_query($kon, "DELETE FROM khs WHERE id_khs='$id_khs'");
    header("Location: khs.php");
}

$edit = false;
$data_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id_khs = $_GET['edit'];
    $result = mysqli_query($kon, "SELECT * FROM khs WHERE id_khs='$id_khs'");
    $data_edit = mysqli_fetch_assoc($result);
}

$mahasiswa_result = mysqli_query($kon, "SELECT id_mahasiswa, nama_mahasiswa FROM mahasiswa");

$tahun_sekarang = date('Y');
$tahun_ajaran_list = [];
for ($i = 0; $i < 7; $i++) {
    $th1 = $tahun_sekarang - $i;
    $th2 = $th1 + 1;
    $tahun_ajaran_list[] = "$th1/$th2";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Data KHS Mahasiswa</title>
</head>
<body>
    <h2 align="center">Data KHS Mahasiswa</h2>

    <form method="post" action="">
        <?php if ($edit): ?>
            <input type="hidden" name="id_khs" value="<?= $data_edit['id_khs'] ?>">
        <?php endif; ?>
        <table align="center" cellpadding="5">
            <tr>
                <td>Nama Mahasiswa</td>
                <td>
                    <select name="id_mahasiswa" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        <?php while ($m = mysqli_fetch_assoc($mahasiswa_result)): ?>
                            <option value="<?= $m['id_mahasiswa'] ?>" 
                                <?= ($edit && $data_edit['id_mahasiswa'] == $m['id_mahasiswa']) ? 'selected' : '' ?>>
                                <?= $m['nama_mahasiswa'] ?>
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
                        <?php foreach ($tahun_ajaran_list as $tahun): ?>
                            <option value="<?= $tahun ?>" <?= ($edit && $data_edit['tahun_akademik'] == $tahun) ? 'selected' : '' ?>>
                                <?= $tahun ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>IPS</td>
                <td>
                    <input type="number" name="IPS" step="0.01" min="0" max="4.00" value="<?= $data_edit['IPS'] ?? '' ?>" required>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <button type="submit" name="<?= $edit ? 'update' : 'simpan' ?>">
                        <?= $edit ? 'Update' : 'Simpan' ?>
                    </button>
                    <?php if ($edit): ?>
                        <a href="khs.php">Batal</a>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </form>
    <br>

    <table border="1" align="center" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID KHS</th>
            <th>Nama Mahasiswa</th>
            <th>Semester</th>
            <th>Tahun Akademik</th>
            <th>IPS</th>
            <th>Aksi</th>
        </tr>
        <?php
        $query = mysqli_query($kon, "SELECT khs.*, m.nama_mahasiswa 
                                     FROM khs 
                                     JOIN mahasiswa m ON khs.id_mahasiswa = m.id_mahasiswa 
                                     ORDER BY id_khs ASC");
        while ($row = mysqli_fetch_assoc($query)) {
            echo "<tr>
                    <td>{$row['id_khs']}</td>
                    <td>{$row['nama_mahasiswa']}</td>
                    <td>{$row['semester']}</td>
                    <td>{$row['tahun_akademik']}</td>
                    <td>" . number_format($row['IPS'], 2) . "</td>
                    <td>
                        <a href='?edit={$row['id_khs']}'>Edit</a> |
                        <a href='?hapus={$row['id_khs']}' onclick=\"return confirm('Yakin ingin menghapus?')\">Hapus</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>
