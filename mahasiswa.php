<?php
include 'config/database.php';

$prodi_result = mysqli_query($kon, "SELECT * FROM program_studi");

if (isset($_POST['simpan'])) {
    $cek = mysqli_query($kon, "SELECT MAX(id_mahasiswa) as max_id FROM mahasiswa");
    $data = mysqli_fetch_assoc($cek);
    $id_mahasiswa_baru = $data['max_id'] + 1;

    $nim = $_POST['nim'];
    $nama = $_POST['nama_mahasiswa'];
    $jk = $_POST['jenis_kelamin'];
    $tgl_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $id_prodi = $_POST['id_prodi'];
    $angkatan = $_POST['angkatan'];
    $status = $_POST['status'];

    mysqli_query($kon, "INSERT INTO mahasiswa 
        (id_mahasiswa, nim, nama_mahasiswa, jenis_kelamin, tanggal_lahir, alamat, email, no_telp, id_prodi, angkatan, status)
        VALUES 
        ('$id_mahasiswa_baru', '$nim', '$nama', '$jk', '$tgl_lahir', '$alamat', '$email', '$no_telp', '$id_prodi', '$angkatan', '$status')");

    header("Location: mahasiswa.php");
}

if (isset($_POST['update'])) {
    $id = $_POST['id_mahasiswa'];
    $nim = $_POST['nim'];
    $nama = $_POST['nama_mahasiswa'];
    $jk = $_POST['jenis_kelamin'];
    $tgl_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $id_prodi = $_POST['id_prodi'];
    $angkatan = $_POST['angkatan'];
    $status = $_POST['status'];

    mysqli_query($kon, "UPDATE mahasiswa SET 
        nim='$nim', nama_mahasiswa='$nama', jenis_kelamin='$jk', tanggal_lahir='$tgl_lahir',
        alamat='$alamat', email='$email', no_telp='$no_telp', id_prodi='$id_prodi', angkatan='$angkatan', status='$status'
        WHERE id_mahasiswa='$id'");

    header("Location: mahasiswa.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($kon, "DELETE FROM mahasiswa WHERE id_mahasiswa='$id'");
    header("Location: mahasiswa.php");
}

$edit = false;
$data_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $result = mysqli_query($kon, "SELECT * FROM mahasiswa WHERE id_mahasiswa='$id'");
    $data_edit = mysqli_fetch_assoc($result);
}

$tahun_sekarang = date('Y');
$angkatan_list = [];
for ($i = 0; $i < 10; $i++) {
    $angkatan_list[] = $tahun_sekarang - $i;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Mahasiswa</title>
</head>
<body>
    <h2 align="center">Data Mahasiswa</h2>

    <form method="post" action="">
        <?php if ($edit): ?>
            <input type="hidden" name="id_mahasiswa" value="<?= $data_edit['id_mahasiswa'] ?>">
        <?php endif; ?>
        <table align="center" cellpadding="5">
            <tr><td>NIM</td><td><input type="text" name="nim" required value="<?= $data_edit['nim'] ?? '' ?>"></td></tr>
            <tr><td>Nama</td><td><input type="text" name="nama_mahasiswa" required value="<?= $data_edit['nama_mahasiswa'] ?? '' ?>"></td></tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>
                    <select name="jenis_kelamin" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" <?= ($edit && $data_edit['jenis_kelamin'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="Perempuan" <?= ($edit && $data_edit['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </td>
            </tr>
            <tr><td>Tanggal Lahir</td><td><input type="date" name="tanggal_lahir" required value="<?= $data_edit['tanggal_lahir'] ?? '' ?>"></td></tr>
            <tr><td>Alamat</td><td><textarea name="alamat" required><?= $data_edit['alamat'] ?? '' ?></textarea></td></tr>
            <tr><td>Email</td><td><input type="email" name="email" value="<?= $data_edit['email'] ?? '' ?>"></td></tr>
            <tr><td>No. Telp</td><td><input type="text" name="no_telp" value="<?= $data_edit['no_telp'] ?? '' ?>"></td></tr>
            <tr>
                <td>Program Studi</td>
                <td>
                    <select name="id_prodi" id="id_prodi" onchange="setAngkatan()" required>
                        <option value="">-- Pilih Prodi --</option>
                        <?php
                        mysqli_data_seek($prodi_result, 0); 
                        while ($p = mysqli_fetch_assoc($prodi_result)):
                        ?>
                            <option value="<?= $p['id_prodi'] ?>" data-angkatan="<?= date('Y') ?>"
                                <?= ($edit && $data_edit['id_prodi'] == $p['id_prodi']) ? 'selected' : '' ?>>
                                <?= $p['nama_prodi'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </td>
            <tr>
                <td>Angkatan</td>
                <td>
                    <select name="angkatan" id="angkatan" required>
                        <option value="">-- Pilih Tahun Angkatan --</option>
                        <?php foreach ($angkatan_list as $tahun): ?>
                            <option value="<?= $tahun ?>" <?= ($edit && $data_edit['angkatan'] == $tahun) ? 'selected' : '' ?>>
                                <?= $tahun ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
                <td>Status</td>
                <td>
                    <select name="status" required>
                        <option value="">-- Pilih --</option>
                        <option value="Aktif" <?= ($edit && $data_edit['status'] == 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                        <option value="Lulus" <?= ($edit && $data_edit['status'] == 'Lulus') ? 'selected' : '' ?>>Lulus</option>
                        <option value="Cuti" <?= ($edit && $data_edit['status'] == 'Cuti') ? 'selected' : '' ?>>Cuti</option>
                        <option value="DO" <?= ($edit && $data_edit['status'] == 'DO') ? 'selected' : '' ?>>DO</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <button type="submit" name="<?= $edit ? 'update' : 'simpan' ?>">
                        <?= $edit ? 'Update' : 'Simpan' ?>
                    </button>
                    <?php if ($edit): ?>
                        <a href="mahasiswa.php">Batal</a>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </form>

    <br>

    <table border="1" align="center" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th><th>NIM</th><th>Nama</th><th>JK</th><th>Tgl Lahir</th><th>Alamat</th><th>Email</th>
            <th>No Telp</th><th>Program Studi</th><th>Angkatan</th><th>Status</th><th>Aksi</th>
        </tr>
        <?php
        $query = mysqli_query($kon, "SELECT m.*, p.nama_prodi 
            FROM mahasiswa m
            LEFT JOIN program_studi p ON m.id_prodi = p.id_prodi
            ORDER BY m.id_mahasiswa ASC");
        while ($row = mysqli_fetch_assoc($query)) {
            echo "<tr>
                <td>{$row['id_mahasiswa']}</td>
                <td>{$row['nim']}</td>
                <td>{$row['nama_mahasiswa']}</td>
                <td>{$row['jenis_kelamin']}</td>
                <td>{$row['tanggal_lahir']}</td>
                <td>{$row['alamat']}</td>
                <td>{$row['email']}</td>
                <td>{$row['no_telp']}</td>
                <td>{$row['nama_prodi']}</td>
                <td>{$row['angkatan']}</td>
                <td>{$row['status']}</td>
                <td>
                    <a href='?edit={$row['id_mahasiswa']}'>Edit</a> |
                    <a href='?hapus={$row['id_mahasiswa']}' onclick=\"return confirm('Yakin?')\">Hapus</a>
                </td>
            </tr>";
        }
        ?>
    </table>

    <script>
    function setAngkatan() {
        var select = document.getElementById("id_prodi");
        var angkatan = select.options[select.selectedIndex].getAttribute("data-angkatan");
        document.getElementById("angkatan").value = angkatan;
    }
    </script>
</body>
</html>
