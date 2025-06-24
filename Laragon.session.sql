
SELECT * FROM mahasiswa;

SELECT * FROM pembayaran WHERE status = 'Lunas';

CREATE VIEW view_mahasiswa AS
SELECT 
    m.id_mahasiswa,
    m.nim,
    m.nama_mahasiswa,
    m.jenis_kelamin,
    m.tanggal_lahir,
    m.alamat,
    m.email,
    m.no_telp,
    p.nama_prodi,
    p.jenjang,
    f.nama_fakultas,
    dw.periode AS periode_wali,
    d.nama_dosen AS dosen_wali,
    r.tahun_akademik AS tahun_registrasi,
    r.status_registrasi
FROM mahasiswa m
LEFT JOIN program_studi p ON m.id_prodi = p.id_prodi
LEFT JOIN fakultas f ON p.id_fakultas = f.id_fakultas
LEFT JOIN dosen_wali dw ON m.id_mahasiswa = dw.id_mahasiswa
LEFT JOIN dosen d ON dw.id_dosen = d.id_dosen
LEFT JOIN registrasi r ON m.id_mahasiswa = r.id_mahasiswa;

SELECT * FROM view_mahasiswa ORDER BY nama_mahasiswa LIMIT 5;


CREATE OR REPLACE VIEW view_pembayaran_terbaru AS
SELECT *
FROM pembayaran
WHERE status = 'Lunas'
ORDER BY tgl_bayar DESC;

SELECT * FROM view_pembayaran_terbaru;



CREATE OR REPLACE VIEW view_pembayaran_per_semester AS
SELECT 
    m.id_mahasiswa,
    m.nama_mahasiswa,
    r.tahun_akademik,
    SUM(p.jumlah) AS total_pembayaran,
    p.tgl_bayar AS tanggal_terakhir_bayar,
    p.status AS status_pembayaran
FROM pembayaran p
JOIN mahasiswa m ON m.id_mahasiswa = p.id_mahasiswa
JOIN registrasi r ON r.id_mahasiswa = p.id_mahasiswa
WHERE p.status = 'Lunas'
GROUP BY m.id_mahasiswa, r.tahun_akademik, p.tgl_bayar, p.status
ORDER BY m.nama_mahasiswa, r.tahun_akademik;

SELECT * FROM view_pembayaran_per_semester;

SELECT * FROM view_pembayaran_per_semester WHERE tanggal_terakhir_bayar BETWEEN '2025-01-01' AND '2025-01-18';

CREATE OR REPLACE VIEW view_total_pembayaran_mahasiswa AS
SELECT 
    m.id_mahasiswa,
    m.nama_mahasiswa,
    SUM(p.jumlah) AS total_pembayaran,
    MAX(p.tgl_bayar) AS pembayaran_terakhir
FROM pembayaran p
JOIN mahasiswa m ON m.id_mahasiswa = p.id_mahasiswa
WHERE p.status = 'Lunas'
GROUP BY m.id_mahasiswa, m.nama_mahasiswa;

SELECT * FROM view_total_pembayaran_mahasiswa;

--agregasi sum lunas
SELECT 
    id_mahasiswa,
    SUM(jumlah) AS total_pembayaran
FROM pembayaran
WHERE status = 'Lunas'
GROUP BY id_mahasiswa;

