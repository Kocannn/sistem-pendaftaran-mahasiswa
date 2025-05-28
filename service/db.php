<?php

$host = "127.0.0.1";
$user = "root";
$pass = "TANPAnama12@";
$db = "sistem_informasi_pendaftaran";

if (!isset($conn)) {
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Koneksi database gagal: " . $conn->connect_error);
    }
}
