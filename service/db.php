<?php

$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "sistem_pendaftaran_mahasiswa";

if (!isset($conn)) {
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Koneksi database gagal: " . $conn->connect_error);
    }
}
