<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['logout'])) {
    session_start();
    session_unset();    // Hapus semua variabel session
    session_destroy();  // Hancurkan session
    header("Location: /mahasiswa/login");
    exit();
}
