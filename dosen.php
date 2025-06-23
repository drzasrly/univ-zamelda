<?php
include 'config/database.php';

$fakultas_result = mysqli_query($kon, "SELECT * FROM fakultas");

function getNamaDosenByNIDN($kon, $nidn) {
    $result = mysqli_query($kon, "SELECT nama_dosen FROM dosen WHERE NIDN='$nidn'");
    $data = mysqli_fetch_assoc($result);
    return $data['nama_dosen'] ?? '';
}

if (isset($_POST['simpan'])) {
    $cek = mysqli_query($kon, "SELECT MAX(id_dosen) as max_id FROM dosen");
    $data = mysqli_fetch_assoc($cek);
    $id_dosen_baru = $data['max_id'] + 1;

    $nidn = $_POST['nidn'];
    $nama = $_POST['nama_dosen'];
    $jk = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $id_fakultas = $_POST['id_fakultas'];
    $jabatan = $_POST['jabatan'];
    $status = $_POST['status'];

    mysqli_query($kon, "INSERT INTO dosen 
        (id_dosen, NIDN, nama_dosen, jenis_kelamin, alamat, email, no_telp, id_fakultas, jabatan, status)
        VALUES 
        ('$id_dosen_baru', '$nidn', '$nama', '$jk', '$alamat', '$email', '$no_telp', '$id_fakultas', '$jabatan', '$status')");

    header("Location: dosen.php");
}

if (isset($_POST['update'])) {
    $id = $_POST['id_dosen'];
    $nidn = $_POST['nidn'];
    $nama = $_POST['nama_dosen'];
    $jk = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $id_fakultas = $_POST['id_fakultas'];
    $jabatan = $_POST['jabatan'];
    $status = $_POST['status'];

    mysqli_query($kon, "UPDATE dosen SET 
        NIDN='$nidn', nama_dosen='$nama', jenis_kelamin='$jk', alamat='$alamat', email='$email',
        no_telp='$no_telp', id_fakultas='$id_fakultas', jabatan='$jabatan', status='$status'
        WHERE id_dosen='$id'");

    header("Location: dosen.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($kon, "DELETE FROM dosen WHERE id_dosen='$id'");
    header("Location: dosen.php");
}

$edit = false;
$data_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $result = mysqli_query($kon, "SELECT * FROM dosen WHERE id_dosen='$id'");
    $data_edit = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Dosen</title>
</head>
<body>
    <h2 align="center">Data Dosen</h2>

    <form method="post" action="">
        <?php if ($edit): ?>
            <input type="hidden" name="id_dosen" value="<?= $data_edit['id_dosen'] ?>">
        <?php endif; ?>
        <table align="center" cellpadding="5">
             <tr>
                <td>NIDN</td>
                <td>
                    <input type="text" name="nidn" required value="<?= $data_edit['NIDN'] ?? '' ?>" 
                    onblur="autofillNama(this.value)">
                </td>
            </tr>
            <tr>
                <td>Nama Dosen</td>
                <td><input type="text" name="nama_dosen" id="nama_dosen" required value="<?= $data_edit['nama_dosen'] ?? '' ?>"></td>
            </tr>
            <tr>
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
            <tr><td>Alamat</td><td><textarea name="alamat" required><?= $data_edit['alamat'] ?? '' ?></textarea></td></tr>
            <tr><td>Email</td><td><input type="email" name="email" value="<?= $data_edit['email'] ?? '' ?>"></td></tr>
            <tr><td>No. Telp</td><td><input type="text" name="no_telp" value="<?= $data_edit['no_telp'] ?? '' ?>"></td></tr>
            <tr>
                <td>Fakultas</td>
                <td>
                    <select name="id_fakultas">
                        <option value="">-- Pilih Fakultas --</option>
                        <?php
                        mysqli_data_seek($fakultas_result, 0);
                        while ($f = mysqli_fetch_assoc($fakultas_result)): ?>
                            <option value="<?= $f['id_fakultas'] ?>" <?= ($edit && $data_edit['id_fakultas'] == $f['id_fakultas']) ? 'selected' : '' ?>>
                                <?= $f['nama_fakultas'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </td>
            </tr>
            <tr><td>Jabatan</td><td><input type="text" name="jabatan" value="<?= $data_edit['jabatan'] ?? '' ?>"></td></tr>
            <tr>
                <td>Status</td>
                <td>
                    <select name="status" required>
                        <option value="">-- Pilih --</option>
                        <option value="Aktif" <?= ($edit && $data_edit['status'] == 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                        <option value="Tidak Aktif" <?= ($edit && $data_edit['status'] == 'Tidak Aktif') ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <button type="submit" name="<?= $edit ? 'update' : 'simpan' ?>">
                        <?= $edit ? 'Update' : 'Simpan' ?>
                    </button>
                    <?php if ($edit): ?>
                        <a href="dosen.php">Batal</a>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </form>

    <br>

    <table border="1" align="center" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>NIDN</th>
            <th>Nama</th>
            <th>JK</th>
            <th>Alamat</th>
            <th>Email</th>
            <th>No Telp</th>
            <th>Fakultas</th>
            <th>Jabatan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php
        $query = mysqli_query($kon, "SELECT d.*, f.nama_fakultas 
            FROM dosen d
            LEFT JOIN fakultas f ON d.id_fakultas = f.id_fakultas
            ORDER BY d.id_dosen ASC");
        while ($row = mysqli_fetch_assoc($query)) {
            echo "<tr>
                <td>{$row['id_dosen']}</td>
                <td>{$row['NIDN']}</td>
                <td>{$row['nama_dosen']}</td>
                <td>{$row['jenis_kelamin']}</td>
                <td>{$row['alamat']}</td>
                <td>{$row['email']}</td>
                <td>{$row['no_telp']}</td>
                <td>{$row['nama_fakultas']}</td>
                <td>{$row['jabatan']}</td>
                <td>{$row['status']}</td>
                <td>
                    <a href='?edit={$row['id_dosen']}'>Edit</a> |
                    <a href='?hapus={$row['id_dosen']}' onclick=\"return confirm('Yakin?')\">Hapus</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</body>
</html>
