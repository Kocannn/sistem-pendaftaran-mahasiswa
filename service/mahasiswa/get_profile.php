<?php
require_once __DIR__ . "/../db.php";

$sql = "
    SELECT 
        m.*,
        ps1.nama AS prodi_1_nama,
        ps1.jenjang AS prodi_1_jenjang,
        ps2.nama AS prodi_2_nama,
        ps2.jenjang AS prodi_2_jenjang
    FROM 
        mahasiswa m
    LEFT JOIN program_studi ps1 ON m.prodi_1_kode = ps1.kode
    LEFT JOIN program_studi ps2 ON m.prodi_2_kode = ps2.kode
    WHERE m.nisn = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['auth']['nisn']);
$stmt->execute();
$result = $stmt->get_result();
$profil = $result->fetch_assoc();
$stmt->close();
