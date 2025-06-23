<!DOCTYPE html>
<html>
<head>
    <title>Sistem Akademik Zamelda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
        }
        .sidebar {
            width: 220px;
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background-color: #34495e;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
            width: calc(100% - 220px);
            height: 100vh;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 style="text-align:center;">Zamelda</h2>
        <a href="index.php">🏠 Beranda</a>
        <a href="index.php?page=mahasiswa">👨‍🎓 Mahasiswa</a>
        <a href="index.php?page=dosen">👨‍🏫 Dosen</a>
        <a href="index.php?page=krs">📘 KRS</a>
        <a href="index.php?page=absensi">📝 Absensi</a>
        <a href="index.php?page=jadwal_kuliah">📅 Jadwal Kuliah</a>
        <a href="index.php?page=nilai">📊 Nilai</a>
        <a href="index.php?page=mata_kuliah">📚 Mata Kuliah</a>
        <a href="index.php?page=registrasi">🗂️ Registrasi</a>
        <a href="index.php?page=pembayaran">💰 Pembayaran</a>
        <a href="index.php?page=ruangan">🏢 Ruangan</a>
        <a href="index.php?page=program_studi">🎓 Program Studi</a>
        <a href="index.php?page=dosen_wali">🧑‍💼 Dosen Wali</a>
    </div>

    <div class="content">
        <?php
        if (isset($_GET['page'])) {
            $page = basename($_GET['page']) . ".php";
            if (file_exists($page)) {
                echo "<iframe src='$page'></iframe>";
            } else {
                echo "<h3>Halaman tidak ditemukan.</h3>";
            }
        } else {
            echo "
            <div style='
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
                flex-direction: column;
                text-align: center;
            '>
                <h2>Selamat Datang di Sistem Akademik Zamelda</h2>
                <p>Silakan pilih menu di sebelah kiri untuk mengelola data akademik.</p>
                <br></br>
                <br></br>
                <p>Anggota:</p>
                <ul>
                    <p>Raesyitha Elga Rolobessy | 06.2023.107695</p>
                    <p>Madadina Adilah Pamuji | 06.2023.1.07725</p>
                    <p>Nazwa Zahira E.A | 06.2023.1.07739</p>
                </ul>
            </div>";
        }
        ?>
    </div>
</body>
</html>