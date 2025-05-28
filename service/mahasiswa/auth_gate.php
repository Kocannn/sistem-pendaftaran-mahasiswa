<?php
session_start();

// Cek apakah sudah login
if (!isset($_SESSION['auth'])) {
    header("Location: /sistem-informasi-pendaftaran/mahasiswa/login");
    exit();
}
