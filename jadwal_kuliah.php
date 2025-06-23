<?php
include 'config/database.php';

$matkul_result = mysqli_query($kon, "SELECT id_matkul, nama_matkul FROM mata_kuliah");
$dosen_result = mysqli_query($kon, "SELECT id_dosen, nama_dosen FROM dosen");
$ruangan_result = mysqli_query($kon, "SELECT id_ruangan, nama_ruangan FROM ruangan");

if (isset($_POST['simpan'])) {
    $cek = mysqli_query($kon, "SELECT MAX(id_jadwal) as max_id FROM jadwal_kuliah");
    $data = mysqli_fetch_assoc($cek);
    $id_baru = $data['max_id'] + 1;

    $id_matkul = $_POST['id_matkul'];
    $id_dosen = $_POST['id_dosen'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $id_ruangan = $_POST['id_ruangan'];
    $tahun_akademik = $_POST['tahun_akademik'];

    mysqli_query($kon, "INSERT INTO jadwal_kuliah 
        (id_jadwal, id_matkul, id_dosen, hari, jam_mulai, jam_selesai, id_ruangan, tahun_akademik)
        VALUES 
        ('$id_baru', '$id_matkul', '$id_dosen', '$hari', '$jam_mulai', '$jam_selesai', '$id_ruangan', '$tahun_akademik')");

    header("Location: jadwal_kuliah.php");
}

if (isset($_POST['update'])) {
    $id = $_POST['id_jadwal'];
    $id_matkul = $_POST['id_matkul'];
    $id_dosen = $_POST['id_dosen'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $id_ruangan = $_POST['id_ruangan'];
    $tahun_akademik = $_POST['tahun_akademik'];

    mysqli_query($kon, "UPDATE jadwal_kuliah SET 
        id_matkul='$id_matkul', id_dosen='$id_dosen', hari='$hari', 
        jam_mulai='$jam_mulai', jam_selesai='$jam_selesai', 
        id_ruangan='$id_ruangan', tahun_akademik='$tahun_akademik'
        WHERE id_jadwal='$id'");

    header("Location: jadwal_kuliah.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($kon, "DELETE FROM jadwal_kuliah WHERE id_jadwal='$id'");
    header("Location: jadwal_kuliah.php");
}

$edit = false;
$data_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $result = mysqli_query($kon, "SELECT * FROM jadwal_kuliah WHERE id_jadwal='$id'");
    $data_edit = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Jadwal Kuliah</title>
</head>
<body>
<h2 align="center">Jadwal Kuliah</h2>

<form method="post" action="">
    <?php if ($edit): ?>
        <input type="hidden" name="id_jadwal" value="<?= $data_edit['id_jadwal'] ?>">
    <?php endif; ?>
    <table align="center" cellpadding="5">
        <tr>
            <td>Mata Kuliah</td>
            <td>
                <select name="id_matkul" required>
                    <option value="">-- Pilih Matkul --</option>
                    <?php while ($m = mysqli_fetch_assoc($matkul_result)): ?>
                        <option value="<?= $m['id_matkul'] ?>" <?= ($edit && $data_edit['id_matkul'] == $m['id_matkul']) ? 'selected' : '' ?>>
                            <?= $m['nama_matkul'] ?>
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
                    <?php while ($d = mysqli_fetch_assoc($dosen_result)): ?>
                        <option value="<?= $d['id_dosen'] ?>" <?= ($edit && $data_edit['id_dosen'] == $d['id_dosen']) ? 'selected' : '' ?>>
                            <?= $d['nama_dosen'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Hari</td>
            <td>
                <select name="hari" required>
                    <option value="">-- Pilih Hari --</option>
                    <?php
                    $hari_list = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                    foreach ($hari_list as $h):
                        $selected = ($edit && $data_edit['hari'] == $h) ? 'selected' : '';
                        echo "<option value='$h' $selected>$h</option>";
                    endforeach;
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Jam Mulai</td>
            <td><input type="time" name="jam_mulai" required value="<?= $data_edit['jam_mulai'] ?? '' ?>"></td>
        </tr>
        <tr>
            <td>Jam Selesai</td>
            <td><input type="time" name="jam_selesai" required value="<?= $data_edit['jam_selesai'] ?? '' ?>"></td>
        </tr>
        <tr>
            <td>Ruangan</td>
            <td>
                <select name="id_ruangan" required>
                    <option value="">-- Pilih Ruangan --</option>
                    <?php while ($r = mysqli_fetch_assoc($ruangan_result)): ?>
                        <option value="<?= $r['id_ruangan'] ?>" <?= ($edit && $data_edit['id_ruangan'] == $r['id_ruangan']) ? 'selected' : '' ?>>
                            <?= $r['nama_ruangan'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Tahun Akademik</td>
            <td>
                <select name="tahun_akademik" required>
                    <option value="">-- Pilih Tahun --</option>
                    <?php
                    $tahun_sekarang = date('Y');
                    for ($i = 0; $i < 7; $i++) {
                        $tahun = $tahun_sekarang - $i;
                        $selected = ($edit && $data_edit['tahun_akademik'] == $tahun) ? 'selected' : '';
                        echo "<option value='$tahun' $selected>$tahun</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <button type="submit" name="<?= $edit ? 'update' : 'simpan' ?>">
                    <?= $edit ? 'Update' : 'Simpan' ?>
                </button>
                <?php if ($edit): ?>
                    <a href="jadwal_kuliah.php">Batal</a>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</form>

<br>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th><th>Mata Kuliah</th><th>Dosen</th><th>Hari</th>
        <th>Jam</th><th>Ruangan</th><th>Tahun Akademik</th><th>Aksi</th>
    </tr>
    <?php
    $q = mysqli_query($kon, "SELECT j.*, mk.nama_matkul, d.nama_dosen, r.nama_ruangan
                             FROM jadwal_kuliah j
                             LEFT JOIN mata_kuliah mk ON j.id_matkul = mk.id_matkul
                             LEFT JOIN dosen d ON j.id_dosen = d.id_dosen
                             LEFT JOIN ruangan r ON j.id_ruangan = r.id_ruangan
                             ORDER BY j.id_jadwal ASC");
    while ($row = mysqli_fetch_assoc($q)) {
        echo "<tr>
            <td>{$row['id_jadwal']}</td>
            <td>{$row['nama_matkul']}</td>
            <td>{$row['nama_dosen']}</td>
            <td>{$row['hari']}</td>
            <td>{$row['jam_mulai']} - {$row['jam_selesai']}</td>
            <td>{$row['nama_ruangan']}</td>
            <td>{$row['tahun_akademik']}</td>
            <td>
                <a href='?edit={$row['id_jadwal']}'>Edit</a> |
                <a href='?hapus={$row['id_jadwal']}' onclick=\"return confirm('Yakin?')\">Hapus</a>
            </td>
        </tr>";
    }
    ?>
</table>
</body>
</html>
